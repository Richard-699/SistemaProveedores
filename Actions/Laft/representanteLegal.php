<?php
session_start();
include "../../ConexionBD/conexion.php";

$Id_proveedor_laft = $_SESSION['id_proveedor_usuarios'];
$consultarlaft = mysqli_query($conexion, "SELECT * FROM laft WHERE Id_proveedor_laft = '$Id_proveedor_laft'");
$row = mysqli_fetch_assoc($consultarlaft);
$Id_laft = $row['Id_laft'];

$consultarRepresentanteLegal = mysqli_query($conexion, "SELECT * FROM laft_representante_legal WHERE Id_laft_representante_legal = '$Id_laft' AND tipo_representante_legal = 1");

if (isset($_POST['isconsulta']) && $_POST['isconsulta'] === "true") {

    if (mysqli_num_rows($consultarRepresentanteLegal) > 0) {
        $representanteLegal = mysqli_fetch_assoc($consultarRepresentanteLegal);
        $response = [
            'success' => true,
            'representanteLegal' => $representanteLegal
        ];
    } else {
        $response = [
            'success' => false,
            'message' => 'No record found'
        ];
    }
} else {

    $nombres_representante_legal = $_POST['nombres_representante_legal'];
    $apellidos_representante_legal = $_POST['apellidos_representante_legal'];
    $tipo_documento_representante_legal = $_POST['tipo_documento_representante_legal'];
    $numero_identificacion_representante_legal = $_POST['numero_identificacion_representante_legal'];
    $correo_electronico_representante_legal = $_POST['correo_electronico_representante_legal'];
    $numero_contacto_representante_legal = $_POST['numero_contacto_representante_legal'];
    $tipo_representante_legal = 1;

    if (mysqli_num_rows($consultarRepresentanteLegal) > 0) {
        $actualizarRepresentanteLegal = $conexion->prepare("UPDATE laft_representante_legal
                                                    SET nombres_representante_legal = ?, 
                                                        apellidos_representante_legal = ?, 
                                                        tipo_documento_representante_legal = ?, 
                                                        numero_identificacion_representante_legal = ?, 
                                                        correo_electronico_representante_legal = ?, 
                                                        numero_contacto_representante_legal = ?, 
                                                        tipo_representante_legal = ? 
                                                    WHERE Id_laft_representante_legal  = ? AND tipo_representante_legal = ?");
        $actualizarRepresentanteLegal->bind_param(
            "sssssiiii",
            $nombres_representante_legal,
            $apellidos_representante_legal,
            $tipo_documento_representante_legal,
            $numero_identificacion_representante_legal,
            $correo_electronico_representante_legal,
            $numero_contacto_representante_legal,
            $tipo_representante_legal,
            $Id_laft,
            $tipo_representante_legal
        );

        if ($actualizarRepresentanteLegal->execute()) {
            $response = ['success' => true];
        } else {
            $response = ['success' => false, 'message' => 'Update failed'];
        }
    } else {
        $insertarRepresentanteLegal = $conexion->prepare("INSERT INTO laft_representante_legal 
                                                (nombres_representante_legal, apellidos_representante_legal,
                                                tipo_documento_representante_legal, numero_identificacion_representante_legal, 
                                                correo_electronico_representante_legal, numero_contacto_representante_legal,
                                                tipo_representante_legal, Id_laft_representante_legal )
                                                VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $insertarRepresentanteLegal->bind_param(
            "sssssiii",
            $nombres_representante_legal,
            $apellidos_representante_legal,
            $tipo_documento_representante_legal,
            $numero_identificacion_representante_legal,
            $correo_electronico_representante_legal,
            $numero_contacto_representante_legal,
            $tipo_representante_legal,
            $Id_laft
        );

        if ($insertarRepresentanteLegal->execute()) {
            $response = ['success' => true];
        } else {
            $response = ['success' => false, 'message' => 'Insert failed'];
        }
    }
}

echo json_encode($response);
