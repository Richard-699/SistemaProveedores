<?php
include('../../ConexionBD/conexion.php');

if (isset($_POST['id_servicio_suministro'])) {
    $id_servicio_suministro = $_POST['id_servicio_suministro'];

    $query = "DELETE FROM proveedor_servicios_suministros WHERE id_servicio_suministro = ?";
    $stmt = mysqli_prepare($conexion, $query);
    mysqli_stmt_bind_param($stmt, "i", $id_servicio_suministro);
    mysqli_stmt_execute($stmt);
    $response = array("success" => $success);
    echo json_encode($response);
}
?>
