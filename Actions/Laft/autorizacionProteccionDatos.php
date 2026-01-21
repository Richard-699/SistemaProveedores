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
        $autorizacionProteccionDatos = $resultado->fetch_assoc();
        $response = [
            'success' => true,
            'autorizacionProteccionDatos' => $autorizacionProteccionDatos
        ];
    } else {
        $response = [
            'success' => false,
            'message' => 'No record found'
        ];
    }
} else {

    $autorizacion_proteccion_datos = $_POST['autorizacion_proteccion_datos'];

    if (mysqli_num_rows($resultado) > 0) {

        $actualizarAutorizacionProteccionDatos = $conexion->prepare("UPDATE laft SET 
        autorizacion_proteccion_datos = ?
        WHERE Id_laft = ?");

        $actualizarAutorizacionProteccionDatos->bind_param(
            "si",
            $autorizacion_proteccion_datos,
            $Id_laft
        );

        if ($actualizarAutorizacionProteccionDatos->execute()) {
            $response = ['success' => true];
        } else {
            $response = ['success' => false, 'message' => 'Update failed'];
        }
    }
}

echo json_encode($response);

?>