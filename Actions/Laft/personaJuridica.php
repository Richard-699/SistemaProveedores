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

$consultarPersonaJuridica = mysqli_query($conexion, "SELECT * FROM laft
                                                    INNER JOIN laft_persona_juridica ON laft.Id_laft = laft_persona_juridica.Id_laft_persona_juridica
                                                    LEFT JOIN laft_certificaciones ON laft_persona_juridica.Id_persona_juridica = laft_certificaciones.Id_laft_persona_juridica_certificacion
                                                    WHERE Id_laft_persona_juridica = '$Id_laft'");

$consultarDocumentos = mysqli_query($conexion, "SELECT * FROM laft
                                                    INNER JOIN laft_persona_juridica ON laft.Id_laft = laft_persona_juridica.Id_laft_persona_juridica
                                                    LEFT JOIN laft_documentos ON laft.Id_laft = laft_documentos.Id_laft_documentos
                                                    WHERE Id_laft_persona_juridica = '$Id_laft'");

if (isset($_POST['isconsulta']) && $_POST['isconsulta'] === "true") {

    if (mysqli_num_rows($consultarPersonaJuridica) > 0) {

        $personaJuridica = mysqli_fetch_assoc($consultarPersonaJuridica);

        $documentos = [];

        while ($row = mysqli_fetch_assoc($consultarDocumentos)) {
            $documentos[] = $row;
        }

        $response = [
            'success' => true,
            'personaJuridica' => $personaJuridica,
            'documentos' => $documentos
        ];
    } else {
        $response = [
            'success' => false,
            'message' => 'No se encontraron registros'
        ];
    }
} else {
    $razon_social_persona_juridica = $_POST['razon_social_persona_juridica'];
    $tipo_identificacion_persona_juridica = $_POST['tipo_identificacion_persona_juridica'];
    $otro_tipo_identificacion = null;
    if ($tipo_identificacion_persona_juridica == "null") {
        $otro_tipo_identificacion = $_POST['otro_tipo_identificacion'];
    }
    $numero_identificacion_persona_juridica = $_POST['numero_identificacion_persona_juridica'];
    $digito_verificacion = $_POST['digito_verificacion'];
    $Id_pais_persona_juridica = $_POST['Id_pais_persona_juridica'];

    $departamento_persona_juridica = null;

    if(isset($_POST['departamento_persona_juridica'])){
        $departamento_persona_juridica = $_POST['departamento_persona_juridica'];
    }

    $ciudad_persona_juridica = null;
    
    if(isset($_POST['ciudad_persona_juridica'])){
        $ciudad_persona_juridica = $_POST['ciudad_persona_juridica'];
    }
    
    $direccion_persona_juridica = $_POST['direccion_persona_juridica'];
    $indicativo_persona_juridica = $_POST['indicativo_persona_juridica'];
    $telefono_persona_juridica = $_POST['telefono_persona_juridica'];
    $correo_electronico_persona_juridica = $_POST['correo_electronico_persona_juridica'];
    $codigo_ciiu_persona_juridica = $_POST['codigo_ciiu_persona_juridica'];
    $condicion_pago = $_POST['condicion_pago'];
    $cuantos_dias_condicion_pago = null;
    if ($condicion_pago == "Otro") {
        $cuantos_dias_condicion_pago = $_POST['cuantos_dias_condicion_pago'];
    }
    $requiere_permiso_licencia_operar = $_POST['requiere_permiso_licencia_operar'];
    $adjuntoPermisoLicencia = null;

    $ISO_9001 = $_POST['ISO_9001'];
    $ISO_14001 = $_POST['ISO_14001'];
    $ISO_45001 = $_POST['ISO_45001'];
    $BASC = $_POST['BASC'];
    $OEA = $_POST['OEA'];
    $otro_certificacion = $_POST['otro_certificacion'];
    if ($otro_certificacion == "") {
        $otro_certificacion = null;
    }

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

    if ($ISO_9001 == 0) {
        eliminarDocumentos($conexion, $Id_laft, 'ISO_9001');
    } else {
        cargarArchivos($conexion, $Id_proveedor_laft, $Id_laft, 'doc_ISO_9001', 'ISO_9001');
    }

    if($ISO_14001 == 0){
        eliminarDocumentos($conexion, $Id_laft, 'ISO_14001');
    }else{
        cargarArchivos($conexion, $Id_proveedor_laft, $Id_laft, 'doc_ISO_14001', 'ISO_14001');
    }

    if($ISO_45001 == 0){
        eliminarDocumentos($conexion, $Id_laft, 'ISO_45001');
    }else{
        cargarArchivos($conexion, $Id_proveedor_laft, $Id_laft, 'doc_ISO_45001', 'ISO_45001');
    }

    if($BASC == 0){
        eliminarDocumentos($conexion, $Id_laft, 'BASC');
    }else{
        cargarArchivos($conexion, $Id_proveedor_laft, $Id_laft, 'doc_BASC', 'BASC');
    }
    
    if($OEA == 0){
        eliminarDocumentos($conexion, $Id_laft, 'OEA');
    }else{
        cargarArchivos($conexion, $Id_proveedor_laft, $Id_laft, 'doc_OEA', 'OEA');
    }
    
    if($otro_certificacion == "" || $otro_certificacion == null){
        eliminarDocumentos($conexion, $Id_laft, 'Otro');
    }

    if($otro_certificacion != "" && $otro_certificacion != null){
        cargarArchivos($conexion, $Id_proveedor_laft, $Id_laft, 'doc_Otro', 'Otro');
    }

    if (mysqli_num_rows($consultarPersonaJuridica) > 0) {
        $row = mysqli_fetch_assoc($consultarPersonaJuridica);
        $Id_persona_juridica = $row['Id_persona_juridica'];

        $actualizarPersonaJuridica = $conexion->prepare("UPDATE laft_persona_juridica SET 
                                                razon_social_persona_juridica = ?, 
                                                tipo_identificacion_persona_juridica = ?,
                                                otro_tipo_identificacion = ?, 
                                                numero_identificacion_persona_juridica = ?, 
                                                digito_verificacion = ?,
                                                Id_pais_persona_juridica = ?,
                                                departamento_persona_juridica = ?,
                                                ciudad_persona_juridica = ?,
                                                direccion_persona_juridica = ?,
                                                indicativo_persona_juridica = ?,
                                                telefono_persona_juridica = ?, 
                                                correo_electronico_persona_juridica = ?,
                                                codigo_ciiu_persona_juridica = ?, 
                                                requiere_permiso_licencia_operar = ?, 
                                                condicion_pago = ?, 
                                                cuantos_dias_condicion_pago = ?
                                                WHERE Id_laft_persona_juridica = ?");
        $actualizarPersonaJuridica->bind_param(
            "ssssiissssissisis",
            $razon_social_persona_juridica,
            $tipo_identificacion_persona_juridica,
            $otro_tipo_identificacion,
            $numero_identificacion_persona_juridica,
            $digito_verificacion,
            $Id_pais_persona_juridica,
            $departamento_persona_juridica,
            $ciudad_persona_juridica,
            $direccion_persona_juridica,
            $indicativo_persona_juridica,
            $telefono_persona_juridica,
            $correo_electronico_persona_juridica,
            $codigo_ciiu_persona_juridica,
            $requiere_permiso_licencia_operar,
            $condicion_pago,
            $cuantos_dias_condicion_pago,
            $Id_laft
        );

        if ($actualizarPersonaJuridica->execute()) {
            $actualizarCertificaciones = $conexion->prepare("
                UPDATE laft_certificaciones
                SET ISO_9001 = ?, ISO_14001 = ?, ISO_45001 = ?, BASC = ?, OEA = ?, otro_certificacion = ?
                WHERE Id_laft_persona_juridica_certificacion = ?
            ");

            $actualizarCertificaciones->bind_param(
                "sssssss",
                $ISO_9001,
                $ISO_14001,
                $ISO_45001,
                $BASC,
                $OEA,
                $otro_certificacion,
                $Id_persona_juridica
            );

            if ($actualizarCertificaciones->execute()) {
                $response = ['success' => true];
            } else {
                $response = ['success' => false, 'message' => 'Error al actualizar las certificaciones.'];
            }
        } else {
            $response = ['success' => false, 'message' => 'Error al actualizar la persona jurídica.'];
        }
    } else {

        $Id_persona_juridica = uniqid();
        $Id_persona_juridica = substr(str_replace(".", "", $Id_persona_juridica), 0, 25);

        $insertarPersonaJuridica = $conexion->prepare("INSERT INTO laft_persona_juridica 
                                                (Id_persona_juridica ,
                                                razon_social_persona_juridica, tipo_identificacion_persona_juridica,
                                                otro_tipo_identificacion, numero_identificacion_persona_juridica, 
                                                digito_verificacion, Id_pais_persona_juridica,
                                                departamento_persona_juridica, ciudad_persona_juridica,
                                                direccion_persona_juridica, indicativo_persona_juridica,
                                                telefono_persona_juridica, correo_electronico_persona_juridica,
                                                codigo_ciiu_persona_juridica, requiere_permiso_licencia_operar, condicion_pago, 
                                                cuantos_dias_condicion_pago, Id_laft_persona_juridica )
                                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $insertarPersonaJuridica->bind_param(
            "sssssisssssisssssi",
            $Id_persona_juridica,
            $razon_social_persona_juridica,
            $tipo_identificacion_persona_juridica,
            $otro_tipo_identificacion,
            $numero_identificacion_persona_juridica,
            $digito_verificacion,
            $Id_pais_persona_juridica,
            $departamento_persona_juridica,
            $ciudad_persona_juridica,
            $direccion_persona_juridica,
            $indicativo_persona_juridica,
            $telefono_persona_juridica,
            $correo_electronico_persona_juridica,
            $codigo_ciiu_persona_juridica,
            $requiere_permiso_licencia_operar,
            $condicion_pago,
            $cuantos_dias_condicion_pago,
            $Id_laft
        );

        if ($insertarPersonaJuridica->execute()) {
            $insertarCertificaciones = $conexion->prepare("INSERT INTO laft_certificaciones
                                                (ISO_9001, ISO_14001, ISO_45001, BASC, OEA, otro_certificacion, 
                                                Id_laft_persona_juridica_certificacion)
                                                VALUES (?, ?, ?, ?, ?, ?, ?)");
            $insertarCertificaciones->bind_param(
                "sssssss",
                $ISO_9001,
                $ISO_14001,
                $ISO_45001,
                $BASC,
                $OEA,
                $otro_certificacion,
                $Id_persona_juridica
            );

            if ($insertarCertificaciones->execute()) {
                $response = ['success' => true];
            } else {
                $response = ['success' => false, 'message' => 'Error al insertar las certificaciones.'];
            }
        } else {
            $response = ['success' => false, 'message' => 'Error al insertar la persona jurídica.'];
        }
    }
}

echo json_encode($response);
