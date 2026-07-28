import mysql.connector
import uuid

# CONFIGURACIÓN DE BASES DE DATOS
db_config_source = {
    'host': '127.0.0.1',
    'user': 'root',
    'password': '',
    'database': 'sistema_proveedores_vieja',
    'charset': 'utf8mb4'
}

db_config_target = {
    'host': '127.0.0.1',
    'user': 'root',
    'password': '',
    'database': 'sistema_proveedores',
    'charset': 'utf8mb4'
}

# DICCIONARIOS DE MAPEO

# Moneda texto -> ID
map_monedas = {'COP': 1, 'USD': 2, 'EUR': 3}

# Incoterm texto -> ID
map_incoterms = {
    'EXW': 1, 'FCA': 2, 'CPT': 3, 'CIP': 4,
    'DAP': 5, 'DPU': 6, 'DDP': 7, 'FAS': 8,
    'FOB': 9, 'CFR': 10, 'CIF': 11
}

# Tipo proveedor texto -> ID
map_tipo_proveedor = {'Directo': 1, 'Indirecto': 2}

# Idioma texto -> ID
map_idioma = {'Es': 1, 'En': 2}

# Estado proveedor/admin viejo -> nuevo
# Viejo: 1=activo, 0=inactivo -> Nuevo: 4=Activo, 5=Inactivo
map_estado_activo = {1: 4, 0: 5}

# Vinculación vieja -> nueva
# Viejo: 1=aprobado, 0=rechazado, NULL=pendiente
# Nuevo: 2=Aprobado, 1=Rechazado, 3=Pendiente
map_estado_vinculacion = {1: 2, 0: 1, None: 3}

# Tipo documento texto -> ID (proveedores_hwi_tipo_documento)
map_tipo_documento = {
    'Cedula Ciudadania': 1, 'Cédula de Ciudadanía': 1, 'CC': 1,
    'NIT': 2, 'Nit': 2,
    'Cedula Extranjeria': 3, 'Cédula de Extranjería': 3, 'CE': 3,
    'Pasaporte': 4, 'PAS': 4,
    'Tarjeta de Identidad': 5, 'TI': 5,
    'ID': 6, 'Otro': 6, 'otro': 6, '': 6
}

# Condición de pago texto -> ID
map_condicion_pago = {
    'Inmediato': 1, '0': 1,
    '30': 2,
    '60': 3,
    '90': 4,
    'Otro': 5, 'otro': 5, '15': 5, '45': 5
}

# Commodity texto -> ID (proveedores_hwi_commodities)
map_commodity = {
    'STEEL': 1,
    'FASTENERS': 2,
    'RESINS': 3,
    'RUBBER, HOSES, MISC PLAS': 4,
    'PUMPS': 5,
    'METAL COMPONENTS': 6,
    'ELME': 7,
    'ELECTRONICS': 8,
    'WIRE & HARNESS': 9,
    'PACKAGE/LITERATURE/INSULATION': 10,
    'STRUCTURE AND AESTHETICS': 11,
    'INJECTION MOLDING': 12,
    'GLASS': 13,
    'HEAT': 14,
    'MOTORS': 15
}

# UTILIDADES

def get_db_connections():
    """Retorna conexiones a las bases de datos fuente y destino."""
    source_conn = mysql.connector.connect(**db_config_source)
    target_conn = mysql.connector.connect(**db_config_target)
    return source_conn, target_conn

def generate_uuid():
    """Genera un UUID v4 como string."""
    return str(uuid.uuid4())

def safe_truncate(value, max_length):
    """Trunca un string a max_length si es necesario."""
    if value is None:
        return None
    s = str(value)
    return s[:max_length] if len(s) > max_length else s

def safe_int(value, default=None):
    """Convierte a int de forma segura."""
    if value is None:
        return default
    try:
        return int(value)
    except (ValueError, TypeError):
        return default

def log_migrated(table_name, count):
    """Imprime mensaje de migración exitosa."""
    print(f"  [OK] {table_name}: {count} registros migrados")

def log_skipped(table_name, reason="Sin registros"):
    """Imprime mensaje de tabla omitida."""
    print(f"  [SKIP] {table_name}: {reason}")

