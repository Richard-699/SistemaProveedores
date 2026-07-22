from db_config import get_db_connections, safe_int, log_migrated, log_skipped



def migrate():
    print("=" * 60)
    print("[6/10] Migrando detalles de costbreakdown (materias primas, procesos, amortizaciÃ³n)...")
    print("=" * 60)

    source_conn, target_conn = get_db_connections()
    source_cursor = source_conn.cursor(dictionary=True)
    target_cursor = target_conn.cursor()

    target_cursor.execute("SET FOREIGN_KEY_CHECKS=0;")

    # 1. Materia Prima
    source_cursor.execute("SELECT * FROM costbreakdown_materia_prima")
    rows_mp = source_cursor.fetchall()
    if rows_mp:
        insert_query_mp = """
            REPLACE INTO proveedores_hwi_costbreakdown_materia_prima
            (id_materia_prima, nombre_materia_prima, moneda_unidad_materia_prima,
             unidad_materia_prima, unidad_pieza_materia_prima, moneda_pieza_materia_prima,
             porcentaje_total_materia_prima, total_moneda_pieza_materia_prima,
             porcentaje_final_materia_prima, id_costbreakdown_materia_prima)
            VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
        """
        data_mp = [(
            r['id_materia_prima'], r['nombre_materia_prima'], r['moneda_unidad_materia_prima'],
            r['unidad_materia_prima'], r['unidad_pieza_materia_prima'], r['moneda_pieza_materia_prima'],
            r.get('porcentaje_total_materia_prima'), r.get('total_moneda_pieza_materia_prima'),
            r.get('porcentaje_final_materia_prima'), r['id_costbreakdown_materia_prima']
        ) for r in rows_mp]
        target_cursor.executemany(insert_query_mp, data_mp)
        target_conn.commit()
        log_migrated("costbreakdown_materia_prima -> proveedores_hwi_costbreakdown_materia_prima", len(data_mp))
    else:
        log_skipped("costbreakdown_materia_prima")

    # 2. Proceso Productivo
    source_cursor.execute("SELECT * FROM costbreakdown_proceso_productivo")
    rows_pp = source_cursor.fetchall()
    if rows_pp:
        insert_query_pp = """
            REPLACE INTO proveedores_hwi_costbreakdown_proceso_productivo
            (id_proceso_productivo, etapa_proceso_productivo, nombre_maquina_proceso_productivo,
             cantidad_cavidades_proceso_productivo, tiempo_ciclo_proceso_productivo, eficiencia_proceso_productivo,
             costo_maquina_hora_proceso_productivo, cantidad_mano_obra_directa_proceso_productivo,
             mano_obra_directa_proceso_productivo, tiempo_setup_proceso_productivo,
             costo_setup_hora_proceso_productivo, lote_setup_proceso_productivo,
             costo_final_maquina_proceso_productivo, mano_obra_directa_final_proceso_productivo,
             costo_final_setup_hora_proceso_productivo, maquina_mano_obra_directa_setup_proceso_productivo,
             porcentaje_total_proceso_productivo, total_moneda_pieza_costo_maquina,
             porcentaje_final_moneda_pieza_costo_maquina, total_moneda_pieza_mano_obra_directa,
             porcentaje_final_moneda_pieza_mano_obra_directa, total_moneda_pieza_costo_setup,
             porcentaje_final_moneda_pieza_costo_setup, id_costbreakdown_proceso_productivo)
            VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
        """
        data_pp = [(
            r['id_proceso_productivo'], r['etapa_proceso_productivo'], r['nombre_maquina_proceso_productivo'],
            r['cantidad_cavidades_proceso_productivo'], r['tiempo_ciclo_proceso_productivo'], r['eficiencia_proceso_productivo'],
            r['costo_maquina_hora_proceso_productivo'], r['cantidad_mano_obra_directa_proceso_productivo'],
            r['mano_obra_directa_proceso_productivo'], r['tiempo_setup_proceso_productivo'],
            r['costo_setup_hora_proceso_productivo'], r['lote_setup_proceso_productivo'],
            r['costo_final_maquina_proceso_productivo'], r['mano_obra_directa_final_proceso_productivo'],
            r['costo_final_setup_hora_proceso_productivo'], r['maquina_mano_obra_directa_setup_proceso_productivo'],
            r.get('porcentaje_total_proceso_productivo'), r['total_moneda_pieza_costo_maquina'],
            r.get('porcentaje_final_moneda_pieza_costo_maquina'), r['total_moneda_pieza_mano_obra_directa'],
            r.get('porcentaje_final_moneda_pieza_mano_obra_directa'), r['total_moneda_pieza_costo_setup'],
            r.get('porcentaje_final_moneda_pieza_costo_setup'), r['id_costbreakdown_proceso_productivo']
        ) for r in rows_pp]
        target_cursor.executemany(insert_query_pp, data_pp)
        target_conn.commit()
        log_migrated("costbreakdown_proceso_productivo -> proveedores_hwi_costbreakdown_proceso_productivo", len(data_pp))
    else:
        log_skipped("costbreakdown_proceso_productivo")

    # 3. AmortizaciÃ³n
    source_cursor.execute("SELECT * FROM costbreakdown_amortizacion")
    rows_am = source_cursor.fetchall()
    if rows_am:
        insert_query_am = """
            REPLACE INTO proveedores_hwi_costbreakdown_amortizacion
            (id_amortizacion, descripcion_amortizacion, inversion_amortizacion,
             piezas_amortizadas, moneda_pieza_amortizacion, porcentaje_total_amortizacion,
             total_moneda_pieza_amortizacion, porcentaje_final_moneda_pieza_amortizacion,
             id_costbreakdown_amortizacion)
            VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)
        """
        data_am = [(
            r['id_amortizacion'], r['descripcion_amortizacion'], r['inversion_amortizacion'],
            r['piezas_amortizadas'], r['moneda_pieza_amortizacion'], r.get('porcentaje_total_amortizacion'),
            r.get('total_moneda_pieza_amortizacion'), r.get('porcentaje_final_moneda_pieza_amortizacion'),
            r['id_costbreakdown_amortizacion']
        ) for r in rows_am]
        target_cursor.executemany(insert_query_am, data_am)
        target_conn.commit()
        log_migrated("costbreakdown_amortizacion -> proveedores_hwi_costbreakdown_amortizacion", len(data_am))
    else:
        log_skipped("costbreakdown_amortizacion")

    target_cursor.execute("SET FOREIGN_KEY_CHECKS=1;")
    source_conn.close()
    target_conn.close()
    print()

if __name__ == "__main__":
    migrate()

