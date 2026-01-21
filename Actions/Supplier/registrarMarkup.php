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
            'markup' => $row
        ];
    } else {
        $response = [
            'success' => false,
            'message' => 'No record found'
        ];
    }
} else {

    if (mysqli_num_rows($consultarCostbreakdown) > 0) {

        $porcentaje_flete = $_POST['porcentaje_flete'];
        $porcentaje_flete = str_replace('.', '', $porcentaje_flete);
        $porcentaje_flete = str_replace(',', '.', $porcentaje_flete);

        $moneda_pieza_flete = $_POST['moneda_pieza_flete'];
        /* $moneda_pieza_flete = str_replace('.', '', $moneda_pieza_flete);
        $moneda_pieza_flete = str_replace(',', '.', $moneda_pieza_flete); */

        $porcentaje_SGA = $_POST['porcentaje_SGA'];
        $porcentaje_SGA = str_replace('.', '', $porcentaje_SGA);
        $porcentaje_SGA = str_replace(',', '.', $porcentaje_SGA);

        $moneda_pieza_SGA = $_POST['moneda_pieza_SGA'];
        /* $moneda_pieza_SGA = str_replace('.', '', $moneda_pieza_SGA);
        $moneda_pieza_SGA = str_replace(',', '.', $moneda_pieza_SGA); */

        $porcentaje_margen_beneficio = $_POST['porcentaje_margen_beneficio'];
        $porcentaje_margen_beneficio = str_replace('.', '', $porcentaje_margen_beneficio);
        $porcentaje_margen_beneficio = str_replace(',', '.', $porcentaje_margen_beneficio);

        $moneda_pieza_margen_beneficio = $_POST['moneda_pieza_margen_beneficio'];
        /* $moneda_pieza_margen_beneficio = str_replace('.', '', $moneda_pieza_margen_beneficio);
        $moneda_pieza_margen_beneficio = str_replace(',', '.', $moneda_pieza_margen_beneficio); */

        $porcentaje_total_embalaje = $_POST['porcentaje_total_embalaje'];
        $porcentaje_total_embalaje = str_replace('.', '', $porcentaje_total_embalaje);
        $porcentaje_total_embalaje = str_replace(',', '.', $porcentaje_total_embalaje);

        $porcentaje_total_scrap = $_POST['porcentaje_total_scrap'];
        $porcentaje_total_scrap = str_replace('.', '', $porcentaje_total_scrap);
        $porcentaje_total_scrap = str_replace(',', '.', $porcentaje_total_scrap);

        $precio_neto_total = $_POST['precio_neto_total'];
        $precio_neto_total = str_replace('.', '', $precio_neto_total);
        $precio_neto_total = str_replace(',', '.', $precio_neto_total);

        $actualizarCbd = $conexion->prepare("UPDATE costbreakdown SET 
                                                porcentaje_flete = ?,
                                                moneda_pieza_flete = ?,
                                                porcentaje_SGA = ?,
                                                moneda_pieza_SGA = ?,
                                                porcentaje_margen_beneficio = ?,
                                                moneda_pieza_margen_beneficio = ?,
                                                porcentaje_total_embalaje = ?,
                                                porcentaje_total_scrap = ?,
                                                precio_neto_total = ?
                                                WHERE id_costbreakdown = ? AND partnumber_costbreakdown = ?");

        $actualizarCbd->bind_param(
            "dddddddddss",
            $porcentaje_flete,
            $moneda_pieza_flete,
            $porcentaje_SGA,
            $moneda_pieza_SGA,
            $porcentaje_margen_beneficio,
            $moneda_pieza_margen_beneficio,
            $porcentaje_total_embalaje,
            $porcentaje_total_scrap,
            $precio_neto_total,
            $id_costbreakdown,
            $id_partnumber
        );

        if ($actualizarCbd->execute()) {
            $response = ['success' => true];
        } else {
            $response = ['success' => false, 'message' => 'Update failed'];
        }

        $porcentaje_final_materia_prima = $_POST['porcentaje_final_materia_prima'];
        $porcentaje_final_materia_prima = str_replace(' %', '', $porcentaje_final_materia_prima);
        $porcentaje_final_materia_prima = str_replace('.','', $porcentaje_final_materia_prima);
        $porcentaje_final_materia_prima = str_replace(',','.', $porcentaje_final_materia_prima);

        $actualizarMateriaPrima = $conexion->prepare("UPDATE costbreakdown_materia_prima SET 
                                                porcentaje_final_materia_prima = ?
                                                WHERE id_costbreakdown_materia_prima = ?");

        $actualizarMateriaPrima->bind_param(
            "ds",
            $porcentaje_final_materia_prima,
            $id_costbreakdown
        );

        if ($actualizarMateriaPrima->execute()) {
            $response = ['success' => true];
        } else {
            $response = ['success' => false, 'message' => 'Update failed'];
        }
    }
}

echo json_encode($response);
$conexion->close();
