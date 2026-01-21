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

$consultarDocumentos = mysqli_query($conexion, "SELECT * FROM laft
                                                    LEFT JOIN laft_documentos ON laft.Id_laft = laft_documentos.Id_laft_documentos
                                                    WHERE Id_laft = '$Id_laft'");

if (isset($_POST['isconsulta']) && $_POST['isconsulta'] === "true") {

    if (mysqli_num_rows($consultarDocumentos) > 0) {

        $documentos = [];
        while ($row = mysqli_fetch_assoc($consultarDocumentos)) {
            $documentos[] = $row;
        }

        $response = [
            'success' => true,
            'documentos' => $documentos
        ];
    } else {
        $response = [
            'success' => false,
            'message' => 'No se encontraron registros'
        ];
    }
} else {

    if (isset($_FILES['RUT']) && $_FILES['RUT']['error'] == 0) {
        $RUT = $_FILES['RUT'];
    }

    if (isset($_FILES['camara_comercio']) && $_FILES['camara_comercio']['error'] == 0) {
        $camara_comercio = $_FILES['camara_comercio'];
    }

    if (isset($_FILES['copia_cedula_representante_legal']) && $_FILES['copia_cedula_representante_legal']['error'] == 0) {
        $copia_cedula_representante_legal = $_FILES['copia_cedula_representante_legal'];
    }

    if (isset($_FILES['certificacion_bancaria']) && $_FILES['certificacion_bancaria']['error'] == 0) {
        $certificacion_bancaria = $_FILES['certificacion_bancaria'];
    }

    if (isset($_FILES['RUB']) && $_FILES['RUB']['error'] == 0) {
        $RUB = $_FILES['RUB'];
    }

    if (isset($_FILES['certificado_afiliacion']) && $_FILES['certificado_afiliacion']['error'] == 0) {
        $certificado_afiliacion = $_FILES['certificado_afiliacion'];
    }

    if (isset($_FILES['carta_personas_cargo']) && $_FILES['carta_personas_cargo']['error'] == 0) {
        $carta_personas_cargo = $_FILES['carta_personas_cargo'];
    }

    if (isset($_POST['portafolioServicios']) && $_POST['portafolioServicios'] == 1) {
        if (isset($_POST['tipo_portafolio'])) {
            if ($_POST['tipo_portafolio'] == "Url") {

                $is_url_documento_laft = true;
                $tipo_documento_laft = "PDF Portafolio Servicios";
                $urlPortafolio = $_POST['urlPortafolio'];

                $stmt_verificar = $conexion->prepare("SELECT documento_laft FROM laft_documentos WHERE tipo_documento_laft = ? AND Id_laft_documentos = ?");
                $stmt_verificar->bind_param('ss', $tipo_documento_laft, $Id_laft);
                $stmt_verificar->execute();
                $resultado = $stmt_verificar->get_result();

                if ($fila = $resultado->fetch_assoc()) {
                    $stmt_actualizar = $conexion->prepare("UPDATE laft_documentos SET documento_laft = ?, is_url_documento_laft = ? WHERE tipo_documento_laft = ? AND Id_laft_documentos = ?");
                    $stmt_actualizar->bind_param('ssss', $urlPortafolio, $is_url_documento_laft, $tipo_documento_laft, $Id_laft);
                    if (!$stmt_actualizar->execute()) {
                        return json_encode(['success' => false, 'message' => 'Error al actualizar el nombre del archivo en la base de datos.']);
                    }
                } else {
                    $stmt_insertar = $conexion->prepare("INSERT INTO laft_documentos (tipo_documento_laft, is_url_documento_laft, documento_laft, Id_laft_documentos) VALUES (?, ?, ?, ?)");
                    $stmt_insertar->bind_param('siss', $tipo_documento_laft, $is_url_documento_laft, $urlPortafolio, $Id_laft);
                    if (!$stmt_insertar->execute()) {
                        return json_encode(['success' => false, 'message' => 'Error al guardar el nombre del archivo en la base de datos.']);
                    }
                }
            } elseif ($_POST['tipo_portafolio'] == "PDF") {
                if (isset($_FILES['pdf_portafolio']) && $_FILES['pdf_portafolio']['error'] == 0) {
                    $pdf_portafolio = $_FILES['pdf_portafolio'];
                }
            }
        }
    }else{
        $tipo_documento_laft = "PDF Portafolio Servicios";
        $stmt_actualizar = $conexion->prepare("DELETE FROM laft_documentos WHERE tipo_documento_laft = ? AND Id_laft_documentos = ?");
        $stmt_actualizar->bind_param('ss', $tipo_documento_laft, $Id_laft);
        if (!$stmt_actualizar->execute()) {
            return json_encode(['success' => false, 'message' => 'Error al eliminar el nombre del archivo en la base de datos.']);
        }
    }

    function cargarArchivos($conexion, $Id_proveedor_laft, $Id_laft, $fileKey, $tipo_documento_laft)
    {
        $is_url_documento_laft = false;
        
        if ($_FILES[$fileKey]['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES[$fileKey]['tmp_name'];
            $fileName = $_FILES[$fileKey]['name'];

            $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            if ($fileType !== 'pdf') {
                return json_encode(['success' => false, 'message' => 'El archivo debe estar en formato PDF.']);
            }

            $carpeta = '../../documents/' . $Id_proveedor_laft;

            if (!file_exists($carpeta)) {
                if (!mkdir($carpeta, 0755, true)) {
                    return json_encode(['success' => false, 'message' => 'Error al crear la carpeta.']);
                }
            }

            $targetFilePath = $carpeta . '/' . $fileName;

            $stmt_verificar = $conexion->prepare("SELECT documento_laft FROM laft_documentos WHERE tipo_documento_laft = ? AND Id_laft_documentos = ?");
            $stmt_verificar->bind_param('ss', $tipo_documento_laft, $Id_laft);
            $stmt_verificar->execute();
            $resultado = $stmt_verificar->get_result();

            if ($fila = $resultado->fetch_assoc()) {
                $documentoExistente = $fila['documento_laft'];

                $documentoExistentePath = $carpeta . '/' . $documentoExistente;
                if (file_exists($documentoExistentePath)) {
                    unlink($documentoExistentePath);
                }

                $stmt_actualizar = $conexion->prepare("UPDATE laft_documentos SET documento_laft = ?, is_url_documento_laft = ? WHERE tipo_documento_laft = ? AND Id_laft_documentos = ?");
                $stmt_actualizar->bind_param('siss', $targetFilePath, $is_url_documento_laft, $tipo_documento_laft, $Id_laft);
                if (!$stmt_actualizar->execute()) {
                    return json_encode(['success' => false, 'message' => 'Error al actualizar el nombre del archivo en la base de datos.']);
                }
            } else {
                $stmt_insertar = $conexion->prepare("INSERT INTO laft_documentos (tipo_documento_laft, is_url_documento_laft, documento_laft, Id_laft_documentos) VALUES (?, ?, ?, ?)");
                $stmt_insertar->bind_param('siss', $tipo_documento_laft, $is_url_documento_laft, $targetFilePath, $Id_laft);
                if (!$stmt_insertar->execute()) {
                    return json_encode(['success' => false, 'message' => 'Error al guardar el nombre del archivo en la base de datos.']);
                }
            }

            if (!move_uploaded_file($fileTmpPath, $targetFilePath)) {
                return json_encode(['success' => false, 'message' => 'Error al mover el archivo.']);
            }
        }
    }

    if (isset($pdf_portafolio)) {
        cargarArchivos($conexion, $Id_proveedor_laft, $Id_laft, 'pdf_portafolio', 'PDF Portafolio Servicios');
    }

    if (isset($RUT)) {
        cargarArchivos($conexion, $Id_proveedor_laft, $Id_laft, 'RUT', 'RUT');
    }

    if (isset($camara_comercio)) {
        cargarArchivos($conexion, $Id_proveedor_laft, $Id_laft, 'camara_comercio', 'Cámara de Comercio');
    }

    if (isset($copia_cedula_representante_legal)) {
        cargarArchivos($conexion, $Id_proveedor_laft, $Id_laft, 'copia_cedula_representante_legal', 'Copia Cédula Rep. Legal');
    }

    if (isset($certificacion_bancaria)) {
        cargarArchivos($conexion, $Id_proveedor_laft, $Id_laft, 'certificacion_bancaria', 'Certificación Bancaria');
    }

    if (isset($RUB)) {
        cargarArchivos($conexion, $Id_proveedor_laft, $Id_laft, 'RUB', 'RUB');
    }

    if (isset($certificado_afiliacion)) {
        cargarArchivos($conexion, $Id_proveedor_laft, $Id_laft, 'certificado_afiliacion', 'Certificado de Afiliación');
    }

    if (isset($carta_personas_cargo)) {
        cargarArchivos($conexion, $Id_proveedor_laft, $Id_laft, 'carta_personas_cargo', 'Carta que Certifica no Tiene Personas a Cargo');
    }

    $consultaProveedor = $conexion->prepare("SELECT * FROM proveedores WHERE Id_proveedor = ?");
    $consultaProveedor->bind_param("s", $Id_proveedor_laft);
    $consultaProveedor->execute();
    $resultadoProveedor = $consultaProveedor->get_result();
    $row = $resultadoProveedor->fetch_assoc();
    $formulario_ambiental = $row['formulario_ambiental'];

    $response = [
        'success' => true,
        'formulario_ambiental' => $formulario_ambiental
    ];
}

echo json_encode($response);
