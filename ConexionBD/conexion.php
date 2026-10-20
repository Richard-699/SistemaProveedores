<?php
// Desactivar el reporte de excepciones estricto de mysqli (PHP 8.1+)
// para que no lance un Fatal Error cuando el usuario local no existe en hosting
mysqli_report(MYSQLI_REPORT_OFF);

// Detectar si estamos en entorno local
$isLocal = (php_sapi_name() === 'cli')
    || in_array($_SERVER['HTTP_HOST'] ?? '', ['localhost', '127.0.0.1', '::1'])
    || (isset($_SERVER['HTTP_HOST']) && str_starts_with($_SERVER['HTTP_HOST'], 'localhost:'));

$conexion = null;

if ($isLocal) {
    // Intentar conexión local (XAMPP): la base de datos principal con la tabla usuarios es proveedores_hwi
    $conexion = @new mysqli("localhost", "root", "", "proveedores_hwi");
    if ($conexion->connect_errno) {
        $conexion = @new mysqli("localhost", "root", "", "sistema_proveedores_v2");
    }
    if ($conexion->connect_errno) {
        $conexion = @new mysqli("localhost", "root", "", "sistema_proveedores_vieja");
    }
}

// Si estamos en hosting o falló el entorno local, conectar con producción
if (!$isLocal || empty($conexion) || $conexion->connect_errno) {
    $conexion = @new mysqli("localhost", "uv1bbovzu8b9o", "EquipoBI2024*", "dbzzwlkqmwlnws");
}

if (!$conexion || $conexion->connect_errno) {
    die("Error de conexion: " . ($conexion ? $conexion->connect_error : "No se pudo establecer conexion con la base de datos"));
}

$conexion->set_charset("utf8");
