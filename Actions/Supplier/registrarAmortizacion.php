<?php

session_start();

include('../../ConexionBD/conexion.php');
require '../../vendor/autoload.php';

$id_partnumber = $_POST['id_partnumber'];
$id_proveedor_usuarios = $_SESSION['id_proveedor_usuarios'];
$consultarCostbreakdown = mysqli_query($conexion, "SELECT * FROM costbreakdown WHERE id_proveedor_costbreakdow = '$id_proveedor_usuarios' and partnumber_costbreakdown = '$id_partnumber'");

$row = mysqli_fetch_assoc($consultarCostbreakdown);
$id_costbreakdown = $row['id_costbreakdown'];

$consultarAmortizacion = mysqli_query($conexion, "SELECT * FROM costbreakdown_amortizacion WHERE id_costbreakdown_amortizacion = '$id_costbreakdown'");

if (isset($_POST['isconsulta']) && $_POST['isconsulta'] === "true") {

    if (mysqli_num_rows($consultarAmortizacion) > 0) {

        $amortizacion = [];

        while ($row = mysqli_fetch_assoc($consultarAmortizacion)) {
            $amortizacion[] = $row;
        }

        $response = [
            'success' => true,
            'amortizacion' => $amortizacion
        ];
    } else {
        $response = [
            'success' => false,
            'message' => 'No record found'
        ];
    }
} else {

    if (mysqli_num_rows($consultarAmortizacion) > 0) {

        $deleteAmortizacion = "DELETE FROM costbreakdown_amortizacion WHERE id_costbreakdown_amortizacion = ?";

        if ($stmt = $conexion->prepare($deleteAmortizacion)) {
            $stmt->bind_param("s", $id_costbreakdown);
            $stmt->execute();
        }
    }

    $descripcion_amortizacion = $_POST['descripcion_amortizacion'];
    $inversion_amortizacion = $_POST['inversion_amortizacion'];
    $piezas_amortizadas = $_POST['piezas_amortizadas'];
    $moneda_pieza_amortizacion = $_POST['moneda_pieza_amortizacion'];
    /* $porcentaje_total_amortizacion = $_POST['porcentaje_total_amortizacion']; */
    $total_moneda_pieza_amortizacion = $_POST['total_moneda_pieza_amortizacion'];
    $total_moneda_pieza_amortizacion = str_replace('.', '', $total_moneda_pieza_amortizacion);
    $total_moneda_pieza_amortizacion = str_replace(',', '.', $total_moneda_pieza_amortizacion);
    /* $porcentaje_final_moneda_pieza_amortizacion = $_POST['porcentaje_final_moneda_pieza_amortizacion']; */

    //Insertar Amortizacion

    $insertarAmortizacion = $conexion->prepare("INSERT INTO costbreakdown_amortizacion (descripcion_amortizacion, inversion_amortizacion,
                                                piezas_amortizadas, moneda_pieza_amortizacion, /* porcentaje_total_amortizacion, */
                                                total_moneda_pieza_amortizacion, /* porcentaje_final_moneda_pieza_amortizacion, */
                                                id_costbreakdown_amortizacion)
                                                VALUES(?,?,?,?,?,?/* ,?,? */)");

    for ($i = 0; $i < count($descripcion_amortizacion); $i++) {

        $inversion_amortizacion[$i] = str_replace('.','', $inversion_amortizacion[$i]);
        $inversion_amortizacion[$i] = str_replace(',','.', $inversion_amortizacion[$i]);

        $piezas_amortizadas[$i] = str_replace('.','', $piezas_amortizadas[$i]);
        $piezas_amortizadas[$i] = str_replace(',','.', $piezas_amortizadas[$i]);

        $moneda_pieza_amortizacion[$i] = str_replace('.','', $moneda_pieza_amortizacion[$i]);
        $moneda_pieza_amortizacion[$i] = str_replace(',','.', $moneda_pieza_amortizacion[$i]);

        /* $porcentaje_total_amortizacion[$i] = str_replace('.','', $porcentaje_total_amortizacion[$i]);
        $porcentaje_total_amortizacion[$i] = str_replace(',','.', $porcentaje_total_amortizacion[$i]); */

        /* $porcentaje_final_moneda_pieza_amortizacion[$i] = str_replace('.','', $porcentaje_final_moneda_pieza_amortizacion[$i]);
        $porcentaje_final_moneda_pieza_amortizacion[$i] = str_replace(',','.', $porcentaje_final_moneda_pieza_amortizacion[$i]); */

        $insertarAmortizacion->bind_param("sdidds", $descripcion_amortizacion[$i], $inversion_amortizacion[$i], 
                                            $piezas_amortizadas[$i], $moneda_pieza_amortizacion[$i], /* $porcentaje_total_amortizacion[$i], */
                                            $total_moneda_pieza_amortizacion,/*  $porcentaje_final_moneda_pieza_amortizacion, */
                                            $id_costbreakdown);
        $insertarAmortizacion->execute();

        if ($insertarAmortizacion->affected_rows > 0) {
            $response = ['success' => true];
        } else {
            $response = ['success' => false, 'message' => 'Insert failed'];
        }
    }
}

echo json_encode($response);
$conexion->close();
?>