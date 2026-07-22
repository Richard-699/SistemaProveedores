import json, os
from db_config import get_db_connections, safe_truncate, log_migrated, log_skipped


def migrate():
    print("=" * 60)
    print("[3/10] Migrando correos de proveedores...")
    print("=" * 60)

    source_conn, target_conn = get_db_connections()
    target_cursor = target_conn.cursor()

    target_cursor.execute("SET FOREIGN_KEY_CHECKS=0;")

    # Cargar correos extraídos del campo correo_proveedor en script 003
    correos_file = os.path.join(os.path.dirname(__file__), '_correos_proveedores.json')
    correos_extra = []
    if os.path.exists(correos_file):
        with open(correos_file, 'r', encoding='utf-8') as f:
            correos_extra = json.load(f)

    # Verificar cuáles ya existen en proveedores_hwi_correos para no duplicar
    target_cursor.execute("SELECT id_proveedor_correo, correo FROM proveedores_hwi_correos")
    _ = target_cursor.fetchall() # Fetch to clear unread results
    # No usamos esto para REPLACE, simplemente insertamos los que vengan del campo

    insert_query = """
        INSERT IGNORE INTO proveedores_hwi_correos
        (id_proveedor_correo, correo)
        VALUES (%s, %s)
    """

    data = []
    for prov_id, correo in correos_extra:
        # Algunos proveedores tienen múltiples correos separados por ; o ,
        for c in correo.replace(';', ',').split(','):
            c = c.strip()
            if c and '@' in c:
                data.append((safe_truncate(prov_id, 35), c[:150]))

    if data:
        target_cursor.executemany(insert_query, data)
        target_conn.commit()
        log_migrated("correo_proveedor -> proveedores_hwi_correos", len(data))
    else:
        log_skipped("proveedores_hwi_correos", "Sin correos para migrar")

    target_cursor.execute("SET FOREIGN_KEY_CHECKS=1;")
    source_conn.close()
    target_conn.close()
    print()


if __name__ == "__main__":
    migrate()

