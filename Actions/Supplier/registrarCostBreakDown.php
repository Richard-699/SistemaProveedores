<?php
session_start();

include('../../ConexionBD/conexion.php');
require '../../vendor/autoload.php';

//Datos costbreakdown

$zona_horaria = new DateTimeZone('America/Bogota');
$fecha_actual = new DateTime('now', $zona_horaria);
$fecha_costbreakdown = $fecha_actual->format('Y-m-d');

$id_partnumber = $_POST['id_partnumber'];
$id_proveedor_usuarios = $_SESSION['id_proveedor_usuarios'];
$diligencio_costbreakdown = $_POST['diligencio_costbreakdown'];
$moneda_costbreakdown = $_POST['moneda_costbreakdown'];
$incoterm_costbreakdown = $_POST['incoterm_costbreakdown'];
$volumen_anual_costbreakdown = $_POST['volumen_anual_costbreakdown'];
$volumen_anual_costbreakdown = str_replace('.', '', $volumen_anual_costbreakdown);
$volumen_anual_costbreakdown = str_replace(',', '.', $volumen_anual_costbreakdown);

$consultarCostbreakdown = mysqli_query($conexion, "SELECT * FROM costbreakdown WHERE id_proveedor_costbreakdow = '$id_proveedor_usuarios' and partnumber_costbreakdown = '$id_partnumber'");

if (mysqli_num_rows($consultarCostbreakdown) > 0) {

    $row = mysqli_fetch_assoc($consultarCostbreakdown);
    $id_costbreakdown = $row['id_costbreakdown'];

    $actualizarCostbreakdown = $conexion->prepare("UPDATE costbreakdown SET 
                                                id_proveedor_costbreakdow = ?, 
                                                partnumber_costbreakdown = ?, 
                                                diligencio_costbreakdown = ?, 
                                                fecha_costbreakdown = ?, 
                                                moneda_costbreakdown = ?, 
                                                incoterm_costbreakdown = ?, 
                                                volumen_anual_costbreakdown = ?
                                                WHERE id_costbreakdown = ?");

    $actualizarCostbreakdown->bind_param(
        "ssssssds",
        $id_proveedor_usuarios,
        $id_partnumber,
        $diligencio_costbreakdown,
        $fecha_costbreakdown,
        $moneda_costbreakdown,
        $incoterm_costbreakdown,
        $volumen_anual_costbreakdown,
        $id_costbreakdown
    );
<<<<<<< HEAD
=======

    $actualizarCostbreakdown->execute();

>>>>>>> 8fe25a02a378af3db1c5f09c74bddd125a144800
    if ($actualizarCostbreakdown->execute()) {
        $response = ['success' => true];
    } else {
        $response = ['success' => false, 'message' => 'Update failed'];
    }
} else {

    $id_costbreakdown = uniqid();
    $id_costbreakdown = substr(str_replace(".", "", $id_costbreakdown), 0, 25);

    $insertarCostbreakdown = $conexion->prepare("INSERT INTO costbreakdown (id_costbreakdown, 
                                                id_proveedor_costbreakdow, partnumber_costbreakdown, diligencio_costbreakdown, 
                                                fecha_costbreakdown, moneda_costbreakdown, incoterm_costbreakdown, 
                                                volumen_anual_costbreakdown)
                                                VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    $insertarCostbreakdown->bind_param(
        "sssssssd",
        $id_costbreakdown,
        $id_proveedor_usuarios,
        $id_partnumber,
        $diligencio_costbreakdown,
        $fecha_costbreakdown,
        $moneda_costbreakdown,
        $incoterm_costbreakdown,
        $volumen_anual_costbreakdown
    );
    $insertarCostbreakdown->execute();

    if ($insertarCostbreakdown->affected_rows > 0) {
        $response = ['success' => true];
    } else {
        $response = ['success' => false, 'message' => 'Insert failed'];
    }
}

echo json_encode($response);
$conexion->close();
?>