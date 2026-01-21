<?php

session_start();

include('../../ConexionBD/conexion.php');
require '../../vendor/autoload.php';

$id_partnumber = $_POST['id_partnumber'];
$id_proveedor_usuarios = $_SESSION['id_proveedor_usuarios'];
$consultarCostbreakdown = mysqli_query($conexion, "SELECT * FROM costbreakdown WHERE id_proveedor_costbreakdow = '$id_proveedor_usuarios' and partnumber_costbreakdown = '$id_partnumber'");

$row = mysqli_fetch_assoc($consultarCostbreakdown);
$id_costbreakdown = $row['id_costbreakdown'];

$consultarProcesoProductivo = mysqli_query($conexion, "SELECT * FROM costbreakdown_proceso_productivo WHERE id_costbreakdown_proceso_productivo = '$id_costbreakdown'");

if (isset($_POST['isconsulta']) && $_POST['isconsulta'] === "true") {

    if (mysqli_num_rows($consultarProcesoProductivo) > 0) {

        $procesoProductivo = [];

        while ($row = mysqli_fetch_assoc($consultarProcesoProductivo)) {
            $procesoProductivo[] = $row;
        }

        $response = [
            'success' => true,
            'procesoProductivo' => $procesoProductivo
        ];
    } else {
        $response = [
            'success' => false,
            'message' => 'No record found'
        ];
    }
} else {

    $nombre_maquina_proceso_productivo = $_POST['nombre_maquina_proceso_productivo'];
    $etapa_proceso_productivo = $_POST['etapa_proceso_productivo'];
    $cantidad_cavidades_proceso_productivo = $_POST['cantidad_cavidades_proceso_productivo'];
    $tiempo_ciclo_proceso_productivo = $_POST['tiempo_ciclo_proceso_productivo'];
    $eficiencia_proceso_productivo = $_POST['eficiencia_proceso_productivo'];
    $costo_maquina_hora_proceso_productivo = $_POST['costo_maquina_hora_proceso_productivo'];
    $cantidad_mano_obra_directa_proceso_productivo = $_POST['cantidad_mano_obra_directa_proceso_productivo'];
    $mano_obra_directa_proceso_productivo = $_POST['mano_obra_directa_proceso_productivo'];
    $tiempo_setup_proceso_productivo = $_POST['tiempo_setup_proceso_productivo'];
    $costo_setup_hora_proceso_productivo = $_POST['costo_setup_hora_proceso_productivo'];
    $lote_setup_proceso_productivo = $_POST['lote_setup_proceso_productivo'];
    $costo_final_maquina_proceso_productivo = $_POST['costo_final_maquina_proceso_productivo'];
    $mano_obra_directa_final_proceso_productivo = $_POST['mano_obra_directa_final_proceso_productivo'];
    $costo_final_setup_hora_proceso_productivo = $_POST['costo_final_setup_hora_proceso_productivo'];
    $maquina_mano_obra_directa_setup_proceso_productivo = $_POST['maquina_mano_obra_directa_setup_proceso_productivo'];
    /* $porcentaje_total_proceso_productivo = $_POST['porcentaje_total_proceso_productivo']; */
    $total_moneda_pieza_costo_maquina = $_POST['total_moneda_pieza_costo_maquina'];
    $total_moneda_pieza_costo_maquina = str_replace('.', '', $total_moneda_pieza_costo_maquina);
    $total_moneda_pieza_costo_maquina = str_replace(',', '.', $total_moneda_pieza_costo_maquina);
    /* $porcentaje_final_moneda_pieza_costo_maquina = $_POST['porcentaje_final_moneda_pieza_costo_maquina']; */
    $total_moneda_pieza_mano_obra_directa = $_POST['total_moneda_pieza_mano_obra_directa'];
    $total_moneda_pieza_mano_obra_directa = str_replace('.', '', $total_moneda_pieza_mano_obra_directa);
    $total_moneda_pieza_mano_obra_directa = str_replace(',', '.', $total_moneda_pieza_mano_obra_directa);
    /* $porcentaje_final_moneda_pieza_mano_obra_directa = $_POST['porcentaje_final_moneda_pieza_mano_obra_directa']; */
    $total_moneda_pieza_costo_setup = $_POST['total_moneda_pieza_costo_setup'];
    $total_moneda_pieza_costo_setup = str_replace('.', '', $total_moneda_pieza_costo_setup);
    $total_moneda_pieza_costo_setup = str_replace(',', '.', $total_moneda_pieza_costo_setup);
    /* $porcentaje_final_moneda_pieza_costo_setup = $_POST['porcentaje_final_moneda_pieza_costo_setup']; */

    if (mysqli_num_rows($consultarProcesoProductivo) > 0) {

        $deleteProcesoProductivo = "DELETE FROM costbreakdown_proceso_productivo WHERE id_costbreakdown_proceso_productivo = ?";

        if ($stmt = $conexion->prepare($deleteProcesoProductivo)) {
            $stmt->bind_param("s", $id_costbreakdown);
            $stmt->execute();
        }
    }

    $insertarProcesoProductivo = $conexion->prepare("INSERT INTO costbreakdown_proceso_productivo (etapa_proceso_productivo, 
                                                    nombre_maquina_proceso_productivo, cantidad_cavidades_proceso_productivo,
                                                    tiempo_ciclo_proceso_productivo, eficiencia_proceso_productivo, 
                                                    costo_maquina_hora_proceso_productivo, cantidad_mano_obra_directa_proceso_productivo, 
                                                    mano_obra_directa_proceso_productivo, tiempo_setup_proceso_productivo, 
                                                    costo_setup_hora_proceso_productivo, lote_setup_proceso_productivo, 
                                                    costo_final_maquina_proceso_productivo, mano_obra_directa_final_proceso_productivo,
                                                    costo_final_setup_hora_proceso_productivo, 
                                                    maquina_mano_obra_directa_setup_proceso_productivo, /* porcentaje_total_proceso_productivo, */
                                                    total_moneda_pieza_costo_maquina, /* porcentaje_final_moneda_pieza_costo_maquina, */
                                                    total_moneda_pieza_mano_obra_directa, /* porcentaje_final_moneda_pieza_mano_obra_directa, */
                                                    total_moneda_pieza_costo_setup, /* porcentaje_final_moneda_pieza_costo_setup, */
                                                    id_costbreakdown_proceso_productivo)
                                                    VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?/* ,?,?,?,? */)");

    for ($i = 0; $i < count($etapa_proceso_productivo); $i++) {

        $cantidad_cavidades_proceso_productivo[$i] = str_replace('.', '', $cantidad_cavidades_proceso_productivo[$i]);
        $cantidad_cavidades_proceso_productivo[$i] = str_replace(',', '.', $cantidad_cavidades_proceso_productivo[$i]);

        $tiempo_ciclo_proceso_productivo[$i] = str_replace('.', '', $tiempo_ciclo_proceso_productivo[$i]);
        $tiempo_ciclo_proceso_productivo[$i] = str_replace(',', '.', $tiempo_ciclo_proceso_productivo[$i]);

        $eficiencia_proceso_productivo[$i] = str_replace('.', '', $eficiencia_proceso_productivo[$i]);
        $eficiencia_proceso_productivo[$i] = str_replace(',', '.', $eficiencia_proceso_productivo[$i]);

        $costo_maquina_hora_proceso_productivo[$i] = str_replace('.', '', $costo_maquina_hora_proceso_productivo[$i]);
        $costo_maquina_hora_proceso_productivo[$i] = str_replace(',', '.', $costo_maquina_hora_proceso_productivo[$i]);

        $cantidad_mano_obra_directa_proceso_productivo[$i] = str_replace('.', '', $cantidad_mano_obra_directa_proceso_productivo[$i]);
        $cantidad_mano_obra_directa_proceso_productivo[$i] = str_replace(',', '.', $cantidad_mano_obra_directa_proceso_productivo[$i]);

        $mano_obra_directa_proceso_productivo[$i] = str_replace('.', '', $mano_obra_directa_proceso_productivo[$i]);
        $mano_obra_directa_proceso_productivo[$i] = str_replace(',', '.', $mano_obra_directa_proceso_productivo[$i]);

        $tiempo_setup_proceso_productivo[$i] = str_replace('.', '', $tiempo_setup_proceso_productivo[$i]);
        $tiempo_setup_proceso_productivo[$i] = str_replace(',', '.', $tiempo_setup_proceso_productivo[$i]);

        $costo_setup_hora_proceso_productivo[$i] = str_replace('.', '', $costo_setup_hora_proceso_productivo[$i]);
        $costo_setup_hora_proceso_productivo[$i] = str_replace(',', '.', $costo_setup_hora_proceso_productivo[$i]);

        $lote_setup_proceso_productivo[$i] = str_replace('.', '', $lote_setup_proceso_productivo[$i]);
        $lote_setup_proceso_productivo[$i] = str_replace(',', '.', $lote_setup_proceso_productivo[$i]);

        $costo_final_maquina_proceso_productivo[$i] = str_replace('.', '', $costo_final_maquina_proceso_productivo[$i]);
        $costo_final_maquina_proceso_productivo[$i] = str_replace(',', '.', $costo_final_maquina_proceso_productivo[$i]);

        $mano_obra_directa_final_proceso_productivo[$i] = str_replace('.', '', $mano_obra_directa_final_proceso_productivo[$i]);
        $mano_obra_directa_final_proceso_productivo[$i] = str_replace(',', '.', $mano_obra_directa_final_proceso_productivo[$i]);

        $costo_final_setup_hora_proceso_productivo[$i] = str_replace('.', '', $costo_final_setup_hora_proceso_productivo[$i]);
        $costo_final_setup_hora_proceso_productivo[$i] = str_replace(',', '.', $costo_final_setup_hora_proceso_productivo[$i]);

        $maquina_mano_obra_directa_setup_proceso_productivo[$i] = str_replace('.', '', $maquina_mano_obra_directa_setup_proceso_productivo[$i]);
        $maquina_mano_obra_directa_setup_proceso_productivo[$i] = str_replace(',', '.', $maquina_mano_obra_directa_setup_proceso_productivo[$i]);

        /* $porcentaje_total_proceso_productivo[$i] = str_replace('.', '', $porcentaje_total_proceso_productivo[$i]);
    $porcentaje_total_proceso_productivo[$i] = str_replace(',', '.', $porcentaje_total_proceso_productivo[$i]); */

        /* $porcentaje_final_moneda_pieza_costo_maquina[$i] = str_replace('.', '', $porcentaje_final_moneda_pieza_costo_maquina[$i]);
    $porcentaje_final_moneda_pieza_costo_maquina[$i] = str_replace(',', '.', $porcentaje_final_moneda_pieza_costo_maquina[$i]); */

        /* $porcentaje_final_moneda_pieza_mano_obra_directa[$i] = str_replace('.', '', $porcentaje_final_moneda_pieza_mano_obra_directa[$i]);
    $porcentaje_final_moneda_pieza_mano_obra_directa[$i] = str_replace(',', '.', $porcentaje_final_moneda_pieza_mano_obra_directa[$i]); */

        /* $porcentaje_final_moneda_pieza_costo_setup[$i] = str_replace('.', '', $porcentaje_final_moneda_pieza_costo_setup[$i]);
    $porcentaje_final_moneda_pieza_costo_setup[$i] = str_replace(',', '.', $porcentaje_final_moneda_pieza_costo_setup[$i]); */

        $insertarProcesoProductivo->bind_param(
            "ssdddddddddddddddds",
            $etapa_proceso_productivo[$i],
            $nombre_maquina_proceso_productivo[$i],
            $cantidad_cavidades_proceso_productivo[$i],
            $tiempo_ciclo_proceso_productivo[$i],
            $eficiencia_proceso_productivo[$i],
            $costo_maquina_hora_proceso_productivo[$i],
            $cantidad_mano_obra_directa_proceso_productivo[$i],
            $mano_obra_directa_proceso_productivo[$i],
            $tiempo_setup_proceso_productivo[$i],
            $costo_setup_hora_proceso_productivo[$i],
            $lote_setup_proceso_productivo[$i],
            $costo_final_maquina_proceso_productivo[$i],
            $mano_obra_directa_final_proceso_productivo[$i],
            $costo_final_setup_hora_proceso_productivo[$i],
            $maquina_mano_obra_directa_setup_proceso_productivo[$i],
            /* $porcentaje_total_proceso_productivo[$i], */
            $total_moneda_pieza_costo_maquina,
            /* $porcentaje_final_moneda_pieza_costo_maquina, */
            $total_moneda_pieza_mano_obra_directa,
            /* $porcentaje_final_moneda_pieza_mano_obra_directa, */
            $total_moneda_pieza_costo_setup,
            /* $porcentaje_final_moneda_pieza_costo_setup, */
            $id_costbreakdown
        );
        $insertarProcesoProductivo->execute();

        if ($insertarProcesoProductivo->affected_rows > 0) {
            $response = ['success' => true];
        } else {
            $response = ['success' => false, 'message' => 'Insert failed'];
        }
    }
}
echo json_encode($response);
$conexion->close();
