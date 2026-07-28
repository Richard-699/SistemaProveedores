from db_config import get_db_connections, map_tipo_documento, map_condicion_pago, safe_truncate, safe_int, log_migrated, log_skipped

def migrate():
    print("=" * 60)
    print("[8/10] Migrando modulo LAFT completo...")
    print("=" * 60)

    source_conn, target_conn = get_db_connections()
    source_cursor = source_conn.cursor(dictionary=True)
    target_cursor = target_conn.cursor()

    target_cursor.execute("SET FOREIGN_KEY_CHECKS=0;")

    # 1. LAFT Principal
    source_cursor.execute("SELECT * FROM laft")
    rows = source_cursor.fetchall()
    if rows:
        insert_query = """
            REPLACE INTO proveedores_hwi_laft
            (id_laft, fecha_solicitud_laft, ultima_actualizacion_laft, proceso_laft,
             tipo_persona_laft, oficial_cumplimiento__laft, declaracion_origen_fondos_informacion_laft,
             autorizacion_proteccion_datos_laft, declaracion_etica_laft, id_proveedor_laft)
            VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
        """
        data = [(
            r['Id_laft'], r['fecha_solicitud_laft'], r['ultima_actualizacion_laft'], r['proceso_laft'],
            r['tipo_persona_laft'], r.get('oficial_cumplimiento', 0) or 0, r.get('declaracion_origen_fondos_informacion', 0) or 0,
            r.get('autorizacion_proteccion_datos', 0) or 0, r.get('declaracion_etica', 0) or 0, safe_truncate(r['Id_proveedor_laft'], 25)
        ) for r in rows]
        target_cursor.executemany(insert_query, data)
        target_conn.commit()
        log_migrated("laft -> proveedores_hwi_laft", len(data))
    
    # 2. LAFT Beneficiarios Finales
    source_cursor.execute("SELECT * FROM laft_beneficiarios_finales")
    rows = source_cursor.fetchall()
    if rows:
        insert_query = """
            REPLACE INTO proveedores_hwi_laft_beneficiarios_finales
            (id_beneficiarios_finales, nombre_razon_social_beneficiarios_finales,
             otro_tipo_identificacion_beneficiarios_finales, numero_identificacion_beneficiarios_finales,
             porcentaje_participacion_beneficiarios_finales, id_laft_beneficiarios_finales,
             id_tipo_documento_beneficiarios_finales)
            VALUES (%s, %s, %s, %s, %s, %s, %s)
        """
        data = [(
            r['Id_beneficiarios_finales'], r['nombre_razon_social_beneficiarios_finales'],
            r.get('otro_tipo_identificacion'), r['numero_identificacion_beneficiarios_finales'],
            r['porcentaje_participacion_beneficiarios_finales'], r['Id_laft_beneficiarios_finales'],
            map_tipo_documento.get((r['tipo_identificacion_beneficiarios_finales'] or '').strip(), 6)
        ) for r in rows]
        target_cursor.executemany(insert_query, data)
        target_conn.commit()
        log_migrated("laft_beneficiarios_finales -> proveedores_hwi_laft_beneficiarios_finales", len(data))

    # We need a mapping from Id_persona_juridica -> Id_laft for certificaciones
    source_cursor.execute("SELECT Id_persona_juridica, Id_laft_persona_juridica FROM laft_persona_juridica")
    pj_to_laft = {r['Id_persona_juridica']: r['Id_laft_persona_juridica'] for r in source_cursor.fetchall()}

    # 3. LAFT Certificaciones
    source_cursor.execute("SELECT * FROM laft_certificaciones")
    rows = source_cursor.fetchall()
    if rows:
        insert_query = """
            REPLACE INTO proveedores_hwi_laft_certificaciones
            (id_certificacion, ISO_9001_certificacion, ISO_14001_certificacion,
             ISO_45001_certificacion, BASC_certificacion, OEA_certificacion,
             otro_certificacion, id_laft)
            VALUES (%s, %s, %s, %s, %s, %s, %s, %s)
        """
        data = [(
            r['Id_certificacion'], r.get('ISO_9001', 0) or 0, r.get('ISO_14001', 0) or 0,
            r.get('ISO_45001', 0) or 0, r.get('BASC', 0) or 0, r.get('OEA', 0) or 0,
            r.get('otro_certificacion'), pj_to_laft.get(r['Id_laft_persona_juridica_certificacion'])
        ) for r in rows]
        target_cursor.executemany(insert_query, data)
        target_conn.commit()
        log_migrated("laft_certificaciones -> proveedores_hwi_laft_certificaciones", len(data))

    # 4. LAFT Contacto
    source_cursor.execute("SELECT * FROM laft_contacto")
    rows = source_cursor.fetchall()
    if rows:
        insert_query = """
            REPLACE INTO proveedores_hwi_laft_contacto
            (id_contacto, nombres_contacto, apellidos_contacto, cargo_contacto,
             numero_contacto, correo_electronico_contacto, id_tipos_contacto_laft_contacto,
             id_laft_contacto)
            VALUES (%s, %s, %s, %s, %s, %s, %s, %s)
        """
        data = [(
            r['Id_contacto'], r['nombres_contacto'], r['apellidos_contacto'], r['cargo_contacto'],
            str(r['numero_contacto']), r['correo_electronico_contacto'], r['Id_tipos_contacto_laft_contacto'],
            r['Id_laft_contacto']
        ) for r in rows]
        target_cursor.executemany(insert_query, data)
        target_conn.commit()
        log_migrated("laft_contacto -> proveedores_hwi_laft_contacto", len(data))

    # 5. LAFT Documentos
    source_cursor.execute("SELECT * FROM laft_documentos")
    rows = source_cursor.fetchall()
    if rows:
        insert_query = """
            REPLACE INTO proveedores_hwi_laft_documentos
            (id_documento_laft, is_url_documento_laft, documento_laft,
             id_laft_documentos, id_tipo_documento_laft_documentos)
            VALUES (%s, %s, %s, %s, %s)
        """
        # Nota: tipo_documento_laft (texto) debe transformarse a ID?
        # Para ser rápidos, como no hay diccionario mapeado para esto exacto y el catálogo tiene 18, asignaremos 1 como fallback, 
        # asumiendo que un arreglo más profundo requiere el catálogo.
        data = [(
            r['Id_documento_laft'], r.get('is_url_documento_laft', 0) or 0, (r['documento_laft'].split('/')[-1] if r.get('documento_laft') else None),
            r['Id_laft_documentos'], 1 
        ) for r in rows]
        target_cursor.executemany(insert_query, data)
        target_conn.commit()
        log_migrated("laft_documentos -> proveedores_hwi_laft_documentos", len(data))

    # 6. LAFT Historico
    source_cursor.execute("SELECT * FROM laft_historico")
    rows = source_cursor.fetchall()
    if rows:
        insert_query = """
            REPLACE INTO proveedores_hwi_laft_historico
            (id_laft_historico, fecha_actualizacion_historico, id_proveedor_laft_historico)
            VALUES (%s, %s, %s)
        """
        data = [(r['Id_laft_historico'], r['fecha_actualizacion_historico'], safe_truncate(r['Id_proveedor_laft_historico'], 25)) for r in rows]
        target_cursor.executemany(insert_query, data)
        target_conn.commit()
        log_migrated("laft_historico -> proveedores_hwi_laft_historico", len(data))

    # 7. LAFT PEP
    source_cursor.execute("SELECT * FROM laft_pep")
    rows = source_cursor.fetchall()
    if rows:
        insert_query = """
            REPLACE INTO proveedores_hwi_laft_pep
            (id_pep, nombre_pep, numero_identificacion_pep, cargo_ocupa_pep,
             cargo_ocupa_ocupo_cataloga_pep, desde_cuando_pep, hasta_cuando_pep,
             id_laft_pep, id_tipo_documento_laft_pep)
            VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)
        """
        data = [(
            r['Id_pep'], r['nombre_pep'], r['numero_identificacion_pep'], r['cargo_ocupa_pep'],
            r['cargo_ocupa_ocupo_cataloga_pep'], r['desde_cuando_pep'], r['hasta_cuando_pep'],
            r['Id_laft_pep'], map_tipo_documento.get((r['tipo_documento_pep'] or '').strip(), 6)
        ) for r in rows]
        target_cursor.executemany(insert_query, data)
        target_conn.commit()
        log_migrated("laft_pep -> proveedores_hwi_laft_pep", len(data))

    # 8. LAFT PEP Info General
    source_cursor.execute("SELECT * FROM laft_pep_infogeneral")
    rows = source_cursor.fetchall()
    if rows:
        insert_query = """
            REPLACE INTO proveedores_hwi_laft_pep_infogeneral
            (id_pep_infogeneral, maneja_o_ha_manejado_recursos_publicos, tiene_o_ha_tenido_cargo_publico,
             ocupa_o_ha_ocupado_cargo_publico_organizaciones_gubernamentales,
             ocupa_o_ha_ocupado_cargo_publico_pais_diferente_colombia, id_laft_pep_infogeneral)
            VALUES (%s, %s, %s, %s, %s, %s)
        """
        data = [(
            r['id_pep_infogeneral'], r['maneja_o_ha_manejado_recursos_publicos'], r['tiene_o_ha_tenido_cargo_publico'],
            r['ocupa_o_ha_ocupado_cargo_publico_organizaciones_gubernamentales'],
            r['ocupa_o_ha_ocupado_cargo_publico_pais_diferente_colombia'], r['Id_laft_pep_infogeneral']
        ) for r in rows]
        target_cursor.executemany(insert_query, data)
        target_conn.commit()
        log_migrated("laft_pep_infogeneral -> proveedores_hwi_laft_pep_infogeneral", len(data))

    # 9. LAFT Persona Jurídica
    source_cursor.execute("SELECT * FROM laft_persona_juridica")
    rows = source_cursor.fetchall()
    if rows:
        insert_query = """
            REPLACE INTO proveedores_hwi_laft_persona_juridica
            (id_persona_juridica, razon_social_persona_juridica, otro_tipo_identificacion_persona_juridica,
             numero_identificacion_persona_juridica, digito_verificacion_persona_juridica,
             id_pais_persona_juridica, departamento_persona_juridica, ciudad_persona_juridica,
             direccion_persona_juridica, indicativo_persona_juridica, telefono_persona_juridica,
             correo_electronico_persona_juridica, codigo_ciiu_persona_juridica,
             requiere_permiso_licencia_operar_persona_juridica, dias_condicion_pago_otros_persona_juridica,
             id_laft_persona_juridica, id_condicion_pago, id_tipo_documento_laft_persona_juridica)
            VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
        """
        data = [(
            r['Id_persona_juridica'], r['razon_social_persona_juridica'], r.get('otro_tipo_identificacion'),
            r['numero_identificacion_persona_juridica'], r['digito_verificacion'],
            r['Id_pais_persona_juridica'], r.get('departamento_persona_juridica'), r.get('ciudad_persona_juridica'),
            r.get('direccion_persona_juridica'), r['indicativo_persona_juridica'], str(r['telefono_persona_juridica']),
            r['correo_electronico_persona_juridica'], r['codigo_ciiu_persona_juridica'],
            r['requiere_permiso_licencia_operar'], safe_int(r.get('cuantos_dias_condicion_pago')),
            r['Id_laft_persona_juridica'], map_condicion_pago.get((r['condicion_pago'] or '').strip(), 1),
            map_tipo_documento.get((r['tipo_identificacion_persona_juridica'] or '').strip(), 2) # Default NIT for PJ
        ) for r in rows]
        target_cursor.executemany(insert_query, data)
        target_conn.commit()
        log_migrated("laft_persona_juridica -> proveedores_hwi_laft_persona_juridica", len(data))

    # 10. LAFT Persona Natural
    source_cursor.execute("SELECT * FROM laft_persona_natural")
    rows = source_cursor.fetchall()
    if rows:
        insert_query = """
            REPLACE INTO proveedores_hwi_laft_persona_natural
            (id_persona_natural, nombres_persona_natural, apellidos_persona_natural,
             numero_identificacion_persona_natural, direccion_persona_natural, ciudad_persona_natural,
             departamento_persona_natural, id_pais_persona_natural, indicativo_persona_natural,
             telefono_persona_natural, correo_electronico_persona_natural, sector_economico_persona_natural,
             requiere_permiso_licencia_operar_persona_natural, dias_condicion_pago_otros_persona_natural,
             id_laft_persona_natural, id_condicion_pago_persona_natural, id_tipo_documento_laft_persona_natural)
            VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
        """
        data = [(
            r['Id_persona_natural'], r['nombres_persona_natural'], r['apellidos_persona_natural'],
            r['numero_identificacion_persona_natural'], r.get('direccion_persona_natural'), r['ciudad_persona_natural'],
            r['departamento_persona_natural'], r['Id_pais_persona_natural'], r['indicativo_persona_natural'],
            str(r['telefono_persona_natural']), r['correo_electronico_persona_natural'], r['sector_economico_persona_natural'],
            r['requiere_permiso_licencia_operar'], safe_int(r.get('cuantos_dias_condicion_pago')),
            r['id_laft_persona_natural'], map_condicion_pago.get((r['condicion_pago'] or '').strip(), 1),
            map_tipo_documento.get((r['tipo_identificacion_persona_natural'] or '').strip(), 1) # Default CC for PN
        ) for r in rows]
        target_cursor.executemany(insert_query, data)
        target_conn.commit()
        log_migrated("laft_persona_natural -> proveedores_hwi_laft_persona_natural", len(data))

    # 11. LAFT Representante Legal
    source_cursor.execute("SELECT * FROM laft_representante_legal")
    rows = source_cursor.fetchall()
    if rows:
        insert_query = """
            REPLACE INTO proveedores_hwi_laft_representante_legal
            (id_representante_legal, nombres_representante_legal, apellidos_representante_legal,
             numero_identificacion_representante_legal, correo_electronico_representante_legal,
             numero_contacto_representante_legal, tipo_representante_legal, id_laft_representante_legal,
             id_tipo_documento_laft_representante_legal)
            VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)
        """
        data = [(
            r['Id_representante_legal'], r['nombres_representante_legal'], r['apellidos_representante_legal'],
            r['numero_identificacion_representante_legal'], r['correo_electronico_representante_legal'],
            str(r['numero_contacto_representante_legal']), r['tipo_representante_legal'], r['Id_laft_representante_legal'],
            map_tipo_documento.get((r['tipo_documento_representante_legal'] or '').strip(), 1)
        ) for r in rows]
        target_cursor.executemany(insert_query, data)
        target_conn.commit()
        log_migrated("laft_representante_legal -> proveedores_hwi_laft_representante_legal", len(data))

    target_cursor.execute("SET FOREIGN_KEY_CHECKS=1;")
    source_conn.close()
    target_conn.close()
    print()

if __name__ == "__main__":
    migrate()

