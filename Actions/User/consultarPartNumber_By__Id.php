<?php
include('../../ConexionBD/conexion.php');

if (isset($_POST['partnumber'])) {
    $partnumber = $_POST['partnumber'];

    $query = "SELECT * FROM proveedor_partnumbers WHERE partnumber = ?";
    $stmt = mysqli_prepare($conexion, $query);
    mysqli_stmt_bind_param($stmt, "s", $partnumber);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result_partnumber = mysqli_fetch_assoc($result)) {
        include '../../Views/User/_EditarPartNumberPartial.php';
    } else {
        echo "No se encontró el partnumber.";
    }
}
?>
