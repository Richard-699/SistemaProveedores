import os
import glob
import subprocess
from db_config import get_db_connections

# MAPEO DE TABLAS FUENTE -> DESTINO
TABLE_MAP = [
    # Script 001 - Administradores (viene de usuarios con rol 1,2)
    {
        'source_query': "SELECT COUNT(*) as total FROM usuarios WHERE id_rol_usuarios IN (1, 2)",
        'target_table': "proveedores_hwi_administradores",
        'label': "usuarios (rol 1,2) -> administradores",
    },
    # Script 002 - Proveedores (desde tabla proveedores)
    {
        'source_table': "proveedores",
        'target_table': "proveedores_hwi",
        'label': "proveedores -> proveedores_hwi",
    },
    # Verificación: usuarios rol 3 vs proveedores migrados
    {
        'source_query': "SELECT COUNT(*) as total FROM usuarios WHERE id_rol_usuarios = 3",
        'target_table': "proveedores_hwi",
        'label': "usuarios (rol 3) -> proveedores_hwi",
        'note': "Diferencia = usuarios rol 3 sin registro en tabla proveedores (huerfanos en BD vieja)",
    },
    # Script 003 - Correos
    {
        'source_query': "SELECT COUNT(*) as total FROM proveedores WHERE correo_proveedor IS NOT NULL AND correo_proveedor != ''",
        'target_table': "proveedores_hwi_correos",
        'label': "correo_proveedor -> correos",
        'note': "Destino puede ser mayor: un campo puede tener multiples correos separados por coma",
    },
    # Script 004 - Partnumbers
    {
        'source_table': "proveedor_partnumbers",
        'target_table': "proveedores_hwi_partnumbers",
        'label': "proveedor_partnumbers -> partnumbers",
    },
    # Script 005 - Costbreakdown principal
    {
        'source_table': "costbreakdown",
        'target_table': "proveedores_hwi_costbreakdown",
        'label': "costbreakdown -> costbreakdown",
    },
    # Script 006 - Costbreakdown detalles
    {
        'source_table': "costbreakdown_materia_prima",
        'target_table': "proveedores_hwi_costbreakdown_materia_prima",
        'label': "costbreakdown_materia_prima -> materia_prima",
    },
    {
        'source_table': "costbreakdown_proceso_productivo",
        'target_table': "proveedores_hwi_costbreakdown_proceso_productivo",
        'label': "costbreakdown_proceso_productivo -> proceso_productivo",
    },
    {
        'source_table': "costbreakdown_amortizacion",
        'target_table': "proveedores_hwi_costbreakdown_amortizacion",
        'label': "costbreakdown_amortizacion -> amortizacion",
    },
    # Script 007 - Costbreakdown simplified + history
    {
        'source_table': "costbreakdown_simplified",
        'target_table': "proveedores_hwi_costbreakdown_simplified",
        'label': "costbreakdown_simplified -> simplified",
    },
    {
        'source_table': "costbreakdown_simplified_history",
        'target_table': "proveedores_hwi_costbreakdown_simplified_history",
        'label': "costbreakdown_simplified_history -> history",
    },
    # Script 008 - LAFT completo
    {
        'source_table': "laft",
        'target_table': "proveedores_hwi_laft",
        'label': "laft -> laft",
    },
    {
        'source_table': "laft_beneficiarios_finales",
        'target_table': "proveedores_hwi_laft_beneficiarios_finales",
        'label': "laft_beneficiarios_finales -> beneficiarios",
    },
    {
        'source_table': "laft_certificaciones",
        'target_table': "proveedores_hwi_laft_certificaciones",
        'label': "laft_certificaciones -> certificaciones",
    },
    {
        'source_table': "laft_contacto",
        'target_table': "proveedores_hwi_laft_contacto",
        'label': "laft_contacto -> contacto",
    },
    {
        'source_table': "laft_documentos",
        'target_table': "proveedores_hwi_laft_documentos",
        'label': "laft_documentos -> documentos",
    },
    {
        'source_table': "laft_historico",
        'target_table': "proveedores_hwi_laft_historico",
        'label': "laft_historico -> historico",
    },
    {
        'source_table': "laft_pep",
        'target_table': "proveedores_hwi_laft_pep",
        'label': "laft_pep -> pep",
    },
    {
        'source_table': "laft_pep_infogeneral",
        'target_table': "proveedores_hwi_laft_pep_infogeneral",
        'label': "laft_pep_infogeneral -> pep_infogeneral",
    },
    {
        'source_table': "laft_persona_juridica",
        'target_table': "proveedores_hwi_laft_persona_juridica",
        'label': "laft_persona_juridica -> persona_juridica",
    },
    {
        'source_table': "laft_persona_natural",
        'target_table': "proveedores_hwi_laft_persona_natural",
        'label': "laft_persona_natural -> persona_natural",
    },
    {
        'source_table': "laft_representante_legal",
        'target_table': "proveedores_hwi_laft_representante_legal",
        'label': "laft_representante_legal -> representante_legal",
    },
    # Script 009 - Vinculación
    {
        'source_table': "vinculacion_proveedor",
        'target_table': "proveedores_hwi_vinculacion",
        'label': "vinculacion_proveedor -> vinculacion",
    },
    # Script 010 - Módulos ambientales
    {
        'source_table': "gestion_ambiental",
        'target_table': "proveedores_hwi_gestion_ambiental",
        'label': "gestion_ambiental -> gestion_ambiental",
    },
    {
        'source_table': "sostenibilidad_ambiental",
        'target_table': "proveedores_hwi_sostenibilidad_ambiental",
        'label': "sostenibilidad_ambiental -> sostenibilidad",
    },
    {
        'source_table': "politicas_ambientales",
        'target_table': "proveedores_hwi_politicas_ambientales",
        'label': "politicas_ambientales -> politicas",
    },
    {
        'source_table': "proyectos_programas_ambientales",
        'target_table': "proveedores_hwi_proyectos_programas_ambientales",
        'label': "proyectos_programas -> proyectos_programas",
    },
]


