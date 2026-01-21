<?php
require_once('../../vendor/tecnickcom/tcpdf/tcpdf.php');
include "../../ConexionBD/conexion.php";

session_start();
$Id_proveedor_laft = $_SESSION['id_proveedor_usuarios'];
$nombre_proveedor = $_SESSION['nombre_proveedor'];
$enviarCorreo = isset($_POST['enviarCorreo']) && $_POST['enviarCorreo'] === 'true';

class MYPDF extends TCPDF
{
    public function Header()
    {
        $logo_path = '../../img/hwiLogo.png';
        $this->Image($logo_path, 160, 10, 30);
        if ($this->getPage() == 1) {
            $this->SetY(50);
            $this->SetFont('helvetica', 'B', 16);
            $this->Cell(0, 10, 'Gestión Ambiental', 0, 1, 'C');
        }
    }

    public function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 8);
        $this->Cell(0, 10, 'Página ' . $this->getAliasNumPage() . ' de ' . $this->getAliasNbPages(), 0, 0, 'C');
    }
}

$sql = "SELECT * FROM laft WHERE Id_proveedor_laft = '$Id_proveedor_laft'";
$result = $conexion->query($sql);

$data = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {

        $gestion_ambiental_sql = "SELECT * FROM gestion_ambiental
                                  WHERE Id_proveedor_gestion_ambiental = '$Id_proveedor_laft'";
        $sostenibilidad_sql = "SELECT * FROM sostenibilidad_ambiental
                                INNER JOIN politicas_ambientales ON sostenibilidad_ambiental.Id_proveedor_sostenibilidad_ambiental = politicas_ambientales.Id_proveedor_politicas_ambientales
                                INNER JOIN proyectos_programas_ambientales ON sostenibilidad_ambiental.Id_proveedor_sostenibilidad_ambiental = proyectos_programas_ambientales.Id_proveedor_proyectos_programas_ambientales
                                WHERE Id_proveedor_sostenibilidad_ambiental = '$Id_proveedor_laft'";

        $gestion_ambiental_result = $conexion->query($gestion_ambiental_sql);
        $sostenibilidad_result = $conexion->query($sostenibilidad_sql);

        if ($gestion_ambiental_result && $gestion_ambiental_result->num_rows > 0) {
            $dataRow = $row;
            $dataRow['gestion_ambiental'] = [];
            while ($gestion_ambiental_row = $gestion_ambiental_result->fetch_assoc()) {
                $dataRow['gestion_ambiental'][] = $gestion_ambiental_row;
            }
        }

        if ($sostenibilidad_result && $sostenibilidad_result->num_rows > 0) {
            $dataRow['sostenibilidad'] = [];
            while ($sostenibilidad_row = $sostenibilidad_result->fetch_assoc()) {
                $dataRow['sostenibilidad'][] = $sostenibilidad_row;
            }
        }
        $data[] = $dataRow;
    }
} else {
    echo "0 resultados";
    exit;
}

$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('HWI');
$pdf->SetTitle('LAFT');
$pdf->SetSubject('Tabla de Datos');
$pdf->SetKeywords('TCPDF, PDF, registros, datos');

$pdf->setHeaderFont(array('', '', 0));
$pdf->setFooterFont(array('', '', 0));

$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(0);
$pdf->SetFooterMargin(0);

$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

$pdf->AddPage();

$pdf->SetY(90);
$pdf->SetFont('helvetica', '', 12);

