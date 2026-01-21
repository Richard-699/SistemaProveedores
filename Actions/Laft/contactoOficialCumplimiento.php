<?php
session_start();
include "../../ConexionBD/conexion.php";

$Id_proveedor_laft = $_SESSION['id_proveedor_usuarios'];
$consultarlaft = mysqli_query($conexion, "SELECT * FROM laft WHERE Id_proveedor_laft = '$Id_proveedor_laft'");
$row = mysqli_fetch_assoc($consultarlaft);
$Id_laft = $row['Id_laft'];

$consultarContactoOficialCumplimiento = mysqli_query($conexion, "SELECT * FROM laft_contacto WHERE Id_laft_contacto = '$Id_laft' AND Id_tipos_contacto_laft_contacto = 3");

if (isset($_POST['isconsulta']) && $_POST['isconsulta'] === "true") {

    if (mysqli_num_rows($consultarContactoOficialCumplimiento) > 0) {
        $oficialCumplimiento = mysqli_fetch_assoc($consultarContactoOficialCumplimiento);
        $response = [
            'success' => true,
            'oficialCumplimiento' => $oficialCumplimiento
        ];
    } else {
        $response = [
            'success' => false,
            'message' => 'No record found'
        ];
    }
} else {

    $oficialCumplimiento = $_POST['oficial_cumplimiento'];

    if ($oficialCumplimiento == 1) {
        $nombres_contacto = $_POST['nombres_contacto'];
        $apellidos_contacto = $_POST['apellidos_contacto'];
        $cargo_contacto = $_POST['cargo_contacto'];
        $correo_electronico_contacto = $_POST['correo_electronico_contacto'];
        $numero_contacto = $_POST['numero_contacto'];
    }
    $Id_tipos_contacto_laft_contacto = 3;

    $actualizarLaft = $conexion->prepare("UPDATE laft
                                                SET oficial_cumplimiento = ?
                                                WHERE Id_laft = ?");
    $actualizarLaft->bind_param(
        "ss",
        $oficialCumplimiento,
        $Id_laft
    );
    $actualizarLaft->execute();

    if (mysqli_num_rows($consultarContactoOficialCumplimiento) > 0) {
        if ($oficialCumplimiento == 1) {
            $actualizarContactoOficialCumplimiento = $conexion->prepare("UPDATE laft_contacto 
                                                  SET nombres_contacto = ?, 
                                                      apellidos_contacto = ?, 
                                                      cargo_contacto = ?, 
                                                      numero_contacto = ?, 
                                                      correo_electronico_contacto = ?, 
                                                      Id_tipos_contacto_laft_contacto = ? 
                                                  WHERE Id_laft_contacto = ? and Id_tipos_contacto_laft_contacto = ?");
            $actualizarContactoOficialCumplimiento->bind_param(
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

            if ($actualizarContactoOficialCumplimiento->execute()) {
                $response = ['success' => true];
            } else {
                $response = ['success' => false, 'message' => 'Update failed'];
            }
        }else{
            $eliminarContactoOficialCumplimiento = $conexion->prepare("DELETE FROM laft_contacto WHERE Id_laft_contacto = ? AND Id_tipos_contacto_laft_contacto = ?");
            $eliminarContactoOficialCumplimiento->bind_param("ii", $Id_laft, $Id_tipos_contacto_laft_contacto);

            if ($eliminarContactoOficialCumplimiento->execute()) {
                $response = ['success' => true];
            } else {
                $response = ['success' => false, 'message' => 'Delete failed'];
            }
        }
    } else {
        if($oficialCumplimiento == 1){
            $insertarContactoOficialCumplimiento = $conexion->prepare("INSERT INTO laft_contacto 
                                                    (nombres_contacto, apellidos_contacto,
                                                    cargo_contacto, numero_contacto, 
                                                    correo_electronico_contacto, Id_tipos_contacto_laft_contacto,
                                                    Id_laft_contacto )
                                                    VALUES (?, ?, ?, ?, ?, ?, ?)");
            $insertarContactoOficialCumplimiento->bind_param(
                "sssisii",
                $nombres_contacto,
                $apellidos_contacto,
                $cargo_contacto,
                $numero_contacto,
                $correo_electronico_contacto,
                $Id_tipos_contacto_laft_contacto,
                $Id_laft
            );

            if ($insertarContactoOficialCumplimiento->execute()) {
                $response = ['success' => true];
            } else {
                $response = ['success' => false, 'message' => 'Insert failed'];
            }
        }else{
            $response = ['success' => true];
        }
    }
}

echo json_encode($response);