def count_table(cursor, table_name):
    """Cuenta registros de una tabla. Retorna -1 si la tabla no existe."""
    try:
        cursor.execute(f"SELECT COUNT(*) as total FROM `{table_name}`")
        result = cursor.fetchone()
        return result['total'] if isinstance(result, dict) else result[0]
    except Exception:
        return -1


def count_query(cursor, query):
    """Ejecuta un query COUNT personalizado. Retorna -1 si falla."""
    try:
        cursor.execute(query)
        result = cursor.fetchone()
        return result['total'] if isinstance(result, dict) else result[0]
    except Exception:
        return -1


def validar_migracion():
    """Compara la cantidad de registros en la base vieja vs la nueva para cada tabla migrada."""
    print()
    print("=" * 80)
    print("  VALIDACION POST-MIGRACION")
    print("  Comparando registros: sistema_proveedores_vieja vs sistema_proveedores")
    print("=" * 80)
    print()

    try:
        source_conn, target_conn = get_db_connections()
    except Exception as e:
        print(f"  [ERROR] No se pudo conectar a las bases de datos: {e}")
        return

    source_cursor = source_conn.cursor(dictionary=True)
    target_cursor = target_conn.cursor(dictionary=True)

    col_label = 50
    col_num = 10
    col_status = 8

    header = f"  {'Tabla':<{col_label}} {'Vieja':>{col_num}} {'Nueva':>{col_num}} {'Estado':>{col_status}}"
    print(header)
    print("  " + "-" * (col_label + col_num * 2 + col_status + 3))

    totals = {'ok': 0, 'warn': 0, 'fail': 0, 'empty': 0}
    problemas = []

    for mapping in TABLE_MAP:
        label = mapping['label']

        # Contar en fuente
        if 'source_query' in mapping:
            source_count = count_query(source_cursor, mapping['source_query'])
        else:
            source_count = count_table(source_cursor, mapping['source_table'])

        # Contar en destino
        target_count = count_table(target_cursor, mapping['target_table'])

        # Determinar estado
        if source_count == -1 or target_count == -1:
            status = "ERROR"
            icon = "X"
            totals['fail'] += 1
        elif source_count == 0 and target_count == 0:
            status = "VACIA"
            icon = "-"
            totals['empty'] += 1
        elif source_count == target_count:
            status = "OK"
            icon = "+"
            totals['ok'] += 1
        elif target_count > source_count:
            status = "OK+"
            icon = "+"
            totals['ok'] += 1
        elif target_count > 0 and target_count < source_count:
            status = "WARN"
            icon = "!"
            totals['warn'] += 1
        else:
            status = "FAIL"
            icon = "X"
            totals['fail'] += 1

        src_str = str(source_count) if source_count >= 0 else "N/A"
        tgt_str = str(target_count) if target_count >= 0 else "N/A"

        print(f"  [{icon}] {label:<{col_label}} {src_str:>{col_num}} {tgt_str:>{col_num}} {status:>{col_status}}")

        if 'note' in mapping and status in ('OK+', 'WARN'):
            print(f"      Nota: {mapping['note']}")

        if status in ('WARN', 'FAIL', 'ERROR'):
            problemas.append({
                'label': label,
                'source': source_count,
                'target': target_count,
                'status': status
            })

    # Resumen final
    print()
    print("  " + "=" * (col_label + col_num * 2 + col_status + 3))
    print(f"  RESUMEN:")
    print(f"    [+] Correctas:      {totals['ok']}")
    print(f"    [!] Con diferencia: {totals['warn']}")
    print(f"    [X] Con error:      {totals['fail']}")
    print(f"    [-] Vacias:         {totals['empty']}")
    print(f"    Total tablas:       {len(TABLE_MAP)}")
    print("  " + "=" * (col_label + col_num * 2 + col_status + 3))

    if problemas:
        print()
        print("  DETALLE DE TABLAS CON DIFERENCIAS:")
        print()
        for r in problemas:
            diff = r['source'] - r['target']
            print(f"    - {r['label']}")
            print(f"      Fuente: {r['source']} | Destino: {r['target']} | Diferencia: {diff} registros")
            print()

    source_conn.close()
    target_conn.close()

    print()
    if totals['fail'] == 0 and totals['warn'] == 0:
        print("  VALIDACION EXITOSA: Todos los registros fueron migrados correctamente.")
    else:
        print("  VALIDACION CON OBSERVACIONES: Revisar las tablas con diferencias arriba.")
    print()