foreach ($data as $row) {
    $pdf->MultiCell(0, 10, 'Fecha de Solicitud: ' . $row['fecha_solicitud_laft'], 0, 'L', 0, 1, '', '', true);
    $pdf->MultiCell(0, 10, 'Proceso: ' . $row['proceso_laft'], 0, 'L', 0, 1, '', '', true);

    $pdf->Ln(20);

    $pdf->SetFont('helvetica', '', 12);

    $gestion_ambiental_sum = 0;
    if (isset($row['gestion_ambiental']) && count($row['gestion_ambiental']) > 0) {
        foreach ($row['gestion_ambiental'] as $gestion_ambiental_row) {
            $gestion_ambiental_sum += floatval($gestion_ambiental_row['cuenta_sistema_gestion_ambiental']);
            $gestion_ambiental_sum += floatval($gestion_ambiental_row['certificado_ISO_14001']);
            $gestion_ambiental_sum += floatval($gestion_ambiental_row['cuenta_departamento_gestion_politica_ambiental']);
            $gestion_ambiental_sum += floatval($gestion_ambiental_row['tiene_identificados_aspectos_impactos']);
            $gestion_ambiental_sum += floatval($gestion_ambiental_row['realiza_registro_anual_autoridades']);
            $gestion_ambiental_sum += floatval($gestion_ambiental_row['ha_obtenido_sancion']);
            $gestion_ambiental_sum += floatval($gestion_ambiental_row['permiso_uso_recursos_naturales']);
            $gestion_ambiental_sum += floatval($gestion_ambiental_row['plan_manejo_integral_residuos']);
            $gestion_ambiental_sum += floatval($gestion_ambiental_row['genera_residuos_posconsumo']);
            $gestion_ambiental_sum += floatval($gestion_ambiental_row['genera_vertimiento_aguas_residuales_industriales']);
            $gestion_ambiental_sum += floatval($gestion_ambiental_row['genera_emisiones_atmosfericas']);
            $gestion_ambiental_sum += floatval($gestion_ambiental_row['plan_contingencia_manejo_transporte']);

            // Output the rows
            if ($gestion_ambiental_row['cuenta_sistema_gestion_ambiental'] == '0') {
                $pdf->MultiCell(0, 10, 'Cuenta con Sistema de Gestión Ambiental: No', 0, 'L', 0, 1, '', '', true);
            } elseif ($gestion_ambiental_row['cuenta_sistema_gestion_ambiental'] == '1') {
                $pdf->MultiCell(0, 10, 'Cuenta con Sistema de Gestión Ambiental: Sí', 0, 'L', 0, 1, '', '', true);
            }

            if ($gestion_ambiental_row['certificado_ISO_14001'] == '0') {
                $pdf->MultiCell(0, 10, 'Cuenta con Certificado ISO 14001: No', 0, 'L', 0, 1, '', '', true);
            } elseif ($gestion_ambiental_row['certificado_ISO_14001'] == '1') {
                $pdf->MultiCell(0, 10, 'Cuenta con Certificado ISO 14001: Sí', 0, 'L', 0, 1, '', '', true);
            }

            if ($gestion_ambiental_row['cuenta_departamento_gestion_politica_ambiental'] == '0') {
                $pdf->MultiCell(0, 10, 'Cuenta con Departamento de Gestión de Política Ambiental: No', 0, 'L', 0, 1, '', '', true);
            } elseif ($gestion_ambiental_row['cuenta_departamento_gestion_politica_ambiental'] == '1') {
                $pdf->MultiCell(0, 10, 'Cuenta con Departamento de Gestión de Política Ambiental: Sí', 0, 'L', 0, 1, '', '', true);
            }

            if ($gestion_ambiental_row['tiene_identificados_aspectos_impactos'] == '0') {
                $pdf->MultiCell(0, 10, 'Tiene Identificados Aspectos/Impactos: No', 0, 'L', 0, 1, '', '', true);
            } elseif ($gestion_ambiental_row['tiene_identificados_aspectos_impactos'] == '1') {
                $pdf->MultiCell(0, 10, 'Tiene Identificados Aspectos/Impactos: Sí', 0, 'L', 0, 1, '', '', true);
            }

            if ($gestion_ambiental_row['realiza_registro_anual_autoridades'] == '0') {
                $pdf->MultiCell(0, 10, 'Realiza Registro Anual con las Autoridades: No', 0, 'L', 0, 1, '', '', true);
            } elseif ($gestion_ambiental_row['realiza_registro_anual_autoridades'] == '1') {
                $pdf->MultiCell(0, 10, 'Realiza Registro Anual con las Autoridades: Sí', 0, 'L', 0, 1, '', '', true);
            }

            if ($gestion_ambiental_row['ha_obtenido_sancion'] == '1') {
                $pdf->MultiCell(0, 10, 'Ha Obtenido Sanciones: Sí', 0, 'L', 0, 1, '', '', true);
            } elseif ($gestion_ambiental_row['ha_obtenido_sancion'] == '0') {
                $pdf->MultiCell(0, 10, 'Ha Obtenido Sanciones: No', 0, 'L', 0, 1, '', '', true);
            }

            if ($gestion_ambiental_row['permiso_uso_recursos_naturales'] == '0') {
                $pdf->MultiCell(0, 10, 'Cuenta con Permiso de Uso de Recursos Naturales: No', 0, 'L', 0, 1, '', '', true);
            } elseif ($gestion_ambiental_row['permiso_uso_recursos_naturales'] == '1') {
                $pdf->MultiCell(0, 10, 'Cuenta con Permiso de Uso de Recursos Naturales: Sí', 0, 'L', 0, 1, '', '', true);
            }

            if ($gestion_ambiental_row['plan_manejo_integral_residuos'] == '0') {
                $pdf->MultiCell(0, 10, 'Cuenta con Plan de Manejo Integral de Residuos: No', 0, 'L', 0, 1, '', '', true);
            } elseif ($gestion_ambiental_row['plan_manejo_integral_residuos'] == '1') {
                $pdf->MultiCell(0, 10, 'Cuenta con Plan de Manejo Integral de Residuos: Sí', 0, 'L', 0, 1, '', '', true);
            }

            if ($gestion_ambiental_row['genera_residuos_posconsumo'] == '1') {
                $pdf->MultiCell(0, 10, 'Genera Residuos Posconsumo: Sí', 0, 'L', 0, 1, '', '', true);
            } elseif ($gestion_ambiental_row['genera_residuos_posconsumo'] == '0') {
                $pdf->MultiCell(0, 10, 'Genera Residuos Posconsumo: No', 0, 'L', 0, 1, '', '', true);
            }

            if ($gestion_ambiental_row['genera_vertimiento_aguas_residuales_industriales'] == '0') {
                $pdf->MultiCell(0, 10, 'Genera Vertimiento de Aguas Residuales Industriales: No', 0, 'L', 0, 1, '', '', true);
            } elseif ($gestion_ambiental_row['genera_vertimiento_aguas_residuales_industriales'] == '1') {
                $pdf->MultiCell(0, 10, 'Genera Vertimiento de Aguas Residuales Industriales: Sí', 0, 'L', 0, 1, '', '', true);
            }

            if ($gestion_ambiental_row['genera_emisiones_atmosfericas'] == '1') {
                $pdf->MultiCell(0, 10, 'Genera Emisiones Atmosféricas: Sí', 0, 'L', 0, 1, '', '', true);
            } elseif ($gestion_ambiental_row['genera_emisiones_atmosfericas'] == '0') {
                $pdf->MultiCell(0, 10, 'Genera Emisiones Atmosféricas: No', 0, 'L', 0, 1, '', '', true);
            }

            if ($gestion_ambiental_row['plan_contingencia_manejo_transporte'] == '0') {
                $pdf->MultiCell(0, 10, 'Cuenta con Plan de Contingencia para el Manejo de Transportes: No', 0, 'L', 0, 1, '', '', true);
            } elseif ($gestion_ambiental_row['plan_contingencia_manejo_transporte'] == '1') {
                $pdf->MultiCell(0, 10, 'Cuenta con Plan de Contingencia para el Manejo de Transportes: Sí', 0, 'L', 0, 1, '', '', true);
            }
        }
        $pdf->Ln(10);
        $pdf->MultiCell(0, 10, 'Total de Gestión Ambiental: ' . $gestion_ambiental_sum, 0, 'L', 0, 1, '', '', true);
    }

    $pdf->Ln(20);

    $pdf->SetFont('helvetica', 'B', 16);
    $pdf->MultiCell(0, 10, 'Sostenibilidad Ambiental', 0, 'C', 0, 1, '', '', true);
    $pdf->SetFont('helvetica', '', 12);

    $sostenibilidad_sum = 0;
    if (isset($row['sostenibilidad']) && count($row['sostenibilidad']) > 0) {
        foreach ($row['sostenibilidad'] as $sostenibilidad_row) {
            $sostenibilidad_sum += floatval($sostenibilidad_row['identificado_grupos_interes']);
            $sostenibilidad_sum += floatval($sostenibilidad_row['realizado_analisis_materialidad']);
            $sostenibilidad_sum += floatval($sostenibilidad_row['cuenta_estrategia_sostenibilidad']);
            $sostenibilidad_sum += floatval($sostenibilidad_row['priorizado_objetivos_desarrollo_sostenible']);
            $sostenibilidad_sum += floatval($sostenibilidad_row['cuenta_programas_inversion']);
            $sostenibilidad_sum += floatval($sostenibilidad_row['cuenta_programas_mejorar_desempeno_ambiental']);
            $sostenibilidad_sum += floatval($sostenibilidad_row['cuenta_programas_buen_gobierno_corporativo']);
            $sostenibilidad_sum += floatval($sostenibilidad_row['inscrito_iniciativa_fondos_sostenibles']);
            $sostenibilidad_sum += floatval($sostenibilidad_row['realiza_reporte_memoria_sostenibilidad']);

            $pdf->Ln(20);

            if ($sostenibilidad_row['identificado_grupos_interes'] == '0') {
                $pdf->MultiCell(0, 10, 'Ha Identificado Grupos de Interés: No', 0, 'L', 0, 1, '', '', true);
            } elseif ($sostenibilidad_row['identificado_grupos_interes'] == '1') {
                $pdf->MultiCell(0, 10, 'Ha Identificado Grupos de Interés: Sí', 0, 'L', 0, 1, '', '', true);
            }

            if ($sostenibilidad_row['realizado_analisis_materialidad'] == '0') {
                $pdf->MultiCell(0, 10, 'Ha Realizado Análisis de Materialidad: No', 0, 'L', 0, 1, '', '', true);
            } elseif ($sostenibilidad_row['realizado_analisis_materialidad'] == '1') {
                $pdf->MultiCell(0, 10, 'Ha Realizado Análisis de Materialidad: Sí', 0, 'L', 0, 1, '', '', true);
            }

            if ($sostenibilidad_row['cuenta_estrategia_sostenibilidad'] == '0') {
                $pdf->MultiCell(0, 10, 'Cuenta con Estrategia de Sostenibilidad: No', 0, 'L', 0, 1, '', '', true);
            } elseif ($sostenibilidad_row['cuenta_estrategia_sostenibilidad'] == '1') {
                $pdf->MultiCell(0, 10, 'Cuenta con Estrategia de Sostenibilidad: Sí', 0, 'L', 0, 1, '', '', true);
            }

            if ($sostenibilidad_row['priorizado_objetivos_desarrollo_sostenible'] == '0') {
                $pdf->MultiCell(0, 10, 'Ha Priorizado Objetivos de Desarrollo Sostenible: No', 0, 'L', 0, 1, '', '', true);
            } elseif ($sostenibilidad_row['priorizado_objetivos_desarrollo_sostenible'] == '1') {
                $pdf->MultiCell(0, 10, 'Ha Priorizado Objetivos de Desarrollo Sostenible: Sí', 0, 'L', 0, 1, '', '', true);
            }

            if ($sostenibilidad_row['cuenta_programas_inversion'] == '0') {
                $pdf->MultiCell(0, 10, 'Cuenta con Programas de Inversión: No', 0, 'L', 0, 1, '', '', true);
            } elseif ($sostenibilidad_row['cuenta_programas_inversion'] == '1') {
                $pdf->MultiCell(0, 10, 'Cuenta con Programas de Inversión: Sí', 0, 'L', 0, 1, '', '', true);
            }

            if ($sostenibilidad_row['cuenta_programas_mejorar_desempeno_ambiental'] == '0') {
                $pdf->MultiCell(0, 10, 'Cuenta con Programas para Mejorar el Desempeño Ambiental: No', 0, 'L', 0, 1, '', '', true);
            } elseif ($sostenibilidad_row['cuenta_programas_mejorar_desempeno_ambiental'] == '1') {
                $pdf->MultiCell(0, 10, 'Cuenta con Programas para Mejorar el Desempeño Ambiental: Sí', 0, 'L', 0, 1, '', '', true);
            }

            if ($sostenibilidad_row['cuenta_programas_buen_gobierno_corporativo'] == '0') {
                $pdf->MultiCell(0, 10, 'Cuenta con Programas de Buen Gobierno Corporativo: No', 0, 'L', 0, 1, '', '', true);
            } elseif ($sostenibilidad_row['cuenta_programas_buen_gobierno_corporativo'] == '1') {
                $pdf->MultiCell(0, 10, 'Cuenta con Programas de Buen Gobierno Corporativo: Sí', 0, 'L', 0, 1, '', '', true);
            }

            if ($sostenibilidad_row['inscrito_iniciativa_fondos_sostenibles'] == '0') {
                $pdf->MultiCell(0, 10, 'Se Encuentra Inscrito en Iniciativas de Fondos Sostenibles: No', 0, 'L', 0, 1, '', '', true);
            } elseif ($sostenibilidad_row['inscrito_iniciativa_fondos_sostenibles'] == '1') {
                $pdf->MultiCell(0, 10, 'Se Encuentra Inscrito en Iniciativas de Fondos Sostenibles: Sí', 0, 'L', 0, 1, '', '', true);
            }

            if ($sostenibilidad_row['realiza_reporte_memoria_sostenibilidad'] == '0') {
                $pdf->MultiCell(0, 10, 'Realiza Reporte de Memoria de Sostenibilidad: No', 0, 'L', 0, 1, '', '', true);
            } elseif ($sostenibilidad_row['realiza_reporte_memoria_sostenibilidad'] == '1') {
                $pdf->MultiCell(0, 10, 'Realiza Reporte de Memoria de Sostenibilidad: Sí', 0, 'L', 0, 1, '', '', true);
            }

            $pdf->Ln(20);
            $pdf->SetFont('', 'B', 12);
            $pdf->MultiCell(0, 10, 'Políticas Ambientales', 0, 'C', 0, 1, '', '', true);
            $pdf->SetFont('', '', 10);

            $titulos_politicas = [
                'Ha Identificado Grupos de Interés' => $sostenibilidad_row['identificado_grupos_interes'],
                'Ha Realizado Análisis de Materialidad' => $sostenibilidad_row['realizado_analisis_materialidad'],
                'Cuenta con Estrategia de Sostenibilidad' => $sostenibilidad_row['cuenta_estrategia_sostenibilidad'],
                'Ha Priorizado Objetivos de Desarrollo Sostenible' => $sostenibilidad_row['priorizado_objetivos_desarrollo_sostenible'],
                'Cuenta con Programas de Inversión' => $sostenibilidad_row['cuenta_programas_inversion'],
                'Cuenta con Programas para Mejorar el Desempeño Ambiental' => $sostenibilidad_row['cuenta_programas_mejorar_desempeno_ambiental'],
                'Cuenta con Programas de Buen Gobierno Corporativo' => $sostenibilidad_row['cuenta_programas_buen_gobierno_corporativo'],
                'Se Encuentra Inscrito en Iniciativas de Fondos Sostenibles' => $sostenibilidad_row['inscrito_iniciativa_fondos_sostenibles'],
                'Realiza Reporte de Memoria de Sostenibilidad' => $sostenibilidad_row['realiza_reporte_memoria_sostenibilidad']
            ];

            foreach ($titulos_politicas as $titulo => $valor) {
                $respuesta = $valor == '1' ? 'Sí' : 'No';
                $pdf->MultiCell(0, 10, $titulo . ': ' . $respuesta, 0, 'L', 0, 1, '', '', true);
            }

            $pdf->Ln(20);
            $pdf->SetFont('', 'B', 12);
            $pdf->MultiCell(0, 10, 'Proyectos y Programas Ambientales', 0, 'C', 0, 1, '', '', true);
            $pdf->SetFont('', '', 10);

            $titulos_proyectos = [
                'Producción Limpia' => $sostenibilidad_row['produccion_limpia'],
                'Economía Circular' => $sostenibilidad_row['economia_circular'],
                'Cambio Climático' => $sostenibilidad_row['cambio_climatico'],
                'Huella de Carbono' => $sostenibilidad_row['huella_carbono'],
                'Net Zero/Carbono Neutro' => $sostenibilidad_row['net_zero_carbono_neutro'],
                'Energías Renovables' => $sostenibilidad_row['energias_renovables'],
                'Energía Verde I-REC' => $sostenibilidad_row['energia_verde_I_REC'],
                'Eficiencia Energética' => $sostenibilidad_row['eficiencia_energetica'],
                'Ecoeficiencia Operacional' => $sostenibilidad_row['ecoeficiencia_operacional'],
                'Sustancias Químicas Ambientalmente Amigables' => $sostenibilidad_row['sustancias_quimicas_ambientalmente_amigables'],
                'Reutilización/Recirculación de Agua' => $sostenibilidad_row['reutilizacion_recirculacion_agua'],
                'Aprovechamiento de Aguas Lluvias' => $sostenibilidad_row['aprovechamiento_aguas_lluvias'],
                'Automatización/Digitalización (Papel Cero)' => $sostenibilidad_row['automatizacion_digitalizacion_papel_cero'],
                'Basura Cero' => $sostenibilidad_row['basura_cero'],
                'Cero Vertimientos' => $sostenibilidad_row['cero_vertimientos'],
                'Cero Emisiones' => $sostenibilidad_row['cero_emisiones'],
                'Ecodiseño de Productos y Embalajes' => $sostenibilidad_row['ecodiseno_productos_embalajes'],
                'Análisis de Ciclo de Vida' => $sostenibilidad_row['analisis_ciclo_vida'],
                'Contratación de Personas con Discapacidad' => $sostenibilidad_row['contratacion_personas_discapacidad'],
                'Contratación de Mujeres en Altos Cargos Directivos' => $sostenibilidad_row['contratacion_mujeres_altos_cargos_directivos'],
                'Selección y Contratación con Criterios de Diversidad' => $sostenibilidad_row['seleccion_contratacion_criterios_diversidad'],
                'Derechos Laborales' => $sostenibilidad_row['derechos_laborales'],
                'Evaluación de Proveedores con Criterios Sociales y Ambientales' => $sostenibilidad_row['evaluacion_proveedores_criterios_sociales_ambientales'],
                'Desarrollo de la Cadena de Suministro Local' => $sostenibilidad_row['desarrollo_cadena_suministro_local'],
                'Inversiones Sostenibles' => $sostenibilidad_row['inversiones_sostenibles']
            ];

            foreach ($titulos_proyectos as $titulo => $valor) {
                $respuesta = $valor == '1' ? 'Sí' : 'No';
                $pdf->MultiCell(0, 10, $titulo . ': ' . $respuesta, 0, 'L', 0, 1, '', '', true);
            }
        }
        $pdf->Ln(10);
        $pdf->MultiCell(0, 10, 'Total de Sostenibilidad Ambiental: ' . $sostenibilidad_sum, 0, 'L', 0, 1, '', '', true);
    }

    $pdf->Ln(10);
}

