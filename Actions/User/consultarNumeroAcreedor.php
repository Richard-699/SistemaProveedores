<?php

include('../../ConexionBD/conexion.php');

$id_proveedor = $_POST['id_proveedor'];

$consultaProveedor = $conexion->prepare("SELECT * FROM proveedores WHERE Id_proveedor = ?");
$consultaProveedor->bind_param("s", $id_proveedor);
$consultaProveedor->execute();
$resultado = $consultaProveedor->get_result();

if ($resultado && $resultado->num_rows > 0) {
    $row = $resultado->fetch_assoc();
    $numero_acreedor = $row['numero_acreedor'];
} else {
    $numero_acreedor = null;
}

$response = [
    'success' => true,
    'numero_acreedor' => $numero_acreedor
];

echo json_encode($response);

?>