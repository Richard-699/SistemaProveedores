import json, os
from db_config import (get_db_connections, map_tipo_proveedor, map_idioma,
                       map_estado_activo, safe_truncate, log_migrated, log_skipped)


def migrate():
    print("=" * 60)
    print("[2/10] Migrando proveedores + credenciales de usuarios...")
    print("=" * 60)

    source_conn, target_conn = get_db_connections()
    source_cursor = source_conn.cursor(dictionary=True)
    target_cursor = target_conn.cursor()

    target_cursor.execute("SET FOREIGN_KEY_CHECKS=0;")

    # Cargar mapeo admin (correo -> UUID)
    map_file = os.path.join(os.path.dirname(__file__), '_admin_map.json')
    admin_map = {}
    if os.path.exists(map_file):
        with open(map_file, 'r', encoding='utf-8') as f:
            admin_map = json.load(f)
        print(f"  [INFO] Mapeo admin cargado: {len(admin_map)} registros")

    # PASO 1: Obtener credenciales de usuarios rol 3 (proveedores)
    source_cursor.execute("""
        SELECT id_proveedor_usuarios, correo_usuario, password_usuario, is_temporal
        FROM usuarios
        WHERE id_rol_usuarios = 3 AND id_proveedor_usuarios IS NOT NULL
    """)
    user_credentials = {}
    for row in source_cursor.fetchall():
        prov_id = row['id_proveedor_usuarios']
        if prov_id:
            user_credentials[prov_id.strip()] = {
                'usuario': row['correo_usuario'],
                'password': row['password_usuario'],
                'is_temporal': row['is_temporal']
            }
    print(f"  [INFO] Credenciales de proveedores encontradas: {len(user_credentials)}")

    # PASO 2: Migrar proveedores
    source_cursor.execute("SELECT * FROM proveedores")
    rows = source_cursor.fetchall()

    if not rows:
        log_skipped("proveedores -> proveedores_hwi")
        target_cursor.execute("SET FOREIGN_KEY_CHECKS=1;")
        source_conn.close()
        target_conn.close()
        return

    insert_query = """
        REPLACE INTO proveedores_hwi
        (id_proveedor, numero_acreedor_proveedor, nombre_proveedor,
         id_tipo_proveedor, id_idioma_proveedor,
         maneja_formato_costbreakdown_proveedor, historia_proveedor,
         descripcion_proveedor, porcentaje_bom_proveedor, logo_proveedor,
         id_srm_proveedor, id_categoria_proveedor, id_sub_categoria_proveedor,
         formulario_ambiental_proveedor, permitir_carta_beneficiarios_finales_proveedor,
         id_administrador_proveedor, id_estado_proveedor,
         usuario_proveedor, password_proveedor, password_is_temporal_proveedor)
        VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
    """

    data = []
    correos_to_insert = []
    sin_credenciales = 0

    for row in rows:
        prov_id_raw = row['Id_proveedor']
        prov_id = safe_truncate(prov_id_raw, 25)

        # Mapear tipo_proveedor texto -> ID
        tipo = map_tipo_proveedor.get(row['tipo_proveedor'], 1)

        # Mapear idioma texto -> ID
        idioma = map_idioma.get(row['idioma_proveedor'], 1)

        # Mapear estado (proveedor_aprobado 0/1 -> estado FK)
        estado = map_estado_activo.get(row['proveedor_aprobado'], 5)

        # Buscar id_administrador_proveedor por correo_negociador
        correo_neg = row.get('correo_negociador', '')
        id_administrador_proveedor = None
        if correo_neg:
            id_administrador_proveedor = admin_map.get(correo_neg.lower().strip())

        # Obtener credenciales del usuario proveedor
        creds = user_credentials.get(prov_id_raw, user_credentials.get(prov_id, None))
        if creds:
            usuario = creds['usuario']
            password = creds['password']
            is_temporal = creds['is_temporal']
        else:
            sin_credenciales += 1
            # Proveedor sin usuario en tabla usuarios — crear credenciales por defecto
            usuario = row.get('correo_proveedor') or prov_id
            password = ''
            is_temporal = 1

        data.append((
            prov_id,
            row.get('numero_acreedor'),
            row['nombre_proveedor'],
            tipo,
            idioma,
            row.get('maneja_formato_costbreakdown', 0) or 0,
            row.get('historia_proveedor', '') or '',
            row.get('descripcion_proveedor', '') or '',
            row.get('porcentaje_bom_proveedor', 0) or 0,
            row.get('logo_proveedor'),
            row.get('id_srm_proveedor'),
            row.get('Id_categoria', 1) or 1,
            row.get('Id_sub_categoria'),
            row.get('formulario_ambiental', 0) or 0,
            row.get('carta_beneficiarios_finales', 0),
            id_administrador_proveedor,
            estado,
            usuario,
            password,
            is_temporal
        ))

        # Guardar correo del proveedor para migrar a tabla correos
        correo_prov = row.get('correo_proveedor')
        if correo_prov and correo_prov.strip():
            correos_to_insert.append((prov_id, correo_prov.strip()))

    target_cursor.executemany(insert_query, data)
    target_conn.commit()
    log_migrated("proveedores -> proveedores_hwi", len(data))

    if sin_credenciales > 0:
        print(f"  [WARN] {sin_credenciales} proveedores sin credenciales en tabla usuarios")

    # Guardar correos para script 004
    correos_file = os.path.join(os.path.dirname(__file__), '_correos_proveedores.json')
    with open(correos_file, 'w', encoding='utf-8') as f:
        json.dump(correos_to_insert, f, ensure_ascii=False)
    print(f"  [INFO] {len(correos_to_insert)} correos de proveedores guardados para migración")

    target_cursor.execute("SET FOREIGN_KEY_CHECKS=1;")
    source_conn.close()
    target_conn.close()
    print()


if __name__ == "__main__":
    migrate()

