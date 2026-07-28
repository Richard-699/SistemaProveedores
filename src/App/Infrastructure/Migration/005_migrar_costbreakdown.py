import json, os
from db_config import (get_db_connections, map_monedas, map_incoterms, 
                       safe_truncate, log_migrated, log_skipped)

def migrate():
    print("=" * 60)
    print("[5/10] Migrando costbreakdown principal...")
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

    source_cursor.execute("SELECT * FROM costbreakdown")
    rows = source_cursor.fetchall()

    if not rows:
        log_skipped("costbreakdown -> proveedores_hwi_costbreakdown")
        target_cursor.execute("SET FOREIGN_KEY_CHECKS=1;")
        source_conn.close()
        target_conn.close()
        return

    insert_query = """
        REPLACE INTO proveedores_hwi_costbreakdown
        (id_costbreakdown, id_proveedor_costbreakdow, diligencio_costbreakdown,
         fecha_costbreakdown, moneda_costbreakdown, incoterm_costbreakdown,
         volumen_anual_costbreakdown, moneda_pieza_embalaje_costbreakdown,
         porcentaje_total_embalaje_costbreakdown, porcentaje_scrap_costbreakdown,
         moneda_pieza_scrap_costbreakdown, porcentaje_total_scrap_costbreakdown,
         porcentaje_flete_costbreakdown, moneda_pieza_flete_costbreakdown,
         porcentaje_sga_costbreakdown, moneda_pieza_sga_costbreakdown,
         porcentaje_margen_beneficio_costbreakdown, moneda_pieza_margen_beneficio_costbreakdown,
         precio_neto_total_costbreakdown, id_partnumber_costbreakdown)
        VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
    """

    data = []
    sin_pn = 0
    
    for row in rows:
        moneda_text = (row['moneda_costbreakdown'] or '').strip().upper()
        moneda_id = map_monedas.get(moneda_text, 1) # Default: COP

        incoterm_text = (row['incoterm_costbreakdown'] or '').strip().upper()
        incoterm_id = map_incoterms.get(incoterm_text, None)

        pn_text = row['partnumber_costbreakdown']
        id_partnumber = pn_map.get(pn_text)
        
        if id_partnumber is None:
            # Fallback en caso de que no exista en pn_map
            # (Aunque deberían estar, pero por integridad referencial)
            sin_pn += 1
            # Como es FK NOT NULL y necesitamos uno valido, tomamos el 1 como fallback temporal
            id_partnumber = 1 

        data.append((
            row['id_costbreakdown'],
            safe_truncate(row['id_proveedor_costbreakdow'], 25),
            row['diligencio_costbreakdown'],
            row['fecha_costbreakdown'],
            moneda_id,
            incoterm_id,
            row.get('volumen_anual_costbreakdown', 0) or 0,
            row.get('moneda_pieza_embalaje', 0) or 0,
            row.get('porcentaje_total_embalaje', 0) or 0,
            row.get('porcentaje_scrap', 0) or 0,
            row.get('moneda_pieza_scrap', 0) or 0,
            row.get('porcentaje_total_scrap', 0) or 0,
            row.get('porcentaje_flete', 0) or 0,
            row.get('moneda_pieza_flete', 0) or 0,
            row.get('porcentaje_SGA', 0) or 0,
            row.get('moneda_pieza_SGA', 0) or 0,
            row.get('porcentaje_margen_beneficio', 0) or 0,
            row.get('moneda_pieza_margen_beneficio', 0) or 0,
            row.get('precio_neto_total', 0) or 0,
            id_partnumber
        ))

    target_cursor.executemany(insert_query, data)
    target_conn.commit()
    log_migrated("costbreakdown -> proveedores_hwi_costbreakdown", len(data))

    if sin_pn > 0:
        print(f"  [WARN] {sin_pn} registros de costbreakdown sin partnumber_id válido (asignado default=1)")

    target_cursor.execute("SET FOREIGN_KEY_CHECKS=1;")
    source_conn.close()
    target_conn.close()
    print()

if __name__ == "__main__":
    migrate()

