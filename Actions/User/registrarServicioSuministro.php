<?php

session_start();
include("../../ConexionBD/conexion.php");

$servicio_suministro = $_POST['servicio_suministro'];

$query = "INSERT INTO proveedor_servicios_suministros (servicio_suministro) 
                                             VALUES (?)";
$stmt = $conexion->prepare($query);

$stmt->bind_param(
    "s",
    $servicio_suministro
);

if ($stmt->execute()) {
    $response = ['success' => true];
} else {
    $response = ['success' => false, 'message' => 'Error al registrar servicio o suministro.'];
}

echo json_encode($response);
