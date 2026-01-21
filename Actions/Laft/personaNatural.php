<?php
session_start();
include "../../ConexionBD/conexion.php";

$Id_proveedor_laft = $_SESSION['id_proveedor_usuarios'];
$consultarlaft = mysqli_query($conexion, "SELECT * FROM laft WHERE Id_proveedor_laft = '$Id_proveedor_laft'");
$row = mysqli_fetch_assoc($consultarlaft);
$Id_laft = $row['Id_laft'];

$consultarPersonaNatural = mysqli_query($conexion, "SELECT * FROM laft
                    INNER JOIN laft_persona_natural ON laft.Id_laft = laft_persona_natural.Id_laft_persona_natural
                    LEFT JOIN laft_documentos ON laft.Id_laft = laft_documentos.Id_laft_documentos
                    WHERE id_laft_persona_natural  = '$Id_laft'");

if (isset($_POST['isconsulta']) && $_POST['isconsulta'] === "true") {

    if (mysqli_num_rows($consultarPersonaNatural) > 0) {
        $personaNatural = mysqli_fetch_assoc($consultarPersonaNatural);
        $response = [
            'success' => true,
            'personaNatural' => $personaNatural
        ];
    } else {
        $response = [
            'success' => false,
            'message' => 'No record found'
        ];
    }

} else {

    $nombres_persona_natural = $_POST['nombres_persona_natural'];
    $apellidos_persona_natural = $_POST['apellidos_persona_natural'];
    $tipo_identificacion_persona_natural = $_POST['tipo_identificacion_persona_natural'];
    $numero_identificacion_persona_natural = $_POST['numero_identificacion_persona_natural'];
    $Id_pais_persona_natural = $_POST['Id_pais_persona_natural'];
    $departamento_persona_natural = $_POST['departamento_persona_natural'];
    $ciudad_persona_natural = $_POST['ciudad_persona_natural'];
    $direccion_persona_natural = $_POST['direccion_persona_natural'];
    $indicativo_persona_natural = $_POST['indicativo_persona_natural'];
    $telefono_persona_natural = $_POST['telefono_persona_natural'];
    $correo_electronico_persona_natural = $_POST['correo_electronico_persona_natural'];
    $sector_economico_persona_natural = $_POST['sector_economico_persona_natural'];
    $condicion_pago = $_POST['condicion_pago'];
    $cuantos_dias_condicion_pago = null;
    if ($condicion_pago == "Otro") {
        $cuantos_dias_condicion_pago = $_POST['cuantos_dias_condicion_pago'];
    }

    $requiere_permiso_licencia_operar = $_POST['requiere_permiso_licencia_operar'];
    $adjuntoPermisoLicencia = null;

    function eliminarDocumentos($conexion, $Id_laft, $tipo_documento_laft)
    {
        $consultarDocumentos = "SELECT documento_laft FROM laft_documentos WHERE Id_laft_documentos = ? AND tipo_documento_laft = ?";
        $stmt = $conexion->prepare($consultarDocumentos);
        $stmt->bind_param("is", $Id_laft, $tipo_documento_laft);
        $stmt->execute();
        $result = $stmt->get_result();

        $filesToDelete = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $filesToDelete[] = $row['documento_laft'];
            }
        }
        $stmt->close();

        foreach ($filesToDelete as $filePath) {
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        $deleteDocuments = $conexion->prepare("DELETE FROM laft_documentos WHERE Id_laft_documentos = ? AND tipo_documento_laft = ?");
        $deleteDocuments->bind_param("is", $Id_laft, $tipo_documento_laft);

        if ($deleteDocuments->execute()) {
            $response = ['success' => true];
        } else {
            $response = ['success' => false, 'message' => 'Delete failed'];
        }
        $deleteDocuments->close();
    }

    function cargarArchivos($conexion, $Id_proveedor_laft, $Id_laft, $fileKey, $tipo_documento_laft)
    {
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

                $stmt_actualizar = $conexion->prepare("UPDATE laft_documentos SET documento_laft = ? WHERE tipo_documento_laft = ? AND Id_laft_documentos = ?");
                $stmt_actualizar->bind_param('sss', $targetFilePath, $tipo_documento_laft, $Id_laft);
                if (!$stmt_actualizar->execute()) {
                    return json_encode(['success' => false, 'message' => 'Error al actualizar el nombre del archivo en la base de datos.']);
                }
            } else {
                $stmt_insertar = $conexion->prepare("INSERT INTO laft_documentos (tipo_documento_laft, documento_laft, Id_laft_documentos) VALUES (?, ?, ?)");
                $stmt_insertar->bind_param('sss', $tipo_documento_laft, $targetFilePath, $Id_laft);
                if (!$stmt_insertar->execute()) {
                    return json_encode(['success' => false, 'message' => 'Error al guardar el nombre del archivo en la base de datos.']);
                }
            }

            if (!move_uploaded_file($fileTmpPath, $targetFilePath)) {
                return json_encode(['success' => false, 'message' => 'Error al mover el archivo.']);
            }
        }
    }

    if($requiere_permiso_licencia_operar == 0){
        eliminarDocumentos($conexion, $Id_laft, 'requiere_permiso_licencia');
    }else{
        cargarArchivos($conexion, $Id_proveedor_laft, $Id_laft, 'doc_permiso_licencia_operar', 'requiere_permiso_licencia');
    }

    if (mysqli_num_rows($consultarPersonaNatural) > 0) {
        $actualizarPersonaNatural = $conexion->prepare("UPDATE laft_persona_natural 
                                                SET nombres_persona_natural = ?, apellidos_persona_natural = ?,
                                                    tipo_identificacion_persona_natural = ?, numero_identificacion_persona_natural = ?,
                                                    direccion_persona_natural = ?, ciudad_persona_natural = ?,
                                                    departamento_persona_natural = ?, Id_pais_persona_natural = ?,
                                                    indicativo_persona_natural = ?, telefono_persona_natural = ?,
                                                    correo_electronico_persona_natural = ?, sector_economico_persona_natural = ?,
                                                    requiere_permiso_licencia_operar = ?, condicion_pago = ?, cuantos_dias_condicion_pago = ?
                                                WHERE id_laft_persona_natural = ?");

        $actualizarPersonaNatural->bind_param(
            "sssisssisisssssi",
            $nombres_persona_natural,
            $apellidos_persona_natural,
            $tipo_identificacion_persona_natural,
            $numero_identificacion_persona_natural,
            $direccion_persona_natural,
            $ciudad_persona_natural,
            $departamento_persona_natural,
            $Id_pais_persona_natural,
            $indicativo_persona_natural,
            $telefono_persona_natural,
            $correo_electronico_persona_natural,
            $sector_economico_persona_natural,
            $requiere_permiso_licencia_operar,
            $condicion_pago,
            $cuantos_dias_condicion_pago,
            $Id_laft
        );
        if ($actualizarPersonaNatural->execute()) {
            $response = ['success' => true];
        } else {
            $response = ['success' => false, 'message' => 'Update failed'];
        }
    } else {
        $insertarPersonaNatural = $conexion->prepare("INSERT INTO laft_persona_natural 
                                                (nombres_persona_natural, apellidos_persona_natural,
                                                tipo_identificacion_persona_natural, numero_identificacion_persona_natural, 
                                                direccion_persona_natural, ciudad_persona_natural, departamento_persona_natural,
                                                Id_pais_persona_natural, indicativo_persona_natural, telefono_persona_natural, 
                                                correo_electronico_persona_natural, sector_economico_persona_natural,
                                                requiere_permiso_licencia_operar, condicion_pago, cuantos_dias_condicion_pago,
                                                id_laft_persona_natural)
                                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $insertarPersonaNatural->bind_param(
            "sssisssisissssis",
            $nombres_persona_natural,
            $apellidos_persona_natural,
            $tipo_identificacion_persona_natural,
            $numero_identificacion_persona_natural,
            $direccion_persona_natural,
            $ciudad_persona_natural,
            $departamento_persona_natural,
            $Id_pais_persona_natural,
            $indicativo_persona_natural,
            $telefono_persona_natural,
            $correo_electronico_persona_natural,
            $sector_economico_persona_natural,
            $requiere_permiso_licencia_operar,
            $condicion_pago,
            $cuantos_dias_condicion_pago,
            $Id_laft
        );

        if ($insertarPersonaNatural->execute()) {
            $response = ['success' => true];
        } else {
            $response = ['success' => false, 'message' => 'Insert failed'];
        }
    }
}

echo json_encode($response);

?>
