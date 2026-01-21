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
                                                    WHERE Id_laft = '$Id_laft' AND tipo_documento_laft = 'Carta Beneficiarios Finales'");


if (isset($_POST['isconsulta']) && $_POST['isconsulta'] === "true") {

    if (mysqli_num_rows($consultarDocumentos) > 0) {

        $documentos = [];
        while ($row = mysqli_fetch_assoc($consultarDocumentos)) {
            $documentos[] = $row;
        }

        $response = [
            'success' => true,
            'cartabeneficiariosFinales' => $documentos
        ];
    } else {
        $response = [
            'success' => false,
            'message' => 'No se encontraron registros'
        ];
    }
} else {
    if (isset($_FILES['carta_beneficiarios_finales']) && $_FILES['carta_beneficiarios_finales']['error'] == 0) {
        $carta_beneficiarios_finales = $_FILES['carta_beneficiarios_finales'];
    }

    function cargarArchivos($conexion, $Id_proveedor_laft, $Id_laft, $fileKey, $tipo_documento_laft)
    {
        $is_url_documento_laft = false;

        if ($_FILES[$fileKey]['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES[$fileKey]['tmp_name'];
            $fileType = strtolower(pathinfo($_FILES[$fileKey]['name'], PATHINFO_EXTENSION));

            if ($fileType !== 'pdf') {
                return json_encode(['success' => false, 'message' => 'El archivo debe estar en formato PDF.']);
            }

            $carpeta = '../../documents/' . $Id_proveedor_laft;
            if (!file_exists($carpeta)) {
                if (!mkdir($carpeta, 0755, true)) {
                    return json_encode(['success' => false, 'message' => 'Error al crear la carpeta.']);
                }
            }

            // Nombre fijo del archivo
            $nombreArchivoFijo = 'Carta_Beneficiarios_Finales.pdf';
            $targetFilePath = $carpeta . '/' . $nombreArchivoFijo;

            // Verificar si ya existe en BD
            $stmt_verificar = $conexion->prepare("SELECT documento_laft FROM laft_documentos WHERE tipo_documento_laft = ? AND Id_laft_documentos = ?");
            $stmt_verificar->bind_param('ss', $tipo_documento_laft, $Id_laft);
            $stmt_verificar->execute();
            $resultado = $stmt_verificar->get_result();

            if ($fila = $resultado->fetch_assoc()) {
                $documentoExistente = $fila['documento_laft'];
                if (file_exists($documentoExistente)) {
                    unlink($documentoExistente);
                }

                // Actualizar ruta en BD
                $stmt_actualizar = $conexion->prepare("UPDATE laft_documentos SET documento_laft = ?, is_url_documento_laft = ? WHERE tipo_documento_laft = ? AND Id_laft_documentos = ?");
                $stmt_actualizar->bind_param('siss', $targetFilePath, $is_url_documento_laft, $tipo_documento_laft, $Id_laft);
                if (!$stmt_actualizar->execute()) {
                    return json_encode(['success' => false, 'message' => 'Error al actualizar el nombre del archivo en la base de datos.']);
                }
            } else {
                // Insertar si no existe
                $stmt_insertar = $conexion->prepare("INSERT INTO laft_documentos (tipo_documento_laft, is_url_documento_laft, documento_laft, Id_laft_documentos) VALUES (?, ?, ?, ?)");
                $stmt_insertar->bind_param('siss', $tipo_documento_laft, $is_url_documento_laft, $targetFilePath, $Id_laft);
                if (!$stmt_insertar->execute()) {
                    return json_encode(['success' => false, 'message' => 'Error al guardar el nombre del archivo en la base de datos.']);
                }
            }

            // Mover archivo con nombre fijo
            if (!move_uploaded_file($fileTmpPath, $targetFilePath)) {
                return json_encode(['success' => false, 'message' => 'Error al mover el archivo.']);
            }
        }
    }

    if (isset($carta_beneficiarios_finales)) {
        cargarArchivos($conexion, $Id_proveedor_laft, $Id_laft, 'carta_beneficiarios_finales', 'Carta Beneficiarios Finales');
    }

    $response = ['success' => true];
}

echo json_encode($response);
?>