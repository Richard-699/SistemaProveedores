<?php
session_start();
include "../../ConexionBD/conexion.php";

$Id_proveedor_laft = $_SESSION['id_proveedor_usuarios'];
$consultaLaft = $conexion->prepare("SELECT * FROM laft WHERE Id_proveedor_laft = ?");
$consultaLaft->bind_param("s", $Id_proveedor_laft);
$consultaLaft->execute();
$resultado = $consultaLaft->get_result();

if ($resultado && $resultado->num_rows > 0) {
    $row = $resultado->fetch_assoc();
    $Id_laft = $row['Id_laft'];
} else {
    $Id_laft = null;
}

if (isset($_POST['isconsulta']) && $_POST['isconsulta'] === "true") {
    $resultado->data_seek(0);
    
    if ($resultado->num_rows > 0) {
        $origenesFondos = $resultado->fetch_assoc();
        $response = [
            'success' => true,
            'origenesFondos' => $origenesFondos
        ];
    } else {
        $response = [
            'success' => false,
            'message' => 'No record found'
        ];
    }
} else {

    $declaracion_origen_fondos_informacion = $_POST['declaracion_origen_fondos_informacion'];

    if (mysqli_num_rows($resultado) > 0) {

        $actualizarOrigenesFondos = $conexion->prepare("UPDATE laft SET 
        declaracion_origen_fondos_informacion = ?
        WHERE Id_laft = ?");

        $actualizarOrigenesFondos->bind_param(
            "si",
            $declaracion_origen_fondos_informacion,
            $Id_laft
        );

        if ($actualizarOrigenesFondos->execute()) {
            $response = ['success' => true];
        } else {
            $response = ['success' => false, 'message' => 'Update failed'];
        }
    }
}

echo json_encode($response);

?>