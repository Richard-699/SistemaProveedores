<?php
session_start();
include "../../ConexionBD/conexion.php";

$Id_proveedor = $_SESSION['id_proveedor_usuarios'];

$consultarSostenibilidad = mysqli_query($conexion, "SELECT * FROM sostenibilidad_ambiental 
                                        INNER JOIN politicas_ambientales ON sostenibilidad_ambiental.Id_proveedor_sostenibilidad_ambiental = politicas_ambientales.Id_proveedor_politicas_ambientales
                                        INNER JOIN proyectos_programas_ambientales ON sostenibilidad_ambiental.Id_proveedor_sostenibilidad_ambiental = proyectos_programas_ambientales.Id_proveedor_proyectos_programas_ambientales
                                        WHERE Id_proveedor_sostenibilidad_ambiental = '$Id_proveedor'");

if (isset($_POST['isconsulta']) && $_POST['isconsulta'] === "true") {

    if (mysqli_num_rows($consultarSostenibilidad) > 0) {
        $sostenibilidadAmbiental = mysqli_fetch_assoc($consultarSostenibilidad);
        $response = [
            'success' => true,
            'sostenibilidadAmbiental' => $sostenibilidadAmbiental
        ];
    } else {
        $response = [
            'success' => false,
            'message' => 'No record found'
        ];
    }
} else {

    $identificado_grupos_interes = $_POST['identificado_grupos_interes'];
    $realizado_analisis_materialidad = $_POST['realizado_analisis_materialidad'];
    $cuenta_estrategia_sostenibilidad = $_POST['cuenta_estrategia_sostenibilidad'];
    $priorizado_objetivos_desarrollo_sostenible = $_POST['priorizado_objetivos_desarrollo_sostenible'];
    $cuenta_programas_inversion = $_POST['cuenta_programas_inversion'];
    $cuenta_programas_mejorar_desempeno_ambiental = $_POST['cuenta_programas_mejorar_desempeno_ambiental'];
    $cuenta_programas_buen_gobierno_corporativo = $_POST['cuenta_programas_buen_gobierno_corporativo'];
    $inscrito_iniciativa_fondos_sostenibles = $_POST['inscrito_iniciativa_fondos_sostenibles'];
    $realiza_reporte_memoria_sostenibilidad = $_POST['realiza_reporte_memoria_sostenibilidad'];

    //Politicas ambientales

    $politica_sostenibilidad = isset($_POST['politica_sostenibilidad']) ? 1 : 0;
    $politica_ambiental = isset($_POST['politica_ambiental']) ? 1 : 0;
    $seguridad_salud_trabajo = isset($_POST['seguridad_salud_trabajo']) ? 1 : 0;
    $politica_derechos_humanos = isset($_POST['politica_derechos_humanos']) ? 1 : 0;
    $politica_debida_diligencia = isset($_POST['politica_debida_diligencia']) ? 1 : 0;
    $politica_prevencion = isset($_POST['politica_prevencion']) ? 1 : 0;
    $codigo_etica_empresarial = isset($_POST['codigo_etica_empresarial']) ? 1 : 0;
    $politica_igualdad = isset($_POST['politica_igualdad']) ? 1 : 0;

    //Proyectos/Programas Ambientales

    $produccion_limpia = isset($_POST['produccion_limpia']) ? 1 : 0;
    $economia_circular = isset($_POST['economia_circular']) ? 1 : 0;
    $cambio_climatico = isset($_POST['cambio_climatico']) ? 1 : 0;
    $huella_carbono = isset($_POST['huella_carbono']) ? 1 : 0;
    $net_zero_carbono_neutro = isset($_POST['net_zero_carbono_neutro']) ? 1 : 0;
    $energias_renovables = isset($_POST['energias_renovables']) ? 1 : 0;
    $energia_verde_I_REC = isset($_POST['energia_verde_I_REC']) ? 1 : 0;
    $eficiencia_energetica = isset($_POST['eficiencia_energetica']) ? 1 : 0;
    $ecoeficiencia_operacional = isset($_POST['ecoeficiencia_operacional']) ? 1 : 0;
    $sustancias_quimicas_ambientalmente_amigables = isset($_POST['sustancias_quimicas_ambientalmente_amigables']) ? 1 : 0;
    $reutilizacion_recirculacion_agua = isset($_POST['reutilizacion_recirculacion_agua']) ? 1 : 0;
    $aprovechamiento_aguas_lluvias = isset($_POST['aprovechamiento_aguas_lluvias']) ? 1 : 0;
    $automatizacion_digitalizacion_papel_cero = isset($_POST['automatizacion_digitalizacion_papel_cero']) ? 1 : 0;
    $basura_cero = isset($_POST['basura_cero']) ? 1 : 0;
    $cero_vertimientos = isset($_POST['cero_vertimientos']) ? 1 : 0;
    $cero_emisiones = isset($_POST['cero_emisiones']) ? 1 : 0;
    $ecodiseno_productos_embalajes = isset($_POST['ecodiseno_productos_embalajes']) ? 1 : 0;
    $analisis_ciclo_vida = isset($_POST['analisis_ciclo_vida']) ? 1 : 0;
    $contratacion_personas_discapacidad = isset($_POST['contratacion_personas_discapacidad']) ? 1 : 0;
    $contratacion_mujeres_altos_cargos_directivos = isset($_POST['contratacion_mujeres_altos_cargos_directivos']) ? 1 : 0;
    $seleccion_contratacion_criterios_diversidad = isset($_POST['seleccion_contratacion_criterios_diversidad']) ? 1 : 0;
    $derechos_laborales = isset($_POST['derechos_laborales']) ? 1 : 0;
    $evaluacion_proveedores_criterios_sociales_ambientales = isset($_POST['evaluacion_proveedores_criterios_sociales_ambientales']) ? 1 : 0;
    $desarrollo_cadena_suministro_local = isset($_POST['desarrollo_cadena_suministro_local']) ? 1 : 0;
    $inversiones_sostenibles = isset($_POST['inversiones_sostenibles']) ? 1 : 0;



    if (mysqli_num_rows($consultarSostenibilidad) > 0) {

        $actualizarSostenibilidad = $conexion->prepare("UPDATE sostenibilidad_ambiental SET
            identificado_grupos_interes = ?, 
            realizado_analisis_materialidad = ?, 
            cuenta_estrategia_sostenibilidad = ?, 
            priorizado_objetivos_desarrollo_sostenible = ?, 
            cuenta_programas_inversion = ?, 
            cuenta_programas_mejorar_desempeno_ambiental = ?, 
            cuenta_programas_buen_gobierno_corporativo = ?, 
            inscrito_iniciativa_fondos_sostenibles = ?, 
            realiza_reporte_memoria_sostenibilidad = ?
        WHERE Id_proveedor_sostenibilidad_ambiental = ?");

        $actualizarSostenibilidad->bind_param(
            "ssssssssss",
            $identificado_grupos_interes,
            $realizado_analisis_materialidad,
            $cuenta_estrategia_sostenibilidad,
            $priorizado_objetivos_desarrollo_sostenible,
            $cuenta_programas_inversion,
            $cuenta_programas_mejorar_desempeno_ambiental,
            $cuenta_programas_buen_gobierno_corporativo,
            $inscrito_iniciativa_fondos_sostenibles,
            $realiza_reporte_memoria_sostenibilidad,
            $Id_proveedor
        );

        if ($actualizarSostenibilidad->execute()) {
            $response = ['success' => true];
        } else {
            $response = ['success' => false, 'message' => 'Update failed'];
        }

        $actualizarPoliticasAmbientales = $conexion->prepare("UPDATE politicas_ambientales 
            SET politica_sostenibilidad = ?, 
                politica_ambiental = ?, 
                seguridad_salud_trabajo = ?, 
                politica_derechos_humanos = ?, 
                politica_debida_diligencia = ?, 
                politica_prevencion = ?, 
                codigo_etica_empresarial = ?, 
                politica_igualdad = ? 
            WHERE Id_proveedor_politicas_ambientales = ?");

        $actualizarPoliticasAmbientales->bind_param(
            "sssssssss",
            $politica_sostenibilidad,
            $politica_ambiental,
            $seguridad_salud_trabajo,
            $politica_derechos_humanos,
            $politica_debida_diligencia,
            $politica_prevencion,
            $codigo_etica_empresarial,
            $politica_igualdad,
            $Id_proveedor
        );

        if ($actualizarPoliticasAmbientales->execute()) {
            $response = ['success' => true];
        } else {
            $response = ['success' => false, 'message' => 'Update failed'];
        }

        $actualizarProyectosProgramasAmbientales = $conexion->prepare("UPDATE proyectos_programas_ambientales 
            SET produccion_limpia = ?, 
                economia_circular = ?, 
                cambio_climatico = ?, 
                huella_carbono = ?, 
                net_zero_carbono_neutro = ?, 
                energias_renovables = ?, 
                energia_verde_I_REC = ?, 
                eficiencia_energetica = ?, 
                ecoeficiencia_operacional = ?, 
                sustancias_quimicas_ambientalmente_amigables = ?, 
                reutilizacion_recirculacion_agua = ?, 
                aprovechamiento_aguas_lluvias = ?, 
                automatizacion_digitalizacion_papel_cero = ?, 
                basura_cero = ?, 
                cero_vertimientos = ?, 
                cero_emisiones = ?, 
                ecodiseno_productos_embalajes = ?, 
                analisis_ciclo_vida = ?, 
                contratacion_personas_discapacidad = ?, 
                contratacion_mujeres_altos_cargos_directivos = ?, 
                seleccion_contratacion_criterios_diversidad = ?, 
                derechos_laborales = ?, 
                evaluacion_proveedores_criterios_sociales_ambientales = ?, 
                desarrollo_cadena_suministro_local = ?, 
                inversiones_sostenibles = ? 
            WHERE Id_proveedor_proyectos_programas_ambientales = ?");

        $actualizarProyectosProgramasAmbientales->bind_param(
            "ssssssssssssssssssssssssss",
            $produccion_limpia,
            $economia_circular,
            $cambio_climatico,
            $huella_carbono,
            $net_zero_carbono_neutro,
            $energias_renovables,
            $energia_verde_I_REC,
            $eficiencia_energetica,
            $ecoeficiencia_operacional,
            $sustancias_quimicas_ambientalmente_amigables,
            $reutilizacion_recirculacion_agua,
            $aprovechamiento_aguas_lluvias,
            $automatizacion_digitalizacion_papel_cero,
            $basura_cero,
            $cero_vertimientos,
            $cero_emisiones,
            $ecodiseno_productos_embalajes,
            $analisis_ciclo_vida,
            $contratacion_personas_discapacidad,
            $contratacion_mujeres_altos_cargos_directivos,
            $seleccion_contratacion_criterios_diversidad,
            $derechos_laborales,
            $evaluacion_proveedores_criterios_sociales_ambientales,
            $desarrollo_cadena_suministro_local,
            $inversiones_sostenibles,
            $Id_proveedor
        );

        if ($actualizarProyectosProgramasAmbientales->execute()) {
            $response = ['success' => true];
        } else {
            $response = ['success' => false, 'message' => 'Update failed'];
        }
    } else {
        $insertarSostenibilidad = $conexion->prepare("INSERT INTO sostenibilidad_ambiental 
                                                (identificado_grupos_interes, realizado_analisis_materialidad,
                                                cuenta_estrategia_sostenibilidad,
                                                priorizado_objetivos_desarrollo_sostenible, 
                                                cuenta_programas_inversion, cuenta_programas_mejorar_desempeno_ambiental,
                                                cuenta_programas_buen_gobierno_corporativo, inscrito_iniciativa_fondos_sostenibles,
                                                realiza_reporte_memoria_sostenibilidad, 
                                                Id_proveedor_sostenibilidad_ambiental)
                                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $insertarSostenibilidad->bind_param(
            "ssssssssss",
            $identificado_grupos_interes,
            $realizado_analisis_materialidad,
            $cuenta_estrategia_sostenibilidad,
            $priorizado_objetivos_desarrollo_sostenible,
            $cuenta_programas_inversion,
            $cuenta_programas_mejorar_desempeno_ambiental,
            $cuenta_programas_buen_gobierno_corporativo,
            $inscrito_iniciativa_fondos_sostenibles,
            $realiza_reporte_memoria_sostenibilidad,
            $Id_proveedor
        );

        if ($insertarSostenibilidad->execute()) {
            $response = ['success' => true];
        } else {
            $response = ['success' => false, 'message' => 'Insert failed'];
        }

        $insertarPoliticasAmbientales = $conexion->prepare("INSERT INTO politicas_ambientales 
                                                (politica_sostenibilidad, politica_ambiental,
                                                seguridad_salud_trabajo,
                                                politica_derechos_humanos, politica_debida_diligencia,
                                                politica_prevencion, codigo_etica_empresarial,
                                                politica_igualdad,
                                                Id_proveedor_politicas_ambientales)
                                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $insertarPoliticasAmbientales->bind_param(
            "sssssssss",
            $politica_sostenibilidad,
            $politica_ambiental,
            $seguridad_salud_trabajo,
            $politica_derechos_humanos,
            $politica_debida_diligencia,
            $politica_prevencion,
            $codigo_etica_empresarial,
            $politica_igualdad,
            $Id_proveedor
        );

        if ($insertarPoliticasAmbientales->execute()) {
            $response = ['success' => true];
        } else {
            $response = ['success' => false, 'message' => 'Insert failed'];
        }

        $insertarProyectosProgramasAmbientales = $conexion->prepare("INSERT INTO proyectos_programas_ambientales 
                                                (produccion_limpia, economia_circular,
                                                cambio_climatico, huella_carbono,
                                                net_zero_carbono_neutro, energias_renovables,
                                                energia_verde_I_REC, eficiencia_energetica,
                                                ecoeficiencia_operacional,
                                                sustancias_quimicas_ambientalmente_amigables,
                                                reutilizacion_recirculacion_agua,
                                                aprovechamiento_aguas_lluvias,
                                                automatizacion_digitalizacion_papel_cero, basura_cero,
                                                cero_vertimientos, cero_emisiones, ecodiseno_productos_embalajes,
                                                analisis_ciclo_vida, contratacion_personas_discapacidad,
                                                contratacion_mujeres_altos_cargos_directivos,
                                                seleccion_contratacion_criterios_diversidad,
                                                derechos_laborales,
                                                evaluacion_proveedores_criterios_sociales_ambientales,
                                                desarrollo_cadena_suministro_local,
                                                inversiones_sostenibles,
                                                Id_proveedor_proyectos_programas_ambientales)
                                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $insertarProyectosProgramasAmbientales->bind_param(
            "ssssssssssssssssssssssssss",
            $produccion_limpia,
            $economia_circular,
            $cambio_climatico,
            $huella_carbono,
            $net_zero_carbono_neutro,
            $energias_renovables,
            $energia_verde_I_REC,
            $eficiencia_energetica,
            $ecoeficiencia_operacional,
            $sustancias_quimicas_ambientalmente_amigables,
            $reutilizacion_recirculacion_agua,
            $aprovechamiento_aguas_lluvias,
            $automatizacion_digitalizacion_papel_cero,
            $basura_cero,
            $cero_vertimientos,
            $cero_emisiones,
            $ecodiseno_productos_embalajes,
            $analisis_ciclo_vida,
            $contratacion_personas_discapacidad,
            $contratacion_mujeres_altos_cargos_directivos,
            $seleccion_contratacion_criterios_diversidad,
            $derechos_laborales,
            $evaluacion_proveedores_criterios_sociales_ambientales,
            $desarrollo_cadena_suministro_local,
            $inversiones_sostenibles,
            $Id_proveedor
        );

        if ($insertarProyectosProgramasAmbientales->execute()) {
            $response = ['success' => true];
        } else {
            $response = ['success' => false, 'message' => 'Insert failed'];
        }
    }
}

echo json_encode($response);
