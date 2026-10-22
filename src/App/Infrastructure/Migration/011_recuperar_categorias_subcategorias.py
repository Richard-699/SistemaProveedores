import json
import sys
import os
import mysql.connector

sys.path.insert(0, r'c:\xampp\htdocs\SistemaProveedores')
from src.App.Infrastructure.Migration.db_config import db_config_target, db_config_source

def restore_categories():
    print("Conectando a la base de datos destino (sistema_proveedores)...")
    target_conn = mysql.connector.connect(**db_config_target)
    target_cursor = target_conn.cursor(dictionary=True)

    backup_path = r'C:\Users\prac.negociacion\.gemini\antigravity-ide\brain\8a36609a-cc0f-4fd9-a0d6-693231451408\scratch\backup_categorias_subcategorias.json'
    with open(backup_path, 'r', encoding='utf-8') as f:
        backup = json.load(f)

    cats_data = backup['proveedores_hwi_categorias']
    subcats_data = backup['proveedores_hwi_sub_categoria']
    prov_mappings = backup['proveedores_cat_subcat']

    print(f"Datos de respaldo cargados: {len(cats_data)} categorías, {len(subcats_data)} subcategorías, {len(prov_mappings)} proveedores.")

    try:
        target_cursor.execute("SET FOREIGN_KEY_CHECKS = 0;")

        # 1. Crear tabla proveedores_hwi_categorias si no existe
        print("\n1. Creando tabla proveedores_hwi_categorias...")
        create_cats_sql = """
        CREATE TABLE IF NOT EXISTS `proveedores_hwi_categorias` (
          `id_categoria` int(11) NOT NULL AUTO_INCREMENT,
          `descripcion_categoria` varchar(200) NOT NULL,
          PRIMARY KEY (`id_categoria`)
        ) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
        """
        target_cursor.execute(create_cats_sql)
        target_cursor.execute("TRUNCATE TABLE `proveedores_hwi_categorias`;")

        # Insertar categorías
        insert_cat_sql = "INSERT INTO `proveedores_hwi_categorias` (`id_categoria`, `descripcion_categoria`) VALUES (%s, %s)"
        for cat in cats_data:
            target_cursor.execute(insert_cat_sql, (cat['id_categoria'], cat['descripcion_categoria']))
            print(f"  [CAT] ID {cat['id_categoria']}: {cat['descripcion_categoria']}")

        # 2. Crear tabla proveedores_hwi_sub_categoria si no existe
        print("\n2. Creando tabla proveedores_hwi_sub_categoria...")
        create_subcats_sql = """
        CREATE TABLE IF NOT EXISTS `proveedores_hwi_sub_categoria` (
          `id_sub_categoria` int(11) NOT NULL AUTO_INCREMENT,
          `descripcion_sub_categoria` varchar(400) NOT NULL,
          `id_categoria_sub_categoria` int(11) NOT NULL,
          PRIMARY KEY (`id_sub_categoria`),
          KEY `id_categoria_sub_categoria` (`id_categoria_sub_categoria`),
          CONSTRAINT `proveedores_hwi_sub_categoria_ibfk_1` FOREIGN KEY (`id_categoria_sub_categoria`) REFERENCES `proveedores_hwi_categorias` (`id_categoria`) ON DELETE CASCADE ON UPDATE CASCADE
        ) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
        """
        target_cursor.execute(create_subcats_sql)
        target_cursor.execute("TRUNCATE TABLE `proveedores_hwi_sub_categoria`;")

        # Insertar subcategorías
        insert_subcat_sql = "INSERT INTO `proveedores_hwi_sub_categoria` (`id_sub_categoria`, `descripcion_sub_categoria`, `id_categoria_sub_categoria`) VALUES (%s, %s, %s)"
        for sub in subcats_data:
            target_cursor.execute(insert_subcat_sql, (sub['id_sub_categoria'], sub['descripcion_sub_categoria'], sub['id_categoria_sub_categoria']))
            print(f"  [SUBCAT] ID {sub['id_sub_categoria']}: {sub['descripcion_sub_categoria']} (Cat {sub['id_categoria_sub_categoria']})")

        # 3. Eliminar Foreign Keys existentes en proveedores_hwi que apuntaban a servicios suministros
        print("\n3. Verificando y eliminando constraints viejas en proveedores_hwi...")
        target_cursor.execute("""
            SELECT CONSTRAINT_NAME 
            FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS 
            WHERE TABLE_SCHEMA = DATABASE() 
              AND TABLE_NAME = 'proveedores_hwi' 
              AND CONSTRAINT_TYPE = 'FOREIGN KEY'
        """)
        existing_fks = [r['CONSTRAINT_NAME'] for r in target_cursor.fetchall()]

        if 'fk_proveedor_categoria' in existing_fks:
            print("  Eliminando fk_proveedor_categoria vieja...")
            target_cursor.execute("ALTER TABLE `proveedores_hwi` DROP FOREIGN KEY `fk_proveedor_categoria`;")
        if 'fk_proveedor_sub_categoria' in existing_fks:
            print("  Eliminando fk_proveedor_sub_categoria vieja...")
            target_cursor.execute("ALTER TABLE `proveedores_hwi` DROP FOREIGN KEY `fk_proveedor_sub_categoria`;")

        # 4. Restaurar categorías y subcategorías en proveedores_hwi
        print("\n4. Restaurando id_categoria_proveedor e id_sub_categoria_proveedor en proveedores_hwi...")
        update_prov_sql = """
            UPDATE `proveedores_hwi` 
            SET `id_categoria_proveedor` = %s, `id_sub_categoria_proveedor` = %s 
            WHERE `id_proveedor` = %s
        """
        updated_count = 0
        for p in prov_mappings:
            target_cursor.execute(update_prov_sql, (p['id_categoria_proveedor'], p['id_sub_categoria_proveedor'], p['id_proveedor']))
            if target_cursor.rowcount > 0:
                updated_count += 1

        # Limpiar cualquier proveedor que tenga valores inválidos que no existan en las categorías (como proveedores creados en pruebas recientes)
        target_cursor.execute("""
            UPDATE `proveedores_hwi` 
            SET `id_categoria_proveedor` = NULL, `id_sub_categoria_proveedor` = NULL 
            WHERE `id_categoria_proveedor` NOT IN (1, 2, 3) OR `id_categoria_proveedor` IS NULL;
        """)
        target_cursor.execute("""
            UPDATE `proveedores_hwi` 
            SET `id_sub_categoria_proveedor` = NULL 
            WHERE `id_sub_categoria_proveedor` NOT IN (1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14);
        """)

        print(f"  {updated_count} proveedores actualizados con sus categorías originales.")

        # 5. Agregar las Foreign Keys correctas hacia proveedores_hwi_categorias y proveedores_hwi_sub_categoria
        print("\n5. Creando Foreign Keys correctas en proveedores_hwi...")
        target_cursor.execute("""
            ALTER TABLE `proveedores_hwi`
            ADD CONSTRAINT `fk_proveedor_categoria`
            FOREIGN KEY (`id_categoria_proveedor`)
            REFERENCES `proveedores_hwi_categorias` (`id_categoria`)
            ON DELETE SET NULL ON UPDATE CASCADE;
        """)
        target_cursor.execute("""
            ALTER TABLE `proveedores_hwi`
            ADD CONSTRAINT `fk_proveedor_sub_categoria`
            FOREIGN KEY (`id_sub_categoria_proveedor`)
            REFERENCES `proveedores_hwi_sub_categoria` (`id_sub_categoria`)
            ON DELETE SET NULL ON UPDATE CASCADE;
        """)

        target_cursor.execute("SET FOREIGN_KEY_CHECKS = 1;")
        target_conn.commit()
        print("\n[ÉXITO] Transacción confirmada.")

        # 6. Verificación final
        print("\n=== VERIFICACIÓN FINAL ===")
        target_cursor.execute("SELECT COUNT(*) as count FROM proveedores_hwi_categorias")
        print(f"proveedores_hwi_categorias: {target_cursor.fetchone()['count']} registros")

        target_cursor.execute("SELECT COUNT(*) as count FROM proveedores_hwi_sub_categoria")
        print(f"proveedores_hwi_sub_categoria: {target_cursor.fetchone()['count']} registros")

        target_cursor.execute("SELECT COUNT(*) as count FROM proveedores_hwi_categorias_servicios_suministros")
        print(f"proveedores_hwi_categorias_servicios_suministros (separada): {target_cursor.fetchone()['count']} registros")

        target_cursor.execute("SELECT COUNT(*) as count FROM proveedores_hwi_sub_categoria_servicios_suministros")
        print(f"proveedores_hwi_sub_categoria_servicios_suministros (separada): {target_cursor.fetchone()['count']} registros")

        target_cursor.execute("""
            SELECT id_categoria_proveedor, COUNT(*) as c 
            FROM proveedores_hwi 
            GROUP BY id_categoria_proveedor
        """)
        print("\nDistribución de proveedores por categoría general:")
        for r in target_cursor.fetchall():
            print(f"  Cat {r['id_categoria_proveedor']}: {r['c']} proveedores")

        target_cursor.execute("""
            SELECT TABLE_NAME, COLUMN_NAME, CONSTRAINT_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME
            FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE() 
              AND TABLE_NAME = 'proveedores_hwi' 
              AND REFERENCED_TABLE_NAME IS NOT NULL
              AND COLUMN_NAME LIKE '%categoria%'
        """)
        print("\nForeign Keys en proveedores_hwi:")
        for r in target_cursor.fetchall():
            print(f"  {r['CONSTRAINT_NAME']}: {r['COLUMN_NAME']} -> {r['REFERENCED_TABLE_NAME']}({r['REFERENCED_COLUMN_NAME']})")

    except Exception as e:
        target_cursor.execute("SET FOREIGN_KEY_CHECKS = 1;")
        target_conn.rollback()
        print(f"\n[ERROR] {e}")
        import traceback
        traceback.print_exc()
    finally:
        target_conn.close()

if __name__ == '__main__':
    restore_categories()