$base_directory = realpath(__DIR__ . '/../../documents');
$target_directory = $base_directory . '/' . $Id_proveedor_laft;

if ($base_directory === false) {
    die("El directorio base no es válido.");
}

if (!is_dir($target_directory)) {
    if (!mkdir($target_directory, 0777, true)) {
        die("No se pudo crear el directorio: $target_directory");
    }
}

$save_path = $target_directory . '/Ambiental.pdf';

$pdf->Output($save_path, 'F');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (file_exists($save_path)) {
    $response = array(
        'success' => true,
        'message' => 'El archivo PDF ha sido guardado en: ' . $save_path
    );

    if ($enviarCorreo) {

        require '../../Services/Exception.php';
        require '../../Services/PHPMailer.php';
        require '../../Services/SMTP.php';

        try {

            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host       = 'gtxm1009.siteground.biz';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'hwiverificacion@hacebwhirlpoolindustrial.com';
            $mail->Password   = 'HWI2023*';
            $mail->SMTPSecure = 'ssl';
            $mail->Port       = 465;
            $mail->setFrom('hwiverificacion@hacebwhirlpoolindustrial.com', 'Equipo BI');
            $mail->addAddress('ricardo.rojas@hacebwhirlpool.com');
            $mail->addAddress('gestion.ambiental@hacebwhirlpool.com');
            $mail->isHTML(true);
            $mail->Subject = 'Nuevo Registro Ambiental';
            $logoUrl = "https://dattics.com/wp-content/uploads/2022/03/Haceb-Whirlpool-Industrial.jpg";
            $mail->Body = '
            <div style="border-radius:10px; border: 1px solid #cccccc; max-width: 100%; max-height: 100%; margin-top: 50px; margin-left: auto; margin-right: auto; text-align: center; padding: 20px;">
                <div style="text-align: center;">
                    <div style="display: inline-block; border-radius: 10px; max-width: 200px; padding: 10px;">
                        <img src="' . $logoUrl . '" alt="Logo Empresa" style="max-width: 100%; height: auto; border-radius: 50%; border: 1px solid #cccccc;">
                    </div>
                </div>
            <h4 style="margin-top: 20px;">' . $nombre_proveedor . ' ha Registrado el Formato de Vinculación Ambiental.</h4>
            <hr style="background-color: #cccccc; border: none; height: 1px; width: 100%; margin-top: 20px; margin-bottom: 20px;">
                <div style="width: 95%; text-align: justify; margin-left: auto; margin-right: auto;">
                    <div>
                        <p><span style="font-weight: bold;">Valídalo aquí: </span><a href="https://sistema.proveedores.hacebwhirlpoolindustrial.com/SistemaProveedores/index.php">Haceb Whirlpool</a></p>
                        <p style="font-weight: bold;"> </p>
                    </div>
                </div>
            </div>
            <div style="text-align: center;">
                <p style="color: #999999;">Copyright © Haceb Whirlpool Industrial S.A.S</p>
            <div>
        ';
            $mail->send();
        } catch (Exception $e) {
            echo "Error al enviar el correo electrónico: {$mail->ErrorInfo}";
        }
    }
} else {
    $response = array(
        'success' => false,
        'message' => 'No se pudo guardar el archivo PDF.'
    );
}

header('Content-Type: application/json');
echo json_encode($response);
