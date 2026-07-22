import json, os
from db_config import (get_db_connections, map_commodity, safe_truncate,
                       log_migrated, log_skipped)


def migrate():
    print("=" * 60)
    print("[4/10] Migrando partnumbers...")
    print("=" * 60)

    source_conn, target_conn = get_db_connections()
    source_cursor = source_conn.cursor(dictionary=True)
    target_cursor = target_conn.cursor()

    target_cursor.execute("SET FOREIGN_KEY_CHECKS=0;")

    source_cursor.execute("""
        SELECT partnumber, descripcion_partnumber, imagen_partnumber,
               porcentaje_peso_bom_partnumber, commodity_partnumber,
               id_proveedor_partnumber
        FROM proveedor_partnumbers
    """)
    rows = source_cursor.fetchall()

    if not rows:
        log_skipped("proveedor_partnumbers -> proveedores_hwi_partnumbers")
        target_cursor.execute("SET FOREIGN_KEY_CHECKS=1;")
        source_conn.close()
        target_conn.close()
        return

    insert_query = """
        REPLACE INTO proveedores_hwi_partnumbers
        (partnumber, descripcion_partnumber, imagen_partnumber,
         porcentaje_peso_bom_partnumber, id_commodity_partnumber,
         id_proveedor_partnumber)
        VALUES (%s, %s, %s, %s, %s, %s)
    """

    data = []
    sin_commodity = 0
    for row in rows:
        # Mapear commodity texto -> ID
        commodity_text = (row['commodity_partnumber'] or '').strip().upper()
        commodity_id = map_commodity.get(commodity_text)

        if commodity_id is None:
            sin_commodity += 1
            commodity_id = 1  # Default: STEEL

        data.append((
            row['partnumber'],
            row['descripcion_partnumber'],
            row.get('imagen_partnumber'),
            row.get('porcentaje_peso_bom_partnumber', 0) or 0,
            commodity_id,
            safe_truncate(row['id_proveedor_partnumber'], 25)
        ))

    target_cursor.executemany(insert_query, data)
    target_conn.commit()
    log_migrated("proveedor_partnumbers -> proveedores_hwi_partnumbers", len(data))

    if sin_commodity > 0:
        print(f"  [WARN] {sin_commodity} partnumbers sin commodity válido (asignado default=STEEL)")

    # Guardar mapeo partnumber_text -> id para costbreakdown
    target_cursor2 = target_conn.cursor(dictionary=True)
    target_cursor2.execute("SELECT id_partnumber, partnumber FROM proveedores_hwi_partnumbers")
    pn_map = {r['partnumber']: r['id_partnumber'] for r in target_cursor2.fetchall()}

    map_file = os.path.join(os.path.dirname(__file__), '_partnumber_map.json')
    with open(map_file, 'w', encoding='utf-8') as f:
        json.dump(pn_map, f, ensure_ascii=False)
    print(f"  [INFO] Mapeo partnumber guardado: {len(pn_map)} registros")

    target_cursor.execute("SET FOREIGN_KEY_CHECKS=1;")
    source_conn.close()
    target_conn.close()
    print()


if __name__ == "__main__":
    migrate()

