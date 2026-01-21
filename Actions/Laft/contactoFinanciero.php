<?php
session_start();
include "../../ConexionBD/conexion.php";

$Id_proveedor_laft = $_SESSION['id_proveedor_usuarios'];
$consultarlaft = mysqli_query($conexion, "SELECT * FROM laft WHERE Id_proveedor_laft = '$Id_proveedor_laft'");
$row = mysqli_fetch_assoc($consultarlaft);
$Id_laft = $row['Id_laft'];

$consultarContactoFinanciero = mysqli_query($conexion, "SELECT * FROM laft_contacto WHERE Id_laft_contacto = '$Id_laft' AND Id_tipos_contacto_laft_contacto = 2");

if (isset($_POST['isconsulta']) && $_POST['isconsulta'] === "true") {

    if (mysqli_num_rows($consultarContactoFinanciero) > 0) {
        $contactoFinanciero = mysqli_fetch_assoc($consultarContactoFinanciero);
        $response = [
            'success' => true,
            'contactoFinanciero' => $contactoFinanciero
        ];
    } else {
        $response = [
            'success' => false,
            'message' => 'No record found'
        ];
    }
} else {

    $nombres_contacto = $_POST['nombres_contacto'];
    $apellidos_contacto = $_POST['apellidos_contacto'];
    $cargo_contacto = $_POST['cargo_contacto'];
    $correo_electronico_contacto = $_POST['correo_electronico_contacto'];
    $numero_contacto = $_POST['numero_contacto'];
    $Id_tipos_contacto_laft_contacto = 2;

    if (mysqli_num_rows($consultarContactoFinanciero) > 0) {
        $actualizarContactoFinanciero = $conexion->prepare("UPDATE laft_contacto 
                                                  SET nombres_contacto = ?, 
                                                      apellidos_contacto = ?, 
                                                      cargo_contacto = ?, 
                                                      numero_contacto = ?, 
                                                      correo_electronico_contacto = ?, 
                                                      Id_tipos_contacto_laft_contacto = ? 
                                                  WHERE Id_laft_contacto = ? and Id_tipos_contacto_laft_contacto = ?");
        $actualizarContactoFinanciero->bind_param(
            "sssisiii",
            $nombres_contacto,
            $apellidos_contacto,
            $cargo_contacto,
            $numero_contacto,
            $correo_electronico_contacto,
            $Id_tipos_contacto_laft_contacto,
            $Id_laft,
            $Id_tipos_contacto_laft_contacto
        );

        if ($actualizarContactoFinanciero->execute()) {
            $response = ['success' => true];
        } else {
            $response = ['success' => false, 'message' => 'Update failed'];
        }
    } else {
        $insertarContactoFinanciero = $conexion->prepare("INSERT INTO laft_contacto 
                                                (nombres_contacto, apellidos_contacto,
                                                cargo_contacto, numero_contacto, 
                                                correo_electronico_contacto, Id_tipos_contacto_laft_contacto,
                                                Id_laft_contacto )
                                                VALUES (?, ?, ?, ?, ?, ?, ?)");
        $insertarContactoFinanciero->bind_param(
            "sssisii",
            $nombres_contacto,
            $apellidos_contacto,
            $cargo_contacto,
            $numero_contacto,
            $correo_electronico_contacto,
            $Id_tipos_contacto_laft_contacto,
            $Id_laft
        );

        if ($insertarContactoFinanciero->execute()) {
            $response = ['success' => true];
        } else {
            $response = ['success' => false, 'message' => 'Insert failed'];
        }
    }
}

echo json_encode($response);