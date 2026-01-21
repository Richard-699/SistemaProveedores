<?php
session_start();
include "../../ConexionBD/conexion.php";

$Id_proveedor_laft = $_SESSION['id_proveedor_usuarios'];
$consultarlaft = mysqli_query($conexion, "SELECT * FROM laft WHERE Id_proveedor_laft = '$Id_proveedor_laft'");
$row = mysqli_fetch_assoc($consultarlaft);
$Id_laft = $row['Id_laft'];

$consultarSuplenteRepresentanteLegal = mysqli_query($conexion, "SELECT * FROM laft_representante_legal WHERE Id_laft_representante_legal = '$Id_laft' AND tipo_representante_legal = 2");

if (isset($_POST['isconsulta']) && $_POST['isconsulta'] === "true") {

    if (mysqli_num_rows($consultarSuplenteRepresentanteLegal) > 0) {
        $suplenteRepresentanteLegal = mysqli_fetch_assoc($consultarSuplenteRepresentanteLegal);
        $response = [
            'success' => true,
            'suplenteRepresentanteLegal' => $suplenteRepresentanteLegal
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
    $tipo_representante_legal = 2;

    if (mysqli_num_rows($consultarSuplenteRepresentanteLegal) > 0) {
        $actualizarSuplenteRepresentanteLegal = $conexion->prepare("UPDATE laft_representante_legal
                                                    SET nombres_representante_legal = ?, 
                                                        apellidos_representante_legal = ?, 
                                                        tipo_documento_representante_legal = ?, 
                                                        numero_identificacion_representante_legal = ?, 
                                                        correo_electronico_representante_legal = ?, 
                                                        numero_contacto_representante_legal = ?, 
                                                        tipo_representante_legal = ? 
                                                    WHERE Id_laft_representante_legal  = ?  AND tipo_representante_legal = ?");
        $actualizarSuplenteRepresentanteLegal->bind_param(
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

        if ($actualizarSuplenteRepresentanteLegal->execute()) {
            $response = ['success' => true];
        } else {
            $response = ['success' => false, 'message' => 'Update failed'];
        }
    } else {
        $insertarSuplenteRepresentanteLegal = $conexion->prepare("INSERT INTO laft_representante_legal 
                                                (nombres_representante_legal, apellidos_representante_legal,
                                                tipo_documento_representante_legal, numero_identificacion_representante_legal, 
                                                correo_electronico_representante_legal, numero_contacto_representante_legal,
                                                tipo_representante_legal, Id_laft_representante_legal)
                                                VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $insertarSuplenteRepresentanteLegal->bind_param(
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

        if ($insertarSuplenteRepresentanteLegal->execute()) {
            $response = ['success' => true];
        } else {
            $response = ['success' => false, 'message' => 'Insert failed'];
        }
    }
}

echo json_encode($response);
