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
            'embalaje' => $row
        ];
    } else {
        $response = [
            'success' => false,
            'message' => 'No record found'
        ];
    }
} else {

    if (mysqli_num_rows($consultarCostbreakdown) > 0) {

        if (isset($_POST['moneda_pieza_embalaje'])){
            $moneda_pieza_embalaje = $_POST['moneda_pieza_embalaje'];
            $moneda_pieza_embalaje = str_replace('.', '', $moneda_pieza_embalaje);
            $moneda_pieza_embalaje = str_replace(',', '.', $moneda_pieza_embalaje);

            $actualizarEmbalaje = $conexion->prepare("UPDATE costbreakdown SET 
                                                    moneda_pieza_embalaje = ?
                                                    WHERE id_costbreakdown = ? AND partnumber_costbreakdown = ?");

            $actualizarEmbalaje->bind_param(
                "dss",
                $moneda_pieza_embalaje,
                $id_costbreakdown,
                $id_partnumber
            );

            if ($actualizarEmbalaje->execute()) {
                $response = ['success' => true];
            } else {
                $response = ['success' => false, 'message' => 'Execute failed: ' . $actualizarEmbalaje->error];
            }
        }
    }
}

echo json_encode($response);
$conexion->close();
