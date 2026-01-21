<?php
session_start();
include "../../ConexionBD/conexion.php";

$Id_proveedor_laft = $_SESSION['id_proveedor_usuarios'];
$consultarlaft = mysqli_query($conexion, "SELECT * FROM laft WHERE Id_proveedor_laft = '$Id_proveedor_laft'");
$row = mysqli_fetch_assoc($consultarlaft);
$Id_laft = $row['Id_laft'];

$consultarPePInfoGeneral = mysqli_query($conexion, "SELECT * FROM laft_pep_infogeneral WHERE Id_laft_pep_infogeneral  = '$Id_laft'");

if (isset($_POST['isconsulta']) && $_POST['isconsulta'] === "true") {

    if (mysqli_num_rows($consultarPePInfoGeneral) > 0) {
        $pepInfoGeneral = mysqli_fetch_assoc($consultarPePInfoGeneral);
        $response = [
            'success' => true,
            'pepInfoGeneral' => $pepInfoGeneral
        ];
    } else {
        $response = [
            'success' => false,
            'message' => 'No record found'
        ];
    }
} else {

    $maneja_o_ha_manejado_recursos_publicos = $_POST['maneja_o_ha_manejado_recursos_publicos'];
    $tiene_o_ha_tenido_cargo_publico = $_POST['tiene_o_ha_tenido_cargo_publico'];
    $ocupa_o_ha_ocupado_cargo_publico_organizaciones_gubernamentales = $_POST['ocupa_o_ha_ocupado_cargo_publico_organizaciones_gubernamentales'];
    $ocupa_o_ha_ocupado_cargo_publico_pais_diferente_colombia = $_POST['ocupa_o_ha_ocupado_cargo_publico_pais_diferente_colombia'];

    if (mysqli_num_rows($consultarPePInfoGeneral) > 0) {

        $actualizarPepInfoGeneral = $conexion->prepare("UPDATE laft_pep_infogeneral SET 
    maneja_o_ha_manejado_recursos_publicos = ?, 
    tiene_o_ha_tenido_cargo_publico = ?, 
    ocupa_o_ha_ocupado_cargo_publico_organizaciones_gubernamentales = ?, 
    ocupa_o_ha_ocupado_cargo_publico_pais_diferente_colombia = ? 
    WHERE Id_laft_pep_infogeneral = ?");

        $actualizarPepInfoGeneral->bind_param(
            "ssssi",
            $maneja_o_ha_manejado_recursos_publicos,
            $tiene_o_ha_tenido_cargo_publico,
            $ocupa_o_ha_ocupado_cargo_publico_organizaciones_gubernamentales,
            $ocupa_o_ha_ocupado_cargo_publico_pais_diferente_colombia,
            $Id_laft
        );

        if ($actualizarPepInfoGeneral->execute()) {
            $response = ['success' => true];
        } else {
            $response = ['success' => false, 'message' => 'Update failed'];
        }
    } else {
        $insertarPepInfoGeneral = $conexion->prepare("INSERT INTO laft_pep_infogeneral 
    (maneja_o_ha_manejado_recursos_publicos, tiene_o_ha_tenido_cargo_publico,
        ocupa_o_ha_ocupado_cargo_publico_organizaciones_gubernamentales,
        ocupa_o_ha_ocupado_cargo_publico_pais_diferente_colombia, Id_laft_pep_infogeneral)
    VALUES (?, ?, ?, ?, ?)");

        $insertarPepInfoGeneral->bind_param(
            "ssssi",
            $maneja_o_ha_manejado_recursos_publicos,
            $tiene_o_ha_tenido_cargo_publico,
            $ocupa_o_ha_ocupado_cargo_publico_organizaciones_gubernamentales,
            $ocupa_o_ha_ocupado_cargo_publico_pais_diferente_colombia,
            $Id_laft
        );

        if ($insertarPepInfoGeneral->execute()) {
            $response = ['success' => true];
        } else {
            $response = ['success' => false, 'message' => 'Insert failed'];
        }
    }
}

echo json_encode($response);

?>