def main():
    print("Iniciando Migracion Completa: sistema_proveedores_vieja -> sistema_proveedores\n")

    # Limpiar los mapas temporales viejos si existen
    for map_file in glob.glob('_*.json'):
        try:
            os.remove(map_file)
        except OSError:
            pass

    # Obtener los scripts en orden
    scripts = sorted(glob.glob('0*_migrar_*.py'))
    
    total = len(scripts)
    errores = []

    for i, script in enumerate(scripts, 1):
        print(f"\n--- Ejecutando {script} ({i}/{total}) ---")
        try:
            # Ejecutar cada script como un subproceso
            result = subprocess.run(['python', script], check=True, text=True, capture_output=True)
            print(result.stdout)
            if result.stderr:
                print("[WARN] Advertencias:", result.stderr)
        except subprocess.CalledProcessError as e:
            print(f"ERROR ejecutando {script}")
            print(e.stdout)
            print(e.stderr)
            errores.append(script)
            # Break on first error
            break
            
    print("\n" + "=" * 60)
    if not errores:
        print("OK: MIGRACION COMPLETADA EXITOSAMENTE")
        # Cleanup json map files
        for map_file in glob.glob('_*.json'):
            try:
                os.remove(map_file)
            except OSError:
                pass
        # Ejecutar validación post-migración
        validar_migracion()
    else:
        print("ERROR: LA MIGRACION FALLO EN LOS SIGUIENTES SCRIPTS:")
        for err in errores:
            print(f"  - {err}")
    print("=" * 60)

if __name__ == "__main__":
    main()

