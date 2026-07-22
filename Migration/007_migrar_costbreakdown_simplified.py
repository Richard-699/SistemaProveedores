import json, os
from db_config import get_db_connections, map_monedas, safe_truncate, log_migrated, log_skipped

def migrate():
    print("=" * 60)
    print("[7/10] Migrando costbreakdown simplified e history...")
    print("=" * 60)

    source_conn, target_conn = get_db_connections()
    source_cursor = source_conn.cursor(dictionary=True)
    target_cursor = target_conn.cursor()

    target_cursor.execute("SET FOREIGN_KEY_CHECKS=0;")

    # Cargar mapeo partnumber_text -> id
    map_file = os.path.join(os.path.dirname(__file__), '_partnumber_map.json')
    pn_map = {}
    if os.path.exists(map_file):
        with open(map_file, 'r', encoding='utf-8') as f:
            pn_map = json.load(f)

    # 1. Simplified
    source_cursor.execute("SELECT * FROM costbreakdown_simplified")
    rows_simp = source_cursor.fetchall()
    
    partnumber_to_provider = {}
    if rows_simp:
        for r in rows_simp:
            if r.get('partnumber_costbreakdown_simplified') and r.get('id_proveedor_simplified'):
                partnumber_to_provider[r['partnumber_costbreakdown_simplified']] = r['id_proveedor_simplified']


        insert_query = """
            REPLACE INTO proveedores_hwi_costbreakdown_simplified
            (id_costbreakdown_simplified, descripcion_costbreakdown_simplified,
             moneda_costbreakdown_simplified, precio_costbreakdown_simplified,
             porcentaje_costbreakdown_simplified, fecha_costbreakdown_simplified,
             id_proveedor_simplified, id_partnumber_costbreakdown_simplified)
            VALUES (%s, %s, %s, %s, %s, %s, %s, %s)
        """
        data_simp = []
        for row in rows_simp:
            moneda_id = map_monedas.get((row['moneda_costbreakdown_simplified'] or '').strip().upper(), 1)
            pn_id = pn_map.get(row['partnumber_costbreakdown_simplified'], 1)
            
            data_simp.append((
                row['id_costbreakdown_simplified'],
                row.get('descripcion_costbreakdown_simplified', ''),
                moneda_id,
                row['precio_costbreakdown_simplified'],
                row.get('porcentaje_costbreakdown_simplified', 0),
                row['fecha_costbreakdown_simplified'],
                safe_truncate(row['id_proveedor_simplified'], 25),
                pn_id
            ))
            
        target_cursor.executemany(insert_query, data_simp)
        target_conn.commit()
        log_migrated("costbreakdown_simplified -> proveedores_hwi_costbreakdown_simplified", len(data_simp))
    else:
        log_skipped("costbreakdown_simplified")


    # 2. History
    source_cursor.execute("SELECT * FROM costbreakdown_simplified_history")
    rows_hist = source_cursor.fetchall()
    
    if rows_hist:
        insert_query_hist = """
            REPLACE INTO proveedores_hwi_costbreakdown_simplified_history
            (id_costbreakdown_simplified_history, descripcion_costbreakdown_simplified_history,
             moneda_costbreakdown_simplified_history, precio_costbreakdown_simplified_history,
             porcentaje_costbreakdown_simplified_history, fecha_costbreakdown_simplified_history,
             id_proveedor_simplified_history, id_partnumber_costbreakdown_simplified_history)
            VALUES (%s, %s, %s, %s, %s, %s, %s, %s)
        """
        data_hist = []
        for row in rows_hist:
            # Notar que history mantuvo la columna partnumber como varchar, no como FK a id_partnumber!
            moneda_id = map_monedas.get((row['moneda_costbreakdown_simplified'] or '').strip().upper(), 1)
            
            # Buscar el proveedor real a partir del partnumber (ya que en la DB vieja history guarda un id truncado/numérico)
            pn_hist = row['partnumber_costbreakdown_simplified_history']
            real_provider_id = partnumber_to_provider.get(pn_hist, row['id_proveedor_simplified_history'])

            data_hist.append((
                row['id_costbreakdown_simplified_history'],
                row.get('descripcion_costbreakdown_simplified_history', ''),
                moneda_id,
                row['precio_costbreakdown_simplified'],
                row.get('porcentaje_costbreakdown_simplified_history', 0),
                row['fecha_costbreakdown_simplified_history'],
                safe_truncate(real_provider_id, 100),
                row['partnumber_costbreakdown_simplified_history']
            ))
            
        target_cursor.executemany(insert_query_hist, data_hist)
        target_conn.commit()
        log_migrated("costbreakdown_simplified_history -> proveedores_hwi_costbreakdown_simplified_history", len(data_hist))
    else:
        log_skipped("costbreakdown_simplified_history")

    target_cursor.execute("SET FOREIGN_KEY_CHECKS=1;")
    source_conn.close()
    target_conn.close()
    print()

if __name__ == "__main__":
    migrate()

