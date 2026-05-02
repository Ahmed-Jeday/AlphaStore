"""
Database helpers for PC Builder feature.
"""
import mysql.connector
import os
from dotenv import load_dotenv

load_dotenv(os.path.join(os.path.dirname(__file__), '../../.env'))


def get_db_connection():
    return mysql.connector.connect(
        host=os.getenv('DB_HOST', 'localhost'),
        user=os.getenv('DB_USER', 'root'),
        password=os.getenv('DB_PASS', ''),
        database=os.getenv('DB_NAME', 'alphastore')
    )


def get_all_pc_components() -> list[dict]:
    """Return all PC components from the database."""
    conn = get_db_connection()
    cursor = conn.cursor(dictionary=True)
    cursor.execute("""
        SELECT id, name, component_type, brand, price, stock, image_url,
               performance_score, tdp, socket, form_factor, ram_type,
               ram_slots, ram_modules, wattage, gpu_max_length, gpu_length, specs
        FROM pc_components
        ORDER BY component_type, performance_score DESC
    """)
    rows = cursor.fetchall()
    cursor.close()
    conn.close()

    # Normalise decimal / None values
    for r in rows:
        r['price'] = float(r['price'] or 0)
        r['performance_score'] = int(r['performance_score'] or 50)
        r['tdp'] = int(r['tdp'] or 0)
        r['stock'] = int(r['stock'] or 0)
    return rows


def save_pc_build(user_id: int, component_ids: list[int], total_price: float, usage_profile: str = 'gaming') -> int:
    """Persist a completed build. Returns the new build id."""
    conn = get_db_connection()
    cursor = conn.cursor()
    cursor.execute(
        "INSERT INTO pc_builds (user_id, total_price, usage_profile) VALUES (%s, %s, %s)",
        (user_id, total_price, usage_profile)
    )
    build_id = cursor.lastrowid
    for cid in component_ids:
        cursor.execute(
            "INSERT INTO pc_build_items (build_id, component_id) VALUES (%s, %s)",
            (build_id, cid)
        )
    conn.commit()
    cursor.close()
    conn.close()
    return build_id


def get_user_builds(user_id: int) -> list[dict]:
    """Return all builds (with items) for a given user."""
    conn = get_db_connection()
    cursor = conn.cursor(dictionary=True)
    cursor.execute("""
        SELECT b.id, b.name, b.total_price, b.usage_profile, b.created_at,
               GROUP_CONCAT(bi.component_id) AS component_ids
        FROM pc_builds b
        LEFT JOIN pc_build_items bi ON bi.build_id = b.id
        WHERE b.user_id = %s
        GROUP BY b.id
        ORDER BY b.created_at DESC
    """, (user_id,))
    rows = cursor.fetchall()
    cursor.close()
    conn.close()
    for r in rows:
        r['total_price'] = float(r['total_price'] or 0)
        cids = r.get('component_ids')
        r['component_ids'] = [int(x) for x in cids.split(',')] if cids else []
    return rows
