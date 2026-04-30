import os
from flask import Flask, request, jsonify
from flask_cors import CORS
from dotenv import load_dotenv
from genetic_optimizer import GeneticOptimizer
from database import get_products_for_optimizer, get_cart_products_for_optimizer

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

@app.route('/api/health', methods=['GET'])
def health():
    return jsonify({"status": "healthy"})

if __name__ == '__main__':
    app.run(port=5001, debug=True)
