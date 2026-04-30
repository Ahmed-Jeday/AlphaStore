import mysql.connector
import os
from dotenv import load_dotenv

load_dotenv('../../.env')

try:
    conn = mysql.connector.connect(
        host=os.getenv('DB_HOST', 'localhost'),
        user=os.getenv('DB_USER', 'root'),
        password=os.getenv('DB_PASS', ''),
        database=os.getenv('DB_NAME', 'alphastore')
    )
    print("Connected successfully!")
    cursor = conn.cursor()
    cursor.execute("SELECT COUNT(*) FROM produits")
    count = cursor.fetchone()[0]
    print(f"Products count: {count}")
    conn.close()
except Exception as e:
    print(f"Error: {e}")
