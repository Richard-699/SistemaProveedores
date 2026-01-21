<?php

session_start();
include "../../ConexionBD/conexion.php";

$Id_proveedor_laft = $_SESSION['id_proveedor_usuarios'];
$consultaLaft = $conexion->prepare("SELECT * FROM laft WHERE Id_proveedor_laft = ?");
$consultaLaft->bind_param("s", $Id_proveedor_laft);
$consultaLaft->execute();
$resultado = $consultaLaft->get_result();

if ($resultado && $resultado->num_rows > 0) {
    $row = $resultado->fetch_assoc();
    $Id_laft = $row['Id_laft'];
} else {
    $Id_laft = null;
}

$consultarPEPInfoGeneral = $conexion->prepare("SELECT maneja_o_ha_manejado_recursos_publicos, tiene_o_ha_tenido_cargo_publico, ocupa_o_ha_ocupado_cargo_publico_organizaciones_gubernamentales, ocupa_o_ha_ocupado_cargo_publico_pais_diferente_colombia FROM laft_pep_infogeneral WHERE Id_laft_pep_infogeneral = ?");
$consultarPEPInfoGeneral->bind_param("i", $Id_laft);
$consultarPEPInfoGeneral->execute();
$result = $consultarPEPInfoGeneral->get_result();

$returnPEP = false;

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        if ($row['maneja_o_ha_manejado_recursos_publicos'] == 1 || 
            $row['tiene_o_ha_tenido_cargo_publico'] == 1 || 
            $row['ocupa_o_ha_ocupado_cargo_publico_organizaciones_gubernamentales'] == 1 || 
            $row['ocupa_o_ha_ocupado_cargo_publico_pais_diferente_colombia'] == 1) {
            $returnPEP = true;
            break;
        }
    }
}

$response = [
    'success' => true,
    'returnPEP' => $returnPEP
];

echo json_encode($response);

?>