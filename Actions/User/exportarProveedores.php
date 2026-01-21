<?php

include "../../ConexionBD/conexion.php";
require '../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use \PhpOffice\PhpSpreadsheet\Style\Fill;
use \PhpOffice\PhpSpreadsheet\Style\Font;


$sql = "SELECT 
    proveedores.*, 
    laft.*, 
    laft_persona_juridica.*, 
    laft_persona_natural.*, 
    pais_juridica.pais AS descripcion_pais_juridica, 
    pais_natural.pais AS descripcion_pais_natural
FROM 
    proveedores
    INNER JOIN laft ON proveedores.Id_proveedor = laft.Id_proveedor_laft
    LEFT JOIN laft_persona_juridica ON laft.Id_laft = laft_persona_juridica.Id_laft_persona_juridica
    LEFT JOIN pais AS pais_juridica ON laft_persona_juridica.Id_pais_persona_juridica = pais_juridica.Id_pais
    LEFT JOIN laft_persona_natural ON laft.Id_laft = laft_persona_natural.id_laft_persona_natural
    LEFT JOIN pais AS pais_natural ON laft_persona_natural.Id_pais_persona_natural = pais_natural.Id_pais
WHERE proveedores.proveedor_aprobado = 1;
";

$resultado = mysqli_query($conexion, $sql);

$excel = new Spreadsheet();
$hojaActiva = $excel->getActiveSheet();
$hojaActiva->setTitle("Proveedores");
$excel->getActiveSheet()->getStyle('A1:I1')->getFill()
    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    ->getStartColor()->setARGB('0B426E');
$excel->getActiveSheet()->getStyle('A1:I1950')->getAlignment()
    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$excel->getActiveSheet()->getStyle('A1:I1')->getFont()->setBold(true);
$excel->getActiveSheet()->getStyle('A1:I1')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
$excel->getActiveSheet()->getStyle('A1:I1')->getAlignment()
    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
$excel->getActiveSheet()->getRowDimension(1)->setRowHeight(50);


/* FIN ESTILOS DE LA HOJA::: */

$hojaActiva->setCellValue('A1', 'Tipo Persona');
$hojaActiva->getColumnDimension('A')->setWidth(13);
$hojaActiva->setCellValue('B1', 'N° Acreedor');
$hojaActiva->getColumnDimension('B')->setWidth(13);
$hojaActiva->setCellValue('C1', 'NIT');
$hojaActiva->getColumnDimension('C')->setWidth(13);
$hojaActiva->setCellValue('D1', 'Dígito Verificación');
$hojaActiva->getColumnDimension('D')->setWidth(17);
$hojaActiva->setCellValue('E1', 'Razón Social/Nombre');
$hojaActiva->getColumnDimension('E')->setWidth(50);
$hojaActiva->setCellValue('F1', 'Dirección');
$hojaActiva->getColumnDimension('F')->setWidth(27);
$hojaActiva->setCellValue('G1', 'País');
$hojaActiva->getColumnDimension('G')->setWidth(25);
$hojaActiva->setCellValue('H1', 'Departamento');
$hojaActiva->getColumnDimension('H')->setWidth(25);
$hojaActiva->setCellValue('I1', 'Ciudad');
$hojaActiva->getColumnDimension('I')->setWidth(25);


$FILA = 2;
while ($rows = $resultado->fetch_assoc()) {

    $hojaActiva->setCellValue('A' . $FILA, $rows['tipo_persona_laft']);
    $hojaActiva->setCellValue('B' . $FILA, $rows['numero_acreedor']);

    if ($rows['numero_identificacion_persona_juridica'] != null) {
        $hojaActiva->setCellValue('C' . $FILA, $rows['numero_identificacion_persona_juridica']);
    } else {
        $hojaActiva->setCellValue('C' . $FILA, $rows['numero_identificacion_persona_natural']);
    }

    if ($rows['digito_verificacion'] != null) {
        $hojaActiva->setCellValue('D' . $FILA, $rows['digito_verificacion']);
    } else {
        $hojaActiva->setCellValue('D' . $FILA, 'N/A');
    }

    if ($rows['razon_social_persona_juridica'] != null) {
        $hojaActiva->setCellValue('E' . $FILA, $rows['razon_social_persona_juridica']);
    } else {
        $hojaActiva->setCellValue('E' . $FILA, $rows['nombres_persona_natural']) . " " . $rows['apellidos_persona_natural'];
    }

    if ($rows['direccion_persona_juridica'] != null) {
        $hojaActiva->setCellValue('F' . $FILA, $rows['direccion_persona_juridica']);
    } else {
        $hojaActiva->setCellValue('F' . $FILA, $rows['direccion_persona_natural']);
    }

    if ($rows['descripcion_pais_juridica'] != null) {
        $hojaActiva->setCellValue('G' . $FILA, $rows['descripcion_pais_juridica']);
    } else {
        $hojaActiva->setCellValue('G' . $FILA, $rows['descripcion_pais_natural']);
    }

    if ($rows['departamento_persona_juridica'] != null) {
        $hojaActiva->setCellValue('H' . $FILA, $rows['departamento_persona_juridica']);
    } else {
        $hojaActiva->setCellValue('H' . $FILA, $rows['departamento_persona_natural']);
    }

    if ($rows['ciudad_persona_juridica'] != null) {
        $hojaActiva->setCellValue('I' . $FILA, $rows['ciudad_persona_juridica']);
    } else {
        $hojaActiva->setCellValue('I' . $FILA, $rows['ciudad_persona_natural']);
    }

    $FILA++;
}


header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Proveedores HWI.xlsx"');
header('Cache-Control: max-age=0');

$writer = IOFactory::createWriter($excel, 'Xlsx');
$writer->save('php://output');
exit;
