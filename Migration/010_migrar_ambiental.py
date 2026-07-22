from db_config import get_db_connections, safe_truncate, log_migrated, log_skipped

def migrate():
    print("=" * 60)
    print("[10/10] Migrando modulos ambientales...")
    print("=" * 60)

    source_conn, target_conn = get_db_connections()
    source_cursor = source_conn.cursor(dictionary=True)
    target_cursor = target_conn.cursor()

    target_cursor.execute("SET FOREIGN_KEY_CHECKS=0;")

    # 1. GestiÃ³n Ambiental
    source_cursor.execute("SELECT * FROM gestion_ambiental")
    rows = source_cursor.fetchall()
    if rows:
        insert_query = """
            REPLACE INTO proveedores_hwi_gestion_ambiental
            (id_gestion_ambiental, cuenta_sistema_gestion_ambiental,
             certificado_ISO_14001_gestion_ambiental, cuenta_departamento_gestion_politica_ambiental,
             tiene_identificados_aspectos_impactos_gestion_ambiental, principales_requisitos_legales_gestion_ambiental,
             realiza_registro_anual_autoridades_gestion_ambiental, ha_obtenido_sancion_gestion_ambiental,
             permiso_uso_recursos_naturales, permisos_cuenta_gestion_ambiental,
             plan_manejo_integral_residuos_gestion_ambiental, genera_residuos_posconsumo_gestion_ambiental,
             controles_realiza_gestion_residuos_solidos_peligrosos_gestion_am,
             genera_vertimiento_aguas_residuales_industriales_gestion_ambient,
             controles_realiza_gestion_vertimientos_gestion_ambiental,
             genera_emisiones_atmosfericas_gestion_ambiental, controles_realiza_gestion_emisiones_gestion_ambiental,
             plan_contingencia_manejo_transporte_gestion_ambiental,
             controles_realiza_gestion_sustancias_quimicas_gestion_ambiental,
             observaciones_gestion_ambiental, id_proveedor_gestion_ambiental)
            VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
        """
        data = [(
            r['Id_gestion_ambiental'], r['cuenta_sistema_gestion_ambiental'], r['certificado_ISO_14001'],
            r['cuenta_departamento_gestion_politica_ambiental'], r['tiene_identificados_aspectos_impactos'],
            r['principales_requisitos_legales'], r['realiza_registro_anual_autoridades'], r['ha_obtenido_sancion'],
            r['permiso_uso_recursos_naturales'], r.get('permisos_cuenta'), r['plan_manejo_integral_residuos'],
            r['genera_residuos_posconsumo'], r['controles_realiza_gestion_residuos_solidos_peligrosos'],
            r['genera_vertimiento_aguas_residuales_industriales'], r['controles_realiza_gestion_vertimientos'],
            r['genera_emisiones_atmosfericas'], r['controles_realiza_gestion_emisiones'],
            r['plan_contingencia_manejo_transporte'], r['controles_realiza_gestion_sustancias_quimicas'],
            r.get('observaciones_gestion_ambiental'), safe_truncate(r['Id_proveedor_gestion_ambiental'], 25)
        ) for r in rows]
        target_cursor.executemany(insert_query, data)
        target_conn.commit()
        log_migrated("gestion_ambiental -> proveedores_hwi_gestion_ambiental", len(data))

    # 2. Sostenibilidad Ambiental
    source_cursor.execute("SELECT * FROM sostenibilidad_ambiental")
    rows = source_cursor.fetchall()
    if rows:
        insert_query = """
            REPLACE INTO proveedores_hwi_sostenibilidad_ambiental
            (id_sostenibilidad_ambiental, identificado_grupos_interes_sostenibilidad_ambiental,
             realizado_analisis_materialidad_sostenibilidad_ambiental,
             cuenta_estrategia_sostenibilidad_ambiental,
             priorizado_objetivos_desarrollo_sostenible_sostenibilidad_ambien,
             cuenta_programas_inversion_sostenibilidad_ambiental,
             cuenta_programas_mejorar_desempeno_ambiental,
             cuenta_programas_buen_gobierno_corporativo_sostenibilidad_ambien,
             inscrito_iniciativa_fondos_sostenibles, realiza_reporte_memoria_sostenibilidad,
             id_proveedor_sostenibilidad_ambiental)
            VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
        """
        data = [(
            r['Id_sostenibilidad_ambiental'], r['identificado_grupos_interes'], r['realizado_analisis_materialidad'],
            r['cuenta_estrategia_sostenibilidad'], r['priorizado_objetivos_desarrollo_sostenible'], r['cuenta_programas_inversion'],
            r['cuenta_programas_mejorar_desempeno_ambiental'], r['cuenta_programas_buen_gobierno_corporativo'],
            r['inscrito_iniciativa_fondos_sostenibles'], r['realiza_reporte_memoria_sostenibilidad'],
            safe_truncate(r['Id_proveedor_sostenibilidad_ambiental'], 25)
        ) for r in rows]
        target_cursor.executemany(insert_query, data)
        target_conn.commit()
        log_migrated("sostenibilidad_ambiental -> proveedores_hwi_sostenibilidad_ambiental", len(data))

    # 3. PolÃ­ticas Ambientales
    source_cursor.execute("SELECT * FROM politicas_ambientales")
    rows = source_cursor.fetchall()
    if rows:
        insert_query = """
            REPLACE INTO proveedores_hwi_politicas_ambientales
            (id_politica_ambiental, politica_sostenibilidad_politica_ambiental,
             politica_ambiental, seguridad_salud_trabajo_politica_ambiental,
             politica_derechos_humanos_politica_ambiental,
             politica_debida_diligencia_politica_ambiental,
             politica_prevencion_politica_ambiental,
             codigo_etica_empresarial_politica_ambiental,
             politica_igualdad_politica_ambiental,
             id_proveedor_politicas_ambientales_politica_ambiental)
            VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
        """
        data = [(
            r['Id_politica_ambiental'], r['politica_sostenibilidad'], r['politica_ambiental'],
            r['seguridad_salud_trabajo'], r['politica_derechos_humanos'], r['politica_debida_diligencia'],
            r['politica_prevencion'], r['codigo_etica_empresarial'],
            r['politica_igualdad'],
            safe_truncate(r['Id_proveedor_politicas_ambientales'], 25)
        ) for r in rows]
        target_cursor.executemany(insert_query, data)
        target_conn.commit()
        log_migrated("politicas_ambientales -> proveedores_hwi_politicas_ambientales", len(data))

    # 4. Proyectos Programas Ambientales
    source_cursor.execute("SELECT * FROM proyectos_programas_ambientales")
    rows = source_cursor.fetchall()
    if rows:
        insert_query = """
            REPLACE INTO proveedores_hwi_proyectos_programas_ambientales
            (id_proyectos_programas_ambientales, produccion_limpia_programas_ambientales,
             economia_circular_programas_ambientales, cambio_climatico_programas_ambientales,
             huella_carbono_programas_ambientales, net_zero_carbono_neutro_programas_ambientales,
             energias_renovables_programas_ambientales, energia_verde_i_rec_programas_ambientales,
             eficiencia_energetica_programas_ambientales, ecoeficiencia_operacional_programas_ambientales,
             sustancias_quimicas_ambientalmente_amigables_programas_ambiental,
             reutilizacion_recirculacion_agua_programas_ambientales,
             aprovechamiento_aguas_lluvias_programas_ambientales,
             automatizacion_digitalizacion_papel_cero_programas_ambientales,
             basura_cero_programas_ambientales, cero_vertimientos_programas_ambientales,
             cero_emisiones_programas_ambientales, ecodiseno_productos_embalajes_programas_ambientales,
             analisis_ciclo_vida_programas_ambientales, contratacion_personas_discapacidad_programas_ambientales,
             contratacion_mujeres_altos_cargos_directivos_programas_ambiental,
             seleccion_contratacion_criterios_diversidad_programas_ambientale,
             derechos_laborales_programas_ambientales,
             evaluacion_proveedores_criterios_sociales_ambientales_programas_,
             desarrollo_cadena_suministro_local_programas_ambientales,
             inversiones_sostenibles_programas_ambientales,
             id_proveedor_proyectos_programas_ambientales)
            VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
        """
        data = [(
            r['Id_proyectos_programas_ambientales'], r['produccion_limpia'], r['economia_circular'],
            r['cambio_climatico'], r['huella_carbono'], r['net_zero_carbono_neutro'], r['energias_renovables'],
            r['energia_verde_I_REC'], r['eficiencia_energetica'], r['ecoeficiencia_operacional'],
            r['sustancias_quimicas_ambientalmente_amigables'], r['reutilizacion_recirculacion_agua'],
            r['aprovechamiento_aguas_lluvias'], r['automatizacion_digitalizacion_papel_cero'],
            r['basura_cero'], r['cero_vertimientos'], r['cero_emisiones'], r['ecodiseno_productos_embalajes'],
            r['analisis_ciclo_vida'], r['contratacion_personas_discapacidad'], r['contratacion_mujeres_altos_cargos_directivos'],
            r['seleccion_contratacion_criterios_diversidad'], r['derechos_laborales'],
            r['evaluacion_proveedores_criterios_sociales_ambientales'], r['desarrollo_cadena_suministro_local'],
            r['inversiones_sostenibles'], safe_truncate(r['Id_proveedor_proyectos_programas_ambientales'], 25)
        ) for r in rows]
        target_cursor.executemany(insert_query, data)
        target_conn.commit()
        log_migrated("proyectos_programas_ambientales -> proveedores_hwi_proyectos_programas_ambientales", len(data))

    target_cursor.execute("SET FOREIGN_KEY_CHECKS=1;")
    source_conn.close()
    target_conn.close()
    print()

if __name__ == "__main__":
    migrate()

