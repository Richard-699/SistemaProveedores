<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include('../../ConexionBD/conexion.php');

$opcion = $_POST['opcion'];
$id_proveedor = $_POST['id_proveedor'];

$consultaLaft = $conexion->prepare("SELECT * FROM proveedores 
                                    INNER JOIN laft ON laft.Id_proveedor_laft = proveedores.Id_proveedor
                                    WHERE Id_proveedor = ?");
$consultaLaft->bind_param("s", $id_proveedor);
$consultaLaft->execute();
$resultado = $consultaLaft->get_result();

if ($resultado && $resultado->num_rows > 0) {
    $row = $resultado->fetch_assoc();
    $Id_laft = $row['Id_laft'];
    $nombre_proveedor = $row['nombre_proveedor'];
    $correo_negociador = $row['correo_negociador'];
} else {
    $Id_laft = null;
}

$consultarAprobaciones = mysqli_query($conexion, "SELECT * FROM vinculacion_proveedor
                    WHERE Id_proveedor_vinculacion_proveedor = '$id_proveedor'");

$aprobaciones = mysqli_fetch_assoc($consultarAprobaciones);

if ($opcion == 'Cumplimiento') {

    $aprobado_cumplimiento = true;
    $observaciones_cumplimiento = $_POST['observaciones_cumplimiento'];

    $uploadDirectory = '../../documents/' . $id_proveedor . '/';

    $doc_Inspektor1 = $_FILES['doc_Inspektor1'];
    $doc_Inspektor2 = $_FILES['doc_Inspektor2'];

    if (!is_dir($uploadDirectory)) {
        mkdir($uploadDirectory, 0777, true);
    }

    if ($doc_Inspektor1['error'] === UPLOAD_ERR_OK) {
        $tmpName = $doc_Inspektor1['tmp_name'];
        $fileName = basename($doc_Inspektor1['name']);
        $filePath1 = $uploadDirectory . $fileName;
        move_uploaded_file($tmpName, $filePath1);
    }

    if ($doc_Inspektor2['error'] === UPLOAD_ERR_OK) {
        $tmpName = $doc_Inspektor2['tmp_name'];
        $fileName = basename($doc_Inspektor2['name']);
        $filePath2 = $uploadDirectory . $fileName;
        move_uploaded_file($tmpName, $filePath2);
    }

    $stmt = $conexion->prepare("UPDATE vinculacion_proveedor SET aprobado_cumplimiento = ?, observaciones_cumplimiento = ? WHERE Id_proveedor_vinculacion_proveedor = ?");
    $stmt->bind_param('sss', $aprobado_cumplimiento, $observaciones_cumplimiento, $id_proveedor);

    if ($stmt->execute()) {
        $response = ['success' => true];

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
            $mail->addAddress($correo_negociador);
            $mail->addAddress('ricardo.rojas@hacebwhirlpool.com');
            $mail->isHTML(true);
            $mail->Subject = 'Aprobacion Cumplimiento';
            $logoUrl = "https://dattics.com/wp-content/uploads/2022/03/Haceb-Whirlpool-Industrial.jpg";
            $mail->Body = '
            <div style="border-radius:10px; border: 1px solid #cccccc; max-width: 100%; max-height: 100%; margin-top: 50px; margin-left: auto; margin-right: auto; text-align: center; padding: 20px;">
                <div style="text-align: center;">
                    <div style="display: inline-block; border-radius: 10px; max-width: 200px; padding: 10px;">
                        <img src="' . $logoUrl . '" alt="Logo Empresa" style="max-width: 100%; height: auto; border-radius: 50%; border: 1px solid #cccccc;">
                    </div>
                </div>
            <h4 style="margin-top: 20px;">Cumplimiento ha aprobado el formato de vinculación LAFT de ' . $nombre_proveedor . '.</h4>
            <hr style="background-color: #cccccc; border: none; height: 1px; width: 100%; margin-top: 20px; margin-bottom: 20px;">
                <div style="width: 95%; text-align: justify; margin-left: auto; margin-right: auto;">
                    <div>
                        <p><span style="font-weight: bold;">Puedes continuar el proceso de aprobación en: </span><a href="https://sistema.proveedores.hacebwhirlpoolindustrial.com/SistemaProveedores/index.php">Haceb Whirlpool</a></p>
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
            echo json_encode(['success' => false, 'message' => "Error al insertar {$tipoDocumento}."]);
            exit;
        }
    } else {
        $response = ['success' => false, 'message' => 'Error al aprobar cumplimiento.'];
    }
    $stmt->close();

    if ($filePath1 !== '') {
        $tipodocumento1 = "Inspektor1";
        $stmt = $conexion->prepare("INSERT INTO laft_documentos (tipo_documento_laft, documento_laft, Id_laft_documentos) VALUES (?, ?, ?)");
        $stmt->bind_param('sss', $tipodocumento1, $filePath1, $Id_laft);
        if ($stmt->execute()) {
            $response = ['success' => true];
        } else {
            $response = ['success' => false, 'message' => 'Error al insertar doc 1.'];
        }
    }

    if ($filePath2 !== '') {
        $tipodocumento2 = "Inspektor2";
        $stmt = $conexion->prepare("INSERT INTO laft_documentos (tipo_documento_laft, documento_laft, Id_laft_documentos) VALUES (?, ?, ?)");
        $stmt->bind_param('sss', $tipodocumento2, $filePath2, $Id_laft);
        if ($stmt->execute()) {
            $response = ['success' => true];
        } else {
            $response = ['success' => false, 'message' => 'Error al insertar doc 2.'];
        }
    }
}

if ($opcion == 'Ambiental') {
    $aprobado_ambiental = true;
    $observaciones_ambiental = $_POST['observaciones_ambiental'];

    $stmt = $conexion->prepare("UPDATE vinculacion_proveedor SET aprobado_ambiental = ?, observaciones_ambiental = ? WHERE Id_proveedor_vinculacion_proveedor = ?");
    $stmt->bind_param('sss', $aprobado_ambiental, $observaciones_ambiental, $id_proveedor);
    if ($stmt->execute()) {
        $response = ['success' => true];
    } else {
        $response = ['success' => false, 'message' => 'Error al aprobar ambiental.'];
    }
    $stmt->close();
}

if ($opcion == 'Negociacion') {
    $aprobado_negociacion = true;
    $proveedor_aprobado = true;
    $numero_acreedor = $_POST['numero_acreedor'];
    $observaciones_negociacion = $_POST['observaciones_negociacion'];

    $stmt = $conexion->prepare("UPDATE vinculacion_proveedor SET aprobado_negociacion = ?, observaciones_negociacion = ? WHERE Id_proveedor_vinculacion_proveedor = ?");
    $stmt->bind_param('sss', $aprobado_negociacion, $observaciones_negociacion, $id_proveedor);
    if ($stmt->execute()) {
        $response = ['success' => true];
    } else {
        $response = ['success' => false, 'message' => 'Error al aprobar negociación.'];
    }
    $stmt->close();

    $stmt = $conexion->prepare("UPDATE proveedores SET numero_acreedor = ?, proveedor_aprobado = ? WHERE Id_proveedor = ?");
    $stmt->bind_param('iss', $numero_acreedor, $proveedor_aprobado, $id_proveedor);
    if ($stmt->execute()) {
        $response = ['success' => true];
    } else {
        $response = ['success' => false, 'message' => 'Error al aprobar negociación.'];
    }
    $stmt->close();
}

echo json_encode($response);
