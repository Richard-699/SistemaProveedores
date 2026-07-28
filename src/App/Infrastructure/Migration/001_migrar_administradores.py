from db_config import get_db_connections, generate_uuid, map_estado_activo, log_migrated, log_skipped

admin_map = {}


def migrate():
    global admin_map
    print("=" * 60)
    print("[1/10] Migrando usuarios (rol 1,2) -> administradores...")
    print("=" * 60)

    source_conn, target_conn = get_db_connections()
    source_cursor = source_conn.cursor(dictionary=True)
    target_cursor = target_conn.cursor()

    target_cursor.execute("SET FOREIGN_KEY_CHECKS=0;")

    # Obtener usuarios con rol 1 (Super-Admin) y rol 2 (Usuario/Negociador)
    source_cursor.execute("""
        SELECT Id_usuario, nombre_usuario, apellidos_usuario, correo_usuario,
               password_usuario, Id_area_usuario, estado_registro, is_temporal
        FROM usuarios
        WHERE id_rol_usuarios IN (1, 2)
          AND (correo_usuario LIKE '%@hacebwhirlpool%' OR correo_usuario LIKE '%@whirlpool%')
    """)
    rows = source_cursor.fetchall()

    if not rows:
        log_skipped("usuarios -> proveedores_hwi_administradores")
        target_cursor.execute("SET FOREIGN_KEY_CHECKS=1;")
        source_conn.close()
        target_conn.close()
        return admin_map

    # Fetch existing UUIDs to prevent overriding
    target_cursor.execute("SELECT correo_hwi_administrador, id_administrador FROM proveedores_hwi_administradores")
    existing_admins = {str(row[0]).lower().strip(): row[1] for row in target_cursor.fetchall()}

    insert_query = """
        INSERT INTO proveedores_hwi_administradores
        (id_administrador, nombre_administrador, apellidos_administrador,
         correo_hwi_administrador, id_area_administrador,
         password_administrador, id_estado_administrador, password_is_temporal)
        VALUES (%s, %s, %s, %s, %s, %s, %s, %s)
        ON DUPLICATE KEY UPDATE
        nombre_administrador = VALUES(nombre_administrador),
        apellidos_administrador = VALUES(apellidos_administrador),
        id_area_administrador = VALUES(id_area_administrador),
        password_administrador = VALUES(password_administrador),
        id_estado_administrador = VALUES(id_estado_administrador),
        password_is_temporal = VALUES(password_is_temporal)
    """

    data = []
    for row in rows:
        correo = str(row['correo_usuario']).lower().strip()
        
        # Preserve existing UUID if the user is already in the target database
        if correo in existing_admins:
            new_uuid = existing_admins[correo]
        else:
            new_uuid = generate_uuid()

        # Guardar mapeo correo -> UUID para vincular proveedores después
        admin_map[correo] = new_uuid

        estado_nuevo = map_estado_activo.get(row['estado_registro'], 5)  # Default: Inactivo
        area = row['Id_area_usuario'] if row['Id_area_usuario'] else 1  # Default: primera área

        data.append((
            new_uuid,
            row['nombre_usuario'] or '',
            row['apellidos_usuario'] or '',
            row['correo_usuario'], # Use original casing for DB
            area,
            row['password_usuario'],
            estado_nuevo,
            row['is_temporal']
        ))

    target_cursor.executemany(insert_query, data)
    target_conn.commit()
    log_migrated("usuarios (rol 1,2) -> proveedores_hwi_administradores", len(data))

    # Guardar el mapeo en un archivo temporal para que otros scripts lo usen
    import json, os
    map_file = os.path.join(os.path.dirname(__file__), '_admin_map.json')
    with open(map_file, 'w', encoding='utf-8') as f:
        json.dump(admin_map, f, ensure_ascii=False)
    print(f"  [INFO] Mapeo admin guardado en _admin_map.json ({len(admin_map)} registros)")

    target_cursor.execute("SET FOREIGN_KEY_CHECKS=1;")
    source_conn.close()
    target_conn.close()
    print()
    return admin_map


if __name__ == "__main__":
    migrate()

