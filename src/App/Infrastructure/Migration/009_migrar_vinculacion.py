from db_config import get_db_connections, map_estado_vinculacion, safe_truncate, log_migrated, log_skipped

def migrate():
    print("=" * 60)
    print("[9/10] Migrando vinculacion_proveedor...")
    print("=" * 60)

    source_conn, target_conn = get_db_connections()
    source_cursor = source_conn.cursor(dictionary=True)
    target_cursor = target_conn.cursor()

    target_cursor.execute("SET FOREIGN_KEY_CHECKS=0;")

    source_cursor.execute("SELECT * FROM vinculacion_proveedor")
    rows = source_cursor.fetchall()
    
    if not rows:
        log_skipped("vinculacion_proveedor -> proveedores_hwi_vinculacion")
        target_cursor.execute("SET FOREIGN_KEY_CHECKS=1;")
        source_conn.close()
        target_conn.close()
        return

    insert_query = """
        REPLACE INTO proveedores_hwi_vinculacion
        (id_vinculacion_proveedor, id_estado_cumplimiento_vinculacion, observaciones_cumplimiento_vinculacion,
         id_estado_ambiental_vinculacion, observaciones_ambiental_vinculacion,
         id_estado_negociacion_vinculacion, observaciones_negociacion_vinculacion,
         id_proveedor_vinculacion_proveedor)
        VALUES (%s, %s, %s, %s, %s, %s, %s, %s)
    """

    data = []
    for row in rows:
        # Los estados en vieja BD son 1(aprobado), 0(rechazado), NULL(pendiente)
        # En la nueva son FK a id_estado: 1(Rechazado), 2(Aprobado), 3(Pendiente)
        est_cump = map_estado_vinculacion.get(row['aprobado_cumplimiento'], 3)
        est_amb = map_estado_vinculacion.get(row['aprobado_ambiental'], 3)
        est_neg = map_estado_vinculacion.get(row['aprobado_negociacion'], 3)

        data.append((
            row['Id_vinculacion_proveedor'],
            est_cump,
            row.get('observaciones_cumplimiento'),
            est_amb,
            row.get('observaciones_ambiental'),
            est_neg,
            row.get('observaciones_negociacion'),
            safe_truncate(row['Id_proveedor_vinculacion_proveedor'], 25)
        ))

    target_cursor.executemany(insert_query, data)
    target_conn.commit()
    log_migrated("vinculacion_proveedor -> proveedores_hwi_vinculacion", len(data))

    target_cursor.execute("SET FOREIGN_KEY_CHECKS=1;")
    source_conn.close()
    target_conn.close()
    print()

if __name__ == "__main__":
    migrate()

