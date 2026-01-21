<?php
include('../../ConexionBD/conexion.php');

if (isset($_POST['partnumber'])) {
    $partnumber = $_POST['partnumber'];

    $query = "DELETE FROM proveedor_partnumbers WHERE partnumber = ?";
    $stmt = mysqli_prepare($conexion, $query);
    mysqli_stmt_bind_param($stmt, "s", $partnumber);
    mysqli_stmt_execute($stmt);
    $response = array("success" => $success);
    echo json_encode($response);
}
?>
