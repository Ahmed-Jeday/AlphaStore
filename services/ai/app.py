import os
from flask import Flask, request, jsonify
from flask_cors import CORS
from dotenv import load_dotenv
from genetic_optimizer import GeneticOptimizer
from database import get_products_for_optimizer, get_cart_products_for_optimizer, get_products_for_csp
from csp_mix_match import OutfitCSP

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

@app.route('/api/health', methods=['GET'])
def health():
    return jsonify({"status": "healthy"})

if __name__ == '__main__':
    app.run(port=5001, debug=True)
