<?php
require_once('../../vendor/tecnickcom/tcpdf/tcpdf.php');
include "../../ConexionBD/conexion.php";

session_start();
$Id_proveedor_laft = $_SESSION['id_proveedor_usuarios'];
$nombre_proveedor = $_SESSION['nombre_proveedor'];
$carta_beneficiarios_finales = $_SESSION['carta_beneficiarios_finales'];
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
            $this->Cell(0, 10, 'Formato de Vinculación', 0, 1, 'C');
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
        $Id_laft = $row['Id_laft'];
        $dataRow = $row;

        if ($row['tipo_persona_laft'] == 'Juridica') {
<<<<<<< HEAD
            $details_sql = "SELECT * FROM laft_persona_juridica 
=======
            $details_sql = "SELECT * FROM laft_persona_juridica
>>>>>>> 8fe25a02a378af3db1c5f09c74bddd125a144800
                            INNER JOIN pais ON laft_persona_juridica.Id_pais_persona_juridica = pais.id_pais
                            WHERE Id_laft_persona_juridica = '$Id_laft'";
            $details_result = $conexion->query($details_sql);

            if ($details_result && $details_result->num_rows > 0) {
                $details_row = $details_result->fetch_assoc();
                $dataRow = array_merge($dataRow, $details_row);

                $Id_persona_juridica = $details_row['Id_persona_juridica'];

                $certificaciones_laft_sql = "SELECT * FROM laft_certificaciones WHERE Id_laft_persona_juridica_certificacion = '$Id_persona_juridica'";
                $certificaciones_laft_result = $conexion->query($certificaciones_laft_sql);
                if ($certificaciones_laft_result && $certificaciones_laft_result->num_rows > 0) {
                    $dataRow['certificaciones'] = [];
                    while ($certificaciones_laft_row = $certificaciones_laft_result->fetch_assoc()) {
                        $dataRow['certificaciones'][] = $certificaciones_laft_row;
                    }
                }

                // Representantes
                $representante_sql = "SELECT * FROM laft_representante_legal WHERE Id_laft_representante_legal = '$Id_laft'";
                $representante_result = $conexion->query($representante_sql);
                if ($representante_result && $representante_result->num_rows > 0) {
                    $dataRow['representantes'] = [];
                    while ($representante_row = $representante_result->fetch_assoc()) {
                        $dataRow['representantes'][] = $representante_row;
                    }
                }

                // Beneficiarios
                $beneficiarios_sql = "SELECT * FROM laft_beneficiarios_finales WHERE Id_laft_beneficiarios_finales = '$Id_laft'";
                $beneficiarios_result = $conexion->query($beneficiarios_sql);
                if ($beneficiarios_result && $beneficiarios_result->num_rows > 0) {
                    $dataRow['beneficiarios'] = [];
                    while ($beneficiario_row = $beneficiarios_result->fetch_assoc()) {
                        $dataRow['beneficiarios'][] = $beneficiario_row;
                    }
                }

                // Contactos
                $contactos_sql = "SELECT * FROM laft_contacto WHERE Id_laft_contacto = '$Id_laft'";
                $contactos_result = $conexion->query($contactos_sql);
                if ($contactos_result && $contactos_result->num_rows > 0) {
                    $dataRow['contactos'] = [];
                    while ($contacto_row = $contactos_result->fetch_assoc()) {
                        $dataRow['contactos'][] = $contacto_row;
                    }
                }

                // PEP Info
                $pep_info_sql = "SELECT * FROM laft_pep_infogeneral WHERE Id_laft_pep_infogeneral = '$Id_laft'";
                $pep_info_result = $conexion->query($pep_info_sql);
                if ($pep_info_result && $pep_info_result->num_rows > 0) {
                    $dataRow['pep_info'] = [];
                    while ($pep_info_row = $pep_info_result->fetch_assoc()) {
                        $dataRow['pep_info'][] = $pep_info_row;
                    }
                }

                // PEP
                $pep_sql = "SELECT * FROM laft_pep WHERE Id_laft_pep = '$Id_laft'";
                $pep_result = $conexion->query($pep_sql);
                if ($pep_result && $pep_result->num_rows > 0) {
                    $dataRow['pep'] = [];
                    while ($pep_row = $pep_result->fetch_assoc()) {
                        $dataRow['pep'][] = $pep_row;
                    }
                }
            }
        } elseif ($row['tipo_persona_laft'] == 'Natural') {
            $details_sql = "SELECT * FROM laft_persona_natural 
                            INNER JOIN pais ON laft_persona_natural.Id_pais_persona_natural = pais.id_pais
                            WHERE id_laft_persona_natural = '$Id_laft'";
            $details_result = $conexion->query($details_sql);

            if ($details_result && $details_result->num_rows > 0) {
                $details_row = $details_result->fetch_assoc();
                $dataRow = array_merge($dataRow, $details_row);

                // PEP Info
                $pep_info_sql = "SELECT * FROM laft_pep_infogeneral WHERE Id_laft_pep_infogeneral = '$Id_laft'";
                $pep_info_result = $conexion->query($pep_info_sql);
                if ($pep_info_result && $pep_info_result->num_rows > 0) {
                    $dataRow['pep_info'] = [];
                    while ($pep_info_row = $pep_info_result->fetch_assoc()) {
                        $dataRow['pep_info'][] = $pep_info_row;
                    }
                }

                // PEP
                $pep_sql = "SELECT * FROM laft_pep WHERE Id_laft_pep = '$Id_laft'";
                $pep_result = $conexion->query($pep_sql);
                if ($pep_result && $pep_result->num_rows > 0) {
                    $dataRow['pep'] = [];
                    while ($pep_row = $pep_result->fetch_assoc()) {
                        $dataRow['pep'][] = $pep_row;
                    }
                }
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
    $pdf->MultiCell(0, 10, 'Fecha de Actualización: ' . $row['ultima_actualizacion_laft'], 0, 'L', 0, 1, '', '', true);
    $pdf->MultiCell(0, 10, 'Proceso: ' . $row['proceso_laft'], 0, 'L', 0, 1, '', '', true);
    $pdf->MultiCell(0, 10, 'Tipo de Persona: ' . $row['tipo_persona_laft'], 0, 'L', 0, 1, '', '', true);

    $pdf->Ln(20);

    $pdf->SetFont('helvetica', 'B', 14);
    $pdf->MultiCell(0, 10, 'Persona ' . ucfirst($row['tipo_persona_laft']), 0, 'C', 0, 1, '', '', true);
    $pdf->Ln(20);

    $pdf->SetFont('helvetica', '', 12);
    if ($row['tipo_persona_laft'] == 'Juridica') {
        $pdf->MultiCell(0, 10, 'Razón Social: ' . $row['razon_social_persona_juridica'], 0, 'L', 0, 1, '', '', true);

        if ($row['tipo_identificacion_persona_juridica'] != 'Otro') {
            $pdf->MultiCell(0, 10, 'Tipo Identificación: ' . $row['tipo_identificacion_persona_juridica'], 0, 'L', 0, 1, '', '', true);
        } else {
            $pdf->MultiCell(0, 10, 'Tipo Identificación: ' . $row['otro_tipo_identificacion'], 0, 'L', 0, 1, '', '', true);
        }

        $pdf->MultiCell(0, 10, 'Número Identificación: ' . $row['numero_identificacion_persona_juridica'], 0, 'L', 0, 1, '', '', true);
        $pdf->MultiCell(0, 10, 'País: ' . $row['pais'], 0, 'L', 0, 1, '', '', true);
        $pdf->MultiCell(0, 10, 'Departamento: ' . $row['departamento_persona_juridica'], 0, 'L', 0, 1, '', '', true);
        $pdf->MultiCell(0, 10, 'Ciudad: ' . $row['ciudad_persona_juridica'], 0, 'L', 0, 1, '', '', true);
        $pdf->MultiCell(0, 10, 'Dirección: ' . $row['direccion_persona_juridica'], 0, 'L', 0, 1, '', '', true);
        $pdf->MultiCell(0, 10, 'País: ' . $row['pais'], 0, 'L', 0, 1, '', '', true);
        $pdf->MultiCell(0, 10, 'Departamento: ' . $row['departamento_persona_juridica'], 0, 'L', 0, 1, '', '', true);
        $pdf->MultiCell(0, 10, 'Ciudad: ' . $row['ciudad_persona_juridica'], 0, 'L', 0, 1, '', '', true);
        $pdf->MultiCell(0, 10, 'Teléfono: ' . $row['telefono_persona_juridica'], 0, 'L', 0, 1, '', '', true);
        $pdf->MultiCell(0, 10, 'Correo: ' . $row['correo_electronico_persona_juridica'], 0, 'L', 0, 1, '', '', true);
        $pdf->MultiCell(0, 10, 'Código ciuu: ' . $row['codigo_ciiu_persona_juridica'], 0, 'L', 0, 1, '', '', true);
        if ($row['requiere_permiso_licencia_operar'] == 1) {
            $pdf->MultiCell(0, 10, 'Requiere Permiso/Licencia Operar: Sí', 0, 'L', 0, 1, '', '', true);
        } else {
            $pdf->MultiCell(0, 10, 'Requiere Permiso/Licencia Operar: No', 0, 'L', 0, 1, '', '', true);
        }

        if ($row['condicion_pago'] != 'Otro') {
            $pdf->MultiCell(0, 10, 'Condición de Pago: ' . $row['condicion_pago'] . ' días', 0, 'L', 0, 1, '', '', true);
        } else {
            $pdf->MultiCell(0, 10, 'Condición de Pago: ' . $row['cuantos_dias_condicion_pago'] . ' días', 0, 'L', 0, 1, '', '', true);
        }

        $pdf->SetFont('helvetica', 'B', 14);
        if (isset($row['representantes']) && count($row['representantes']) > 0) {
            foreach ($row['representantes'] as $representante_row) {
                if ($pdf->GetY() > 250) {
                    $pdf->AddPage();
                    $pdf->SetY(50);
                }
                $pdf->SetFont('helvetica', 'B', 14);
                if ($representante_row['tipo_representante_legal'] == 1) {
                    $pdf->MultiCell(0, 10, 'Representante Legal', 0, 'C', 0, 1, '', '', true);
                } elseif ($representante_row['tipo_representante_legal'] == 2) {
                    $pdf->MultiCell(0, 10, 'Suplente Representante Legal', 0, 'C', 0, 1, '', '', true);
                }
                $pdf->Ln(10);
                $pdf->SetFont('helvetica', '', 12);
                $pdf->MultiCell(0, 10, 'Nombre(s): ' . $representante_row['nombres_representante_legal'], 0, 'L', 0, 1, '', '', true);
                $pdf->MultiCell(0, 10, 'Apellido(s): ' . $representante_row['apellidos_representante_legal'], 0, 'L', 0, 1, '', '', true);
                $pdf->MultiCell(0, 10, 'Tipo de Identificación: ' . $representante_row['tipo_documento_representante_legal'], 0, 'L', 0, 1, '', '', true);
                $pdf->MultiCell(0, 10, 'Número Identificación: ' . $representante_row['numero_identificacion_representante_legal'], 0, 'L', 0, 1, '', '', true);
                $pdf->MultiCell(0, 10, 'Número de Contacto: ' . $representante_row['numero_contacto_representante_legal'], 0, 'L', 0, 1, '', '', true);
                $pdf->MultiCell(0, 10, 'Correo: ' . $representante_row['correo_electronico_representante_legal'], 0, 'L', 0, 1, '', '', true);
                $pdf->Ln(10);
            }
        }

        if ($carta_beneficiarios_finales == 1) {
            $pdf->SetFont('helvetica', 'B', 14);
            $pdf->MultiCell(0, 10, 'Beneficiarios Finales', 0, 'C', 0, 1, '', '', true);
            $pdf->Ln(10);
            $pdf->SetFont('helvetica', '', 12);
            $pdf->MultiCell(0, 10, 'El proveedor adjuntó la Certificación de Beneficiarios Finales.', 0, 'L', 0, 1, '', '', true);
        } else {
            if (isset($row['beneficiarios']) && count($row['beneficiarios']) > 0) {
                $pdf->SetFont('helvetica', 'B', 14);
                $pdf->MultiCell(0, 10, 'Beneficiarios Finales', 0, 'C', 0, 1, '', '', true);
                $pdf->Ln(10);

                $pdf->SetFont('helvetica', '', 12);
                foreach ($row['beneficiarios'] as $beneficiario_row) {
                    if ($pdf->GetY() > 250) {
                        $pdf->AddPage();
                        $pdf->SetY(70);
                    }
                    $pdf->MultiCell(0, 10, 'Razón Social o Nombre(s): ' . $beneficiario_row['nombre_razon_social_beneficiarios_finales'], 0, 'L', 0, 1, '', '', true);
                    if ($beneficiario_row['otro_tipo_identificacion'] == null) {
                        $pdf->MultiCell(0, 10, 'Tipo de Identificación: ' . $beneficiario_row['tipo_identificacion_beneficiarios_finales'], 0, 'L', 0, 1, '', '', true);
                    } else {
                        $pdf->MultiCell(0, 10, 'Tipo de Identificación: ' . $beneficiario_row['otro_tipo_identificacion'], 0, 'L', 0, 1, '', '', true);
                    }
                    $pdf->MultiCell(0, 10, 'Número Identificación: ' . $beneficiario_row['numero_identificacion_beneficiarios_finales'], 0, 'L', 0, 1, '', '', true);
                    $pdf->MultiCell(0, 10, 'Porcentaje de Participación: ' . $beneficiario_row['porcentaje_participacion_beneficiarios_finales'], 0, 'L', 0, 1, '', '', true);

                    // Línea horizontal para separar los registros
                    $pdf->Ln(5);
                    $pdf->SetDrawColor(0, 0, 0);
                    $pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
                    $pdf->Ln(10);
                }
            }
        }

        $pdf->SetFont('helvetica', 'B', 14);
        if (isset($row['contactos']) && count($row['contactos']) > 0) {
            foreach ($row['contactos'] as $contacto_row) {
                if ($pdf->GetY() > 250) {
                    $pdf->AddPage();
                    $pdf->SetY(50);
                }
                $pdf->SetFont('helvetica', 'B', 14);
                if ($contacto_row['Id_tipos_contacto_laft_contacto'] == 1) {
                    $pdf->MultiCell(0, 10, 'Contacto Comercial', 0, 'C', 0, 1, '', '', true);
                } elseif ($contacto_row['Id_tipos_contacto_laft_contacto'] == 2) {
                    $pdf->MultiCell(0, 10, 'Contacto Financiero', 0, 'C', 0, 1, '', '', true);
                } elseif ($contacto_row['Id_tipos_contacto_laft_contacto'] == 3) {
                    $pdf->MultiCell(0, 10, 'Oficial de Cumplimiento', 0, 'C', 0, 1, '', '', true);
                }
                $pdf->Ln(10);
                $pdf->SetFont('helvetica', '', 12);
                $pdf->MultiCell(0, 10, 'Nombre(s): ' . $contacto_row['nombres_contacto'], 0, 'L', 0, 1, '', '', true);
                $pdf->MultiCell(0, 10, 'Apellido(s): ' . $contacto_row['apellidos_contacto'], 0, 'L', 0, 1, '', '', true);
                $pdf->MultiCell(0, 10, 'Cargo: ' . $contacto_row['cargo_contacto'], 0, 'L', 0, 1, '', '', true);
                $pdf->MultiCell(0, 10, 'Teléfono: ' . $contacto_row['numero_contacto'], 0, 'L', 0, 1, '', '', true);
                $pdf->MultiCell(0, 10, 'Correo: ' . $contacto_row['correo_electronico_contacto'], 0, 'L', 0, 1, '', '', true);
                $pdf->Ln(10);
            }
        }

        $pdf->SetFont('helvetica', 'B', 14);

        $pdf->Ln(20);
        if (isset($row['certificaciones']) && count($row['certificaciones']) > 0) {
            foreach ($row['certificaciones'] as $certificaciones_laft_row) {
                if ($pdf->GetY() > 250) {
                    $pdf->AddPage();
                    $pdf->SetY(50);
                }
                $pdf->SetFont('helvetica', 'B', 14);

                $pdf->MultiCell(0, 10, 'Certificaciones', 0, 'C', 0, 1, '', '', true);

                $pdf->Ln(20);
                $pdf->SetFont('helvetica', '', 12);

                if ($certificaciones_laft_row['ISO_9001'] == 1) {
                    $pdf->MultiCell(0, 10, 'ISO 9001: Sí', 0, 'L', 0, 1, '', '', true);
                } else {
                    $pdf->MultiCell(0, 10, 'ISO 9001: No', 0, 'L', 0, 1, '', '', true);
                }

                if ($certificaciones_laft_row['ISO_14001'] == 1) {
                    $pdf->MultiCell(0, 10, 'ISO 14001: Sí', 0, 'L', 0, 1, '', '', true);
                } else {
                    $pdf->MultiCell(0, 10, 'ISO 14001: No', 0, 'L', 0, 1, '', '', true);
                }

                if ($certificaciones_laft_row['ISO_45001'] == 1) {
                    $pdf->MultiCell(0, 10, 'ISO 45001: Sí', 0, 'L', 0, 1, '', '', true);
                } else {
                    $pdf->MultiCell(0, 10, 'ISO 45001: No', 0, 'L', 0, 1, '', '', true);
                }

                if ($certificaciones_laft_row['BASC'] == 1) {
                    $pdf->MultiCell(0, 10, 'BASC: Sí', 0, 'L', 0, 1, '', '', true);
                } else {
                    $pdf->MultiCell(0, 10, 'BASC: No', 0, 'L', 0, 1, '', '', true);
                }

                if ($certificaciones_laft_row['OEA'] == 1) {
                    $pdf->MultiCell(0, 10, 'OEA: Sí', 0, 'L', 0, 1, '', '', true);
                } else {
                    $pdf->MultiCell(0, 10, 'OEA: No', 0, 'L', 0, 1, '', '', true);
                }

                if ($certificaciones_laft_row['otro_certificacion'] !== null) {
                    $pdf->MultiCell(0, 10, 'Otro: ' . $certificaciones_laft_row['otro_certificacion'], 0, 'L', 0, 1, '', '', true);
                }

                $pdf->Ln(10);
            }
        }
    } elseif ($row['tipo_persona_laft'] == 'Natural') {
        $pdf->MultiCell(0, 10, 'Nombre(s): ' . $row['nombres_persona_natural'], 0, 'L', 0, 1, '', '', true);
        $pdf->MultiCell(0, 10, 'Apellido(s): ' . $row['apellidos_persona_natural'], 0, 'L', 0, 1, '', '', true);
        $pdf->MultiCell(0, 10, 'Tipo de Identificación: ' . $row['tipo_identificacion_persona_natural'], 0, 'L', 0, 1, '', '', true);
        $pdf->MultiCell(0, 10, 'Número Identificación: ' . $row['numero_identificacion_persona_natural'], 0, 'L', 0, 1, '', '', true);
        $pdf->MultiCell(0, 10, 'País: ' . $row['pais'], 0, 'L', 0, 1, '', '', true);
        $pdf->MultiCell(0, 10, 'Departamento: ' . $row['departamento_persona_natural'], 0, 'L', 0, 1, '', '', true);
        $pdf->MultiCell(0, 10, 'Ciudad: ' . $row['ciudad_persona_natural'], 0, 'L', 0, 1, '', '', true);
        $pdf->MultiCell(0, 10, 'Teléfono: ' . $row['indicativo_persona_natural'] . ' ' . $row['telefono_persona_natural'], 0, 'L', 0, 1, '', '', true);
        $pdf->MultiCell(0, 10, 'Correo: ' . $row['correo_electronico_persona_natural'], 0, 'L', 0, 1, '', '', true);
        $pdf->MultiCell(0, 10, 'Sector Económico: ' . $row['sector_economico_persona_natural'], 0, 'L', 0, 1, '', '', true);
        if ($row['requiere_permiso_licencia_operar'] == 1) {
            $pdf->MultiCell(0, 10, 'Requiere Permiso/Licencia Operar: Sí', 0, 'L', 0, 1, '', '', true);
        } else {
            $pdf->MultiCell(0, 10, 'Requiere Permiso/Licencia Operar: No', 0, 'L', 0, 1, '', '', true);
        }

        if ($row['condicion_pago'] != 'Otro') {
            $pdf->MultiCell(0, 10, 'Condición de Pago: ' . $row['condicion_pago'] . ' días', 0, 'L', 0, 1, '', '', true);
        } else {
            $pdf->MultiCell(0, 10, 'Condición de Pago: ' . $row['cuantos_dias_condicion_pago'] . ' días', 0, 'L', 0, 1, '', '', true);
        }
    }


    $pdf->SetFont('helvetica', 'B', 14);

    $pdf->Ln(20);
    if (isset($row['pep_info']) && count($row['pep_info']) > 0) {
        foreach ($row['pep_info'] as $pep_info_row) {
            if ($pdf->GetY() > 250) {
                $pdf->AddPage();
                $pdf->SetY(50);
            }
            $pdf->SetFont('helvetica', 'B', 14);

            $pdf->MultiCell(0, 10, 'PEP Información General', 0, 'C', 0, 1, '', '', true);

            $pdf->Ln(20);
            $pdf->SetFont('helvetica', '', 12);

            if ($pep_info_row['maneja_o_ha_manejado_recursos_publicos'] == 1) {
                $pdf->MultiCell(0, 10, 'Maneja o ha Manejado Recursos Públicos: Sí', 0, 'L', 0, 1, '', '', true);
            } else {
                $pdf->MultiCell(0, 10, 'Maneja o ha Manejado Recursos Públicos: No', 0, 'L', 0, 1, '', '', true);
            }

            if ($pep_info_row['tiene_o_ha_tenido_cargo_publico'] == 1) {
                $pdf->MultiCell(0, 10, 'Tiene o ha Tenido Cargo Público: Sí', 0, 'L', 0, 1, '', '', true);
            } else {
                $pdf->MultiCell(0, 10, 'Tiene o ha Tenido Cargo Público: No', 0, 'L', 0, 1, '', '', true);
            }

            if ($pep_info_row['ocupa_o_ha_ocupado_cargo_publico_organizaciones_gubernamentales'] == 1) {
                $pdf->MultiCell(0, 10, 'Ocupa o ha Ocupado Cargo Público en Organizaciones Gubernamentales: Sí', 0, 'L', 0, 1, '', '', true);
            } else {
                $pdf->MultiCell(0, 10, 'Ocupa o ha Ocupado Cargo Público en Organizaciones Gubernamentales: No', 0, 'L', 0, 1, '', '', true);
            }

            if ($pep_info_row['ocupa_o_ha_ocupado_cargo_publico_pais_diferente_colombia'] == 1) {
                $pdf->MultiCell(0, 10, 'Ocupa o ha Ocupado Cargo Público en un País Diferente a Colombia: Sí', 0, 'L', 0, 1, '', '', true);
            } else {
                $pdf->MultiCell(0, 10, 'Ocupa o ha Ocupado Cargo Público en un País Diferente a Colombia: No', 0, 'L', 0, 1, '', '', true);
            }

            $pdf->Ln(10);
        }
    }

    $pdf->SetFont('helvetica', 'B', 14);
    if (isset($row['pep']) && count($row['pep']) > 0) {

        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->MultiCell(0, 10, 'PEP', 0, 'C', 0, 1, '', '', true);
        $pdf->Ln(10);

        foreach ($row['pep'] as $pep_row) {
            if ($pdf->GetY() > 250) {
                $pdf->AddPage();
                $pdf->SetY(50);
            }
            $pdf->Ln(10);
            $pdf->SetFont('helvetica', '', 12);
            $pdf->MultiCell(0, 10, 'Nombre(s): ' . $pep_row['nombre_pep'], 0, 'L', 0, 1, '', '', true);
            $pdf->MultiCell(0, 10, 'Tipo de Identificación: ' . $pep_row['tipo_documento_pep'], 0, 'L', 0, 1, '', '', true);
            $pdf->MultiCell(0, 10, 'Número Identificación: ' . $pep_row['numero_identificacion_pep'], 0, 'L', 0, 1, '', '', true);
            $pdf->MultiCell(0, 10, 'Cargo que Ocupa y Cataloga como PEP: ' . $pep_row['cargo_ocupa_ocupo_cataloga_pep'], 0, 'L', 0, 1, '', '', true);
            $pdf->MultiCell(0, 10, 'Desde: ' . $pep_row['desde_cuando_pep'], 0, 'L', 0, 1, '', '', true);
            $pdf->MultiCell(0, 10, 'Hasta: ' . $pep_row['hasta_cuando_pep'], 0, 'L', 0, 1, '', '', true);

            $pdf->Ln(5);
            $pdf->SetDrawColor(0, 0, 0);
            $pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
            $pdf->Ln(10);
        }
    }

    $pdf->SetFont('helvetica', 'B', 14);
    $pdf->MultiCell(0, 10, 'Autorizaciones y Declaraciones', 0, 'C', 0, 1, '', '', true);

    $pdf->Ln(10);
    $pdf->SetFont('helvetica', '', 12);

    if ($row['declaracion_origen_fondos_informacion'] == 1) {
        $pdf->MultiCell(0, 10, 'Acepta Declaración Origenes de Fondos: Sí', 0, 'L', 0, 1, '', '', true);
    } else {
        $pdf->MultiCell(0, 10, 'Acepta Declaración Origenes de Fondos: No', 0, 'L', 0, 1, '', '', true);
    }

    if ($row['autorizacion_proteccion_datos'] == 1) {
        $pdf->MultiCell(0, 10, 'Autoriza Protección de Datos: Sí', 0, 'L', 0, 1, '', '', true);
    } else {
        $pdf->MultiCell(0, 10, 'Autoriza Protección de Datos: No', 0, 'L', 0, 1, '', '', true);
    }

    if ($row['declaracion_etica'] == 1) {
        $pdf->MultiCell(0, 10, 'Acepta Declaración Ética: Sí', 0, 'L', 0, 1, '', '', true);
    } else {
        $pdf->MultiCell(0, 10, 'Acepta Declaración Ética: No', 0, 'L', 0, 1, '', '', true);
    }

    $pdf->Ln(10);
}

$base_directory = realpath(__DIR__ . '/../../documents');
$target_directory = $base_directory . '/' . $Id_proveedor_laft . '/LAFT';

if ($base_directory === false) {
    die("El directorio base no es válido.");
}

$fecha_actual = date('Y-m-d');

$query = "SELECT ultima_actualizacion_laft FROM laft WHERE Id_proveedor_laft = ?";
$stmt = $conexion->prepare($query);
$stmt->bind_param("i", $Id_proveedor_laft);
$stmt->execute();
$stmt->bind_result($ultima_actualizacion_laft);
$stmt->fetch();
$stmt->close();

if ($fecha_actual !== $ultima_actualizacion_laft) {
    $target_directory .= '/' . $fecha_actual;
    if (!is_dir($target_directory)) {
        if (!mkdir($target_directory, 0777, true)) {
            die("No se pudo crear el directorio: $target_directory");
        }
    }
} else {
    $target_directory .= '/' . $fecha_actual;
    if (!is_dir($target_directory)) {
        if (!mkdir($target_directory, 0777, true)) {
            die("No se pudo crear el directorio: $target_directory");
        }
    }
}

$save_path = $target_directory . '/LAFT.pdf';

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

        $consultaLaft = $conexion->prepare("SELECT * FROM proveedores 
                                    INNER JOIN laft ON laft.Id_proveedor_laft = proveedores.Id_proveedor
                                    WHERE Id_proveedor = ?");
        $consultaLaft->bind_param("s", $Id_proveedor_laft);
        $consultaLaft->execute();
        $resultado = $consultaLaft->get_result();

        if ($resultado && $resultado->num_rows > 0) {
            $row = $resultado->fetch_assoc();
            $correo_negociador = $row['correo_negociador'];
        } else {
            $Id_laft = null;
        }

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
<<<<<<< HEAD
            $mail->addAddress('laura.gallego@hacebwhirlpool.com');
=======
            $mail->addAddress('administrador.smartcenter@hacebwhirlpool.com');
>>>>>>> 8fe25a02a378af3db1c5f09c74bddd125a144800
            $mail->addAddress($correo_negociador);
            $mail->isHTML(true);
            $mail->Subject = 'Nuevo Registro LAFT';
            $logoUrl = "https://dattics.com/wp-content/uploads/2022/03/Haceb-Whirlpool-Industrial.jpg";
            $mail->Body = '
            <div style="border-radius:10px; border: 1px solid #cccccc; max-width: 100%; max-height: 100%; margin-top: 50px; margin-left: auto; margin-right: auto; text-align: center; padding: 20px;">
                <div style="text-align: center;">
                    <div style="display: inline-block; border-radius: 10px; max-width: 200px; padding: 10px;">
                        <img src="' . $logoUrl . '" alt="Logo Empresa" style="max-width: 100%; height: auto; border-radius: 50%; border: 1px solid #cccccc;">
                    </div>
                </div>
            <h4 style="margin-top: 20px;">' . $nombre_proveedor . ' ha Registrado el Formato de Vinculación.</h4>
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
