<?php

session_start();
include "../../ConexionBD/conexion.php";

$tipoPersona = $_POST['tipoPersona'];

$Id_proveedor_laft = $_SESSION['id_proveedor_usuarios'];
$consultarlaft = mysqli_query($conexion, "SELECT * FROM laft WHERE Id_proveedor_laft = '$Id_proveedor_laft'");

if ($tipoPersona == null) {
    if (mysqli_num_rows($consultarlaft) > 0) {
        $row = mysqli_fetch_assoc($consultarlaft);
        $response = [
            'success' => true,
            'tipo_persona_laft' => $row['tipo_persona_laft']
        ];
    } else {
        $response = [
            'success' => false,
            'message' => 'No record found'
        ];
    }
} else {
    date_default_timezone_set('America/Bogota');
    $fecha_solicitud_laft = date('Y-m-d H:i:s');
    $ultima_actualizacion_laft = date('Y-m-d');
    $proceso_laft = "Vinculación";

    $consultaLaft_historico = $conexion->prepare("
    SELECT * FROM laft_historico 
    WHERE Id_proveedor_laft_historico = ? 
    ORDER BY fecha_actualizacion_historico DESC 
    LIMIT 1
");
    $consultaLaft_historico->bind_param("s", $Id_proveedor_laft);
    $consultaLaft_historico->execute();
    $resultado = $consultaLaft_historico->get_result();

    if ($resultado && $resultado->num_rows > 0) {
        $row = $resultado->fetch_assoc();
        $fecha_actualizacion_historico = $row['fecha_actualizacion_historico'];
    } else {
        $fecha_actualizacion_historico = null;
    }

    $ultima_actualizacion_laft_formateada = date('Y-m-d', strtotime($ultima_actualizacion_laft));
    $fecha_actualizacion_historico_formateada = $fecha_actualizacion_historico ? date('Y-m-d', strtotime($fecha_actualizacion_historico)) : null;

    if ($ultima_actualizacion_laft_formateada !== $fecha_actualizacion_historico_formateada) {
        $insertarHistoricoLaft = $conexion->prepare("INSERT INTO laft_historico (fecha_actualizacion_historico, Id_proveedor_laft_historico)
                                                VALUES (?, ?)");
        $insertarHistoricoLaft->bind_param("ss", $ultima_actualizacion_laft, $Id_proveedor_laft);

        if ($insertarHistoricoLaft->execute()) {
            $response = ['success' => true];
        } else {
            $response = ['success' => false, 'message' => 'Insert failed'];
        }
    } else {
        $response = ['success' => false, 'message' => 'Dates are the same, no insert'];
    }


    if (mysqli_num_rows($consultarlaft) > 0) {
        //Update
        $actualizarlaft = $conexion->prepare("UPDATE laft SET ultima_actualizacion_laft = ?, tipo_persona_laft = ? WHERE Id_proveedor_laft = ?");
        $actualizarlaft->bind_param("sss", $ultima_actualizacion_laft, $tipoPersona, $Id_proveedor_laft);

        if ($actualizarlaft->execute()) {
            $response = ['success' => true];
        } else {
            $response = ['success' => false, 'message' => 'Update failed'];
        }
    } else {
        //Insert
        $insertarlaft = $conexion->prepare("INSERT INTO laft (fecha_solicitud_laft, ultima_actualizacion_laft, proceso_laft, tipo_persona_laft, Id_proveedor_laft)
                                                    VALUES (?, ?, ?, ?, ?)");
        $insertarlaft->bind_param("sssss", $fecha_solicitud_laft, $ultima_actualizacion_laft, $proceso_laft, $tipoPersona, $Id_proveedor_laft);

        if ($insertarlaft->execute()) {
            $response = ['success' => true];
        } else {
            $response = ['success' => false, 'message' => 'Insert failed'];
        }
    }
}

echo json_encode($response);
