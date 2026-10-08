<?php
// Conexión compatible con entorno local (XAMPP) y producción
$conexion = @new mysqli("localhost", "root", "", "sistema_proveedores_vieja");

if ($conexion->connect_errno) {
    // Si falla el entorno local, conectar con credenciales de producción
    $conexion = new mysqli("localhost", "uv1bbovzu8b9o", "EquipoBI2024*", "dbzzwlkqmwlnws");
}

if ($conexion->connect_errno) {
    die("Error de conexion: " . $conexion->connect_error);
}

$conexion->set_charset("utf8");
?>