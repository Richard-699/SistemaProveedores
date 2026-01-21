<?php
session_start();
include "../../ConexionBD/conexion.php";

$Id_proveedor = $_SESSION['id_proveedor_usuarios'];

$consultarGestionAmbiental = mysqli_query($conexion, "SELECT * FROM gestion_ambiental WHERE Id_proveedor_gestion_ambiental = '$Id_proveedor'");

if (isset($_POST['isconsulta']) && $_POST['isconsulta'] === "true") {

    if (mysqli_num_rows($consultarGestionAmbiental) > 0) {
        $gestionAmbiental = mysqli_fetch_assoc($consultarGestionAmbiental);
        $response = [
            'success' => true,
            'gestionAmbiental' => $gestionAmbiental
        ];
    } else {
        $response = [
            'success' => false,
            'message' => 'No record found'
        ];
    }
} else {

    $cuenta_sistema_gestion_ambiental = $_POST['cuenta_sistema_gestion_ambiental'];
    $certificado_ISO_14001 = $_POST['certificado_ISO_14001'];
    $cuenta_departamento_gestion_politica_ambiental = $_POST['cuenta_departamento_gestion_politica_ambiental'];
    $tiene_identificados_aspectos_impactos = $_POST['tiene_identificados_aspectos_impactos'];
    $principales_requisitos_legales = $_POST['principales_requisitos_legales'];
    $realiza_registro_anual_autoridades = $_POST['realiza_registro_anual_autoridades'];
    $ha_obtenido_sancion = $_POST['ha_obtenido_sancion'];
    $permiso_uso_recursos_naturales = $_POST['permiso_uso_recursos_naturales'];
    $permisos_cuenta = $_POST['permisos_cuenta'];
    $plan_manejo_integral_residuos = $_POST['plan_manejo_integral_residuos'];
    $genera_residuos_posconsumo = $_POST['genera_residuos_posconsumo'];
    $controles_realiza_gestion_residuos_solidos_peligrosos = $_POST['controles_realiza_gestion_residuos_solidos_peligrosos'];
    $genera_vertimiento_aguas_residuales_industriales = $_POST['genera_vertimiento_aguas_residuales_industriales'];
    $controles_realiza_gestion_vertimientos = $_POST['controles_realiza_gestion_vertimientos'];
    $genera_emisiones_atmosfericas = $_POST['genera_emisiones_atmosfericas'];
    $controles_realiza_gestion_emisiones = $_POST['controles_realiza_gestion_emisiones'];
    $plan_contingencia_manejo_transporte = $_POST['plan_contingencia_manejo_transporte'];
    $controles_realiza_gestion_sustancias_quimicas = $_POST['controles_realiza_gestion_sustancias_quimicas'];
    $observaciones_gestion_ambiental = $_POST['observaciones_gestion_ambiental'];

    if (mysqli_num_rows($consultarGestionAmbiental) > 0) {
        $actualizarGestionAmbiental = $conexion->prepare("UPDATE gestion_ambiental SET
            cuenta_sistema_gestion_ambiental = ?, 
            certificado_ISO_14001 = ?, 
            cuenta_departamento_gestion_politica_ambiental = ?, 
            tiene_identificados_aspectos_impactos = ?, 
            principales_requisitos_legales = ?, 
            realiza_registro_anual_autoridades = ?, 
            ha_obtenido_sancion = ?, 
            permiso_uso_recursos_naturales = ?, 
            permisos_cuenta = ?, 
            plan_manejo_integral_residuos = ?, 
            genera_residuos_posconsumo = ?, 
            controles_realiza_gestion_residuos_solidos_peligrosos = ?, 
            genera_vertimiento_aguas_residuales_industriales = ?, 
            controles_realiza_gestion_vertimientos = ?, 
            genera_emisiones_atmosfericas = ?, 
            controles_realiza_gestion_emisiones = ?, 
            plan_contingencia_manejo_transporte = ?, 
            controles_realiza_gestion_sustancias_quimicas = ?, 
            observaciones_gestion_ambiental = ?
        WHERE Id_proveedor_gestion_ambiental = ?");

        $actualizarGestionAmbiental->bind_param(
            "ssssssssssssssssssss",
            $cuenta_sistema_gestion_ambiental,
            $certificado_ISO_14001,
            $cuenta_departamento_gestion_politica_ambiental,
            $tiene_identificados_aspectos_impactos,
            $principales_requisitos_legales,
            $realiza_registro_anual_autoridades,
            $ha_obtenido_sancion,
            $permiso_uso_recursos_naturales,
            $permisos_cuenta,
            $plan_manejo_integral_residuos,
            $genera_residuos_posconsumo,
            $controles_realiza_gestion_residuos_solidos_peligrosos,
            $genera_vertimiento_aguas_residuales_industriales,
            $controles_realiza_gestion_vertimientos,
            $genera_emisiones_atmosfericas,
            $controles_realiza_gestion_emisiones,
            $plan_contingencia_manejo_transporte,
            $controles_realiza_gestion_sustancias_quimicas,
            $observaciones_gestion_ambiental,
            $Id_proveedor
        );

        if ($actualizarGestionAmbiental->execute()) {
            $response = ['success' => true];
        } else {
            $response = ['success' => false, 'message' => 'Update failed'];
        }
    } else {
        $insertarGestionAmbiental = $conexion->prepare("INSERT INTO gestion_ambiental 
                                                (cuenta_sistema_gestion_ambiental, certificado_ISO_14001,
                                                cuenta_departamento_gestion_politica_ambiental,
                                                tiene_identificados_aspectos_impactos, 
                                                principales_requisitos_legales, realiza_registro_anual_autoridades,
                                                ha_obtenido_sancion, permiso_uso_recursos_naturales,
                                                permisos_cuenta, plan_manejo_integral_residuos, genera_residuos_posconsumo, 
                                                controles_realiza_gestion_residuos_solidos_peligrosos,
                                                genera_vertimiento_aguas_residuales_industriales,
                                                controles_realiza_gestion_vertimientos, genera_emisiones_atmosfericas,
                                                controles_realiza_gestion_emisiones, plan_contingencia_manejo_transporte,
                                                controles_realiza_gestion_sustancias_quimicas, observaciones_gestion_ambiental,
                                                Id_proveedor_gestion_ambiental)
                                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $insertarGestionAmbiental->bind_param(
            "ssssssssssssssssssss",
            $cuenta_sistema_gestion_ambiental,
            $certificado_ISO_14001,
            $cuenta_departamento_gestion_politica_ambiental,
            $tiene_identificados_aspectos_impactos,
            $principales_requisitos_legales,
            $realiza_registro_anual_autoridades,
            $ha_obtenido_sancion,
            $permiso_uso_recursos_naturales,
            $permisos_cuenta,
            $plan_manejo_integral_residuos,
            $genera_residuos_posconsumo,
            $controles_realiza_gestion_residuos_solidos_peligrosos,
            $genera_vertimiento_aguas_residuales_industriales,
            $controles_realiza_gestion_vertimientos,
            $genera_emisiones_atmosfericas,
            $controles_realiza_gestion_emisiones,
            $plan_contingencia_manejo_transporte,
            $controles_realiza_gestion_sustancias_quimicas,
            $observaciones_gestion_ambiental,
            $Id_proveedor
        );

        if ($insertarGestionAmbiental->execute()) {
            $response = ['success' => true];
        } else {
            $response = ['success' => false, 'message' => 'Insert failed'];
        }
    }
}

echo json_encode($response);
