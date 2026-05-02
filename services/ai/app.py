import os
from flask import Flask, request, jsonify
from flask_cors import CORS
from dotenv import load_dotenv
from genetic_optimizer import GeneticOptimizer
from database import get_products_for_optimizer, get_cart_products_for_optimizer, get_products_for_csp
from csp_mix_match import OutfitCSP
from pc_database import get_all_pc_components, save_pc_build
from pc_csp import PCCompatibilityCSP
from pc_genetic import PCRecommendationGA

load_dotenv(os.path.join(os.path.dirname(__file__), '../../.env'))

app = Flask(__name__)
CORS(app)  # Enable CORS for all routes

@app.route('/api/optimize', methods=['POST'])
def optimize():
    data = request.json
    budget = float(data.get('budget', 100))
    category = data.get('category', None)
    user_id = data.get('user_id', None)
    
    # Fetch products (from cart if user_id is provided, otherwise from all products)
    if user_id:
        products = get_cart_products_for_optimizer(user_id)
    else:
        products = get_products_for_optimizer(category)
    
    if not products:
        return jsonify({"error": "No products found for this category"}), 404
        
    optimizer = GeneticOptimizer(products, budget)
    result = optimizer.run()
    
    return jsonify(result)

VALID_MIX_MATCH_SEASONS = frozenset({'ete', 'hiver', 'mi_saison', 'toutes_saisons'})


@app.route('/api/mix-match', methods=['POST'])
def mix_match():
    data = request.json or {}
    anchor_id = data.get('product_id')
    try:
        budget = float(data.get('budget', 250))
    except (TypeError, ValueError):
        return jsonify({"error": "Invalid budget"}), 400
    budget = max(10.0, min(1000.0, budget))

    season = data.get('season')
    if isinstance(season, str):
        season = season.strip().lower()
        season = ''.join(c for c in season if c.isalnum() or c == '_') or None
    else:
        season = None
    if season not in VALID_MIX_MATCH_SEASONS:
        season = None
    
    # Fetch all products for CSP
    all_products = get_products_for_csp()
    
    # Find anchor product (handle both int and string IDs)
    anchor_product = next((p for p in all_products if str(p['id']) == str(anchor_id)), None)
    
    if not anchor_product:
        return jsonify({"error": "Anchor product not found"}), 404
        
    # Initialize CSP
    constraints = {
        "budget": budget,
        "season": season
    }
    
    csp = OutfitCSP(anchor_product, all_products, constraints)
    solutions = csp.solve(max_solutions=5)
    
    return jsonify(solutions)

@app.route('/api/pc-components', methods=['GET'])
def pc_components():
    """Return all PC components grouped by type."""
    try:
        components = get_all_pc_components()
        grouped = {}
        for comp in components:
            t = comp['component_type']
            grouped.setdefault(t, [])
            grouped[t].append(comp)
        return jsonify(grouped)
    except Exception as e:
        return jsonify({"error": str(e)}), 500


@app.route('/api/pc-filter', methods=['POST'])
def pc_filter():
    """
    CSP endpoint: given selected components and budget,
    returns annotated domains (ok/reason) for each component type.
    Body: { selected: {cpu: {id, ...}, ...}, budget: 1500 }
    """
    data = request.json or {}
    try:
        budget = float(data.get('budget', 1500))
    except (TypeError, ValueError):
        return jsonify({"error": "Invalid budget"}), 400

    selected_raw = data.get('selected', {})

    all_components = get_all_pc_components()

    # Build a map of id -> component for quick lookup
    comp_map = {str(c['id']): c for c in all_components}

    # Resolve selected IDs to full component dicts
    selected = {}
    for comp_type, comp_data in selected_raw.items():
        if comp_data and 'id' in comp_data:
            resolved = comp_map.get(str(comp_data['id']))
            if resolved:
                selected[comp_type] = resolved

    csp = PCCompatibilityCSP(all_components, selected, budget)
    domains = csp.get_valid_domains()

    # Serialise: convert component dicts to JSON-safe format
    result = {}
    for t, items in domains.items():
        result[t] = [
            {
                'component': item['component'],
                'ok': item['ok'],
                'reason': item.get('reason'),
                'selected': item.get('selected', False),
            }
            for item in items
        ]
    return jsonify(result)


@app.route('/api/pc-recommend', methods=['POST'])
def pc_recommend():
    """
    GA endpoint: given fixed components, budget, and usage profile,
    recommends the best components for unselected categories.
    Body: { selected: {...}, budget: 1500, usage_profile: 'gaming' }
    """
    data = request.json or {}
    try:
        budget = float(data.get('budget', 1500))
    except (TypeError, ValueError):
        return jsonify({"error": "Invalid budget"}), 400

    selected_raw = data.get('selected', {})
    usage_profile = data.get('usage_profile', 'gaming')
    if usage_profile not in ('gaming', 'workstation', 'budget', 'streaming'):
        usage_profile = 'gaming'

    all_components = get_all_pc_components()
    comp_map = {str(c['id']): c for c in all_components}

    # Resolve selected
    fixed = {}
    for comp_type, comp_data in selected_raw.items():
        if comp_data and 'id' in comp_data:
            resolved = comp_map.get(str(comp_data['id']))
            if resolved:
                fixed[comp_type] = resolved

    # Get CSP-filtered domains
    csp = PCCompatibilityCSP(all_components, fixed, budget)
    annotated_domains = csp.get_valid_domains()

    # Build valid-only domains (ok=True, not already fixed) for GA
    valid_domains = {}
    for t, items in annotated_domains.items():
        if t in fixed:
            continue
        valid_components = [item['component'] for item in items if item['ok'] and not item['selected']]
        if valid_components:
            valid_domains[t] = valid_components

    # Remaining budget after fixed selections
    spent = sum(float(c.get('price') or 0) for c in fixed.values())
    remaining_budget = budget - spent

    ga = PCRecommendationGA(valid_domains, fixed, remaining_budget, usage_profile)
    result = ga.run()

    return jsonify(result)


@app.route('/api/health', methods=['GET'])
def health():
    return jsonify({"status": "healthy"})

if __name__ == '__main__':
    app.run(port=5001, debug=True)
