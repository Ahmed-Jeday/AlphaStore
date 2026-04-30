import mysql.connector
import os
from dotenv import load_dotenv

load_dotenv(os.path.join(os.path.dirname(__file__), '../../.env'))

def get_db_connection():
    return mysql.connector.connect(
        host=os.getenv('DB_HOST', 'localhost'),
        user=os.getenv('DB_USER', 'root'),
        password=os.getenv('DB_PASS', ''),
        database=os.getenv('DB_NAME', 'AlphaStore')
    )

def get_products_for_optimizer(category=None):
    conn = get_db_connection()
    cursor = conn.cursor(dictionary=True)
    
    # Fashion products (join with categories table)
    query_p = """
        SELECT p.id, p.name, p.price, p.image_path as image, p.stock, c.name as category, 'fashion' as type 
        FROM produits p
        JOIN categories c ON p.category_id = c.id
    """
    
    # Tech products (category is a direct column)
    query_t = """
        SELECT id, name, price, image_path as image, stock, category, 'tech' as type 
        FROM produits_t
    """
    
    if category and category.lower() != 'all':
        query_p += f" WHERE c.name = '{category}'"
        query_t += f" WHERE category = '{category}'"
        
    cursor.execute(query_p)
    products = cursor.fetchall()
    
    cursor.execute(query_t)
    products.extend(cursor.fetchall())
    
    cursor.close()
    conn.close()
    
    # Format products for the optimizer
    formatted_products = []
    for p in products:
        # Calculate a basic satisfaction score based on stock and price (for the GA)
        # Higher stock = more reliable (+1)
        # Lower price (relative) = better value (+2)
        # This will be refined in the GA logic if needed
        score = 0
        if p['stock'] > 10: score += 1
        if p['price'] < 50: score += 2
        
        formatted_products.append({
            "id": p['id'],
            "name": p['name'],
            "price": float(p['price']),
            "image": p['image'],
            "category": p['category'],
            "type": p['type'],
            "score": score
        })
        
    return formatted_products

def get_cart_products_for_optimizer(user_id):
    conn = get_db_connection()
    cursor = conn.cursor(dictionary=True)
    
    query = """
        SELECT c.produit_id as id, 
               c.quantite,
               COALESCE(p.name, pt.name) as name, 
               COALESCE(p.price, pt.price) as price, 
               COALESCE(p.image_path, pt.image_path) as image, 
               COALESCE(p.stock, pt.stock) as stock,
               COALESCE(cat.name, pt.category) as category,
               CASE WHEN p.id IS NOT NULL THEN 'fashion' ELSE 'tech' END as type
        FROM cart c 
        LEFT JOIN produits p ON c.produit_id = p.id 
        LEFT JOIN produits_t pt ON c.produit_id = pt.id 
        LEFT JOIN categories cat ON p.category_id = cat.id
        WHERE c.user_id = %s
    """
    
    cursor.execute(query, (user_id,))
    products = cursor.fetchall()
    
    cursor.close()
    conn.close()
    
    formatted_products = []
    for p in products:
        score = 0
        if p['stock'] and p['stock'] > 10: score += 1
        if p['price'] and float(p['price']) < 50: score += 2
        
        # Add the product multiple times based on quantity in cart
        qty = p.get('quantite', 1)
        for _ in range(qty):
            formatted_products.append({
                "id": p['id'],
                "name": p['name'],
                "price": float(p['price']) if p['price'] else 0,
                "image": p['image'],
                "category": p['category'],
                "type": p['type'],
                "score": score
            })
        
    return formatted_products
