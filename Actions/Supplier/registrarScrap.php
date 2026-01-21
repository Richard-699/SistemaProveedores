<?php

session_start();

include('../../ConexionBD/conexion.php');
require '../../vendor/autoload.php';

$id_partnumber = $_POST['id_partnumber'];
$id_proveedor_usuarios = $_SESSION['id_proveedor_usuarios'];

$consultarCostbreakdown = mysqli_query($conexion, "SELECT * FROM costbreakdown WHERE id_proveedor_costbreakdow = '$id_proveedor_usuarios' and partnumber_costbreakdown = '$id_partnumber'");
$row = mysqli_fetch_assoc($consultarCostbreakdown);
$id_costbreakdown = $row['id_costbreakdown'];

if (isset($_POST['isconsulta']) && $_POST['isconsulta'] === "true") {

    if (mysqli_num_rows($consultarCostbreakdown) > 0) {

        $response = [
            'success' => true,
            'scrap' => $row
        ];
    } else {
        $response = [
            'success' => false,
            'message' => 'No record found'
        ];
    }
} else {

    if (mysqli_num_rows($consultarCostbreakdown) > 0) {

        $moneda_pieza_scrap = $_POST['moneda_pieza_scrap'];
        $moneda_pieza_scrap = str_replace('.','', $moneda_pieza_scrap);
        $moneda_pieza_scrap = str_replace(',','.',$moneda_pieza_scrap);
        $porcentaje_scrap = $_POST['porcentaje_scrap'];
        $porcentaje_scrap = str_replace('.','', $porcentaje_scrap);
        $porcentaje_scrap = str_replace(',','.',$porcentaje_scrap);

        $actualizarScrap = $conexion->prepare("UPDATE costbreakdown SET 
                                                moneda_pieza_scrap = ?,
                                                porcentaje_scrap = ?
                                                WHERE id_costbreakdown = ? AND partnumber_costbreakdown = ?");

        $actualizarScrap->bind_param(
            "ddss",
            $moneda_pieza_scrap,
            $porcentaje_scrap,
            $id_costbreakdown,
            $id_partnumber
        );

        if($actualizarScrap->execute()){
            $response = ['success' => true];
        } else {
            $response = ['success' => false, 'message' => 'Update failed'];
        }
    }
    
}

echo json_encode($response);
$conexion->close();
