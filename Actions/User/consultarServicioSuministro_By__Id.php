<?php
include('../../ConexionBD/conexion.php');

if (isset($_POST['id_servicio_suministro'])) {
    $id_servicio_suministro = $_POST['id_servicio_suministro'];

    $query = "SELECT * FROM proveedor_servicios_suministros WHERE id_servicio_suministro = ?";
    $stmt = mysqli_prepare($conexion, $query);
    mysqli_stmt_bind_param($stmt, "s", $id_servicio_suministro);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result_servicioSuministro = mysqli_fetch_assoc($result)) {
        include '../../Views/User/_EditarServicioSuministroPartial.php';
    } else {
        echo "No se encontró el partnumber.";
    }
}
?>
