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
        $declaracionEtica = $resultado->fetch_assoc();
        $response = [
            'success' => true,
            'declaracionEtica' => $declaracionEtica
        ];
    } else {
        $response = [
            'success' => false,
            'message' => 'No record found'
        ];
    }
} else {

    $declaracion_etica = $_POST['declaracion_etica'];

    if (mysqli_num_rows($resultado) > 0) {

        $actualizarDeclaracion_etica = $conexion->prepare("UPDATE laft SET 
        declaracion_etica = ?
        WHERE Id_laft = ?");

        $actualizarDeclaracion_etica->bind_param(
            "si",
            $declaracion_etica,
            $Id_laft
        );
        $actualizarDeclaracion_etica->execute();
        $response = [
            'success' => true
        ];
    }
}

echo json_encode($response);

?>