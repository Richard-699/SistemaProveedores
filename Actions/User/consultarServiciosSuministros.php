<?php
include('../../ConexionBD/conexion.php');

$query = $conexion->prepare("SELECT * FROM proveedor_servicios_suministros");
$query->execute();
$result = $query->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
?>
