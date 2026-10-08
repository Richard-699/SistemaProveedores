<?php
header('Content-Type: application/json; charset=utf-8');

include("../../ConexionBD/conexion.php");
/** @var mysqli $conexion */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
    exit;
}

$input_usuario = trim($_POST['usuario'] ?? ($_POST['correo_usuario'] ?? ''));

if (empty($input_usuario)) {
    echo json_encode(['success' => false, 'message' => 'Por favor, ingresa tu usuario o correo electrónico.']);
    exit;
}

try {
    $stmt = $conexion->prepare("SELECT u.Id_usuario, u.nombre_usuario, u.apellidos_usuario, u.correo_usuario, p.nombre_proveedor 
        FROM usuarios u 
        LEFT JOIN proveedores p ON u.id_proveedor_usuarios = p.Id_proveedor 
        WHERE u.correo_usuario = ? OR u.nombre_usuario = ? 
        LIMIT 1");

    if (!$stmt) {
        throw new Exception("Error al preparar la consulta: " . $conexion->error);
    }

    $stmt->bind_param("ss", $input_usuario, $input_usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if (!$resultado || $resultado->num_rows === 0) {
        echo json_encode([
            'success' => false,
            'message' => 'El usuario o correo electrónico ingresado no se encuentra registrado en el sistema.'
        ]);
        exit;
    }

    $usuario = $resultado->fetch_assoc();
    $id_usuario = (int)$usuario['Id_usuario'];

    // Determinar dirección de correo de destino
    $correo_destino = filter_var($usuario['correo_usuario'], FILTER_VALIDATE_EMAIL) ? $usuario['correo_usuario'] : '';
    if (empty($correo_destino) && filter_var($input_usuario, FILTER_VALIDATE_EMAIL)) {
        $correo_destino = $input_usuario;
    }

    if (empty($correo_destino)) {
        echo json_encode([
            'success' => false,
            'message' => 'Este usuario no tiene un correo electrónico válido registrado para recibir la contraseña. Por favor, contacta con el administrador.'
        ]);
        exit;
    }

    $nombreCompleto = trim(($usuario['nombre_usuario'] ?? '') . ' ' . ($usuario['apellidos_usuario'] ?? ''));
    if (empty($nombreCompleto) && !empty($usuario['nombre_proveedor'])) {
        $nombreCompleto = trim($usuario['nombre_proveedor']);
    }
    if (empty($nombreCompleto)) {
        $nombreCompleto = 'Usuario';
    }

    // Generar contraseña temporal segura
    $caracteres = 'abcdefghjkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789!@#$%';
    $password_temp = 'Hwi_' . substr(str_shuffle($caracteres), 0, 8);
    $hashedPassword = password_hash($password_temp, PASSWORD_DEFAULT);
    $is_temporal = 1;

    // Actualizar usuario en BD
    $updateStmt = $conexion->prepare("UPDATE usuarios SET password_usuario = ?, is_temporal = ? WHERE Id_usuario = ?");
    if (!$updateStmt) {
        throw new Exception("Error al preparar la actualización: " . $conexion->error);
    }

    $updateStmt->bind_param("sii", $hashedPassword, $is_temporal, $id_usuario);
    if (!$updateStmt->execute()) {
        throw new Exception("Error al actualizar la contraseña: " . $updateStmt->error);
    }

    // Enviar correo con PHPMailer
    require_once '../../Services/Exception.php';
    require_once '../../Services/PHPMailer.php';
    require_once '../../Services/SMTP.php';

    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host       = 'mail.hacebwhirlpoolindustrial.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'hwiverificacion@hacebwhirlpoolindustrial.com';
    $mail->Password   = 'HWI2023*';
    $mail->SMTPSecure = 'ssl';
    $mail->Port       = 465;
    $mail->CharSet    = 'UTF-8';
    $mail->Encoding   = 'base64';

    $mail->setFrom('hwiverificacion@hacebwhirlpoolindustrial.com', 'Sistema de Proveedores HWI');
    $mail->addAddress($correo_destino);
    $mail->isHTML(true);
    $mail->Subject = 'Recuperación de Contraseña - Haceb Whirlpool Industrial';

    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $scriptDir = dirname(dirname(dirname($_SERVER['SCRIPT_NAME'] ?? '')));
    $loginUrl = rtrim($protocol . $host . $scriptDir, '/\\') . "/index.php";

    $logoUrl = "https://sistemaevaluacioncontratistas.hacebwhirlpoolindustrial.com/Evaluador_HWI/Imagenes/LogoBlancoHWI.png";

    $mail->Body = '
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Recuperación de Contraseña</title>
    </head>
    <body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; margin: 0;">
        <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; border: 1px solid #e0e0e0; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            
            <div style="text-align: center; padding: 25px 20px; background: linear-gradient(to right, #072B31, #0093B2);">
                <img src="' . $logoUrl . '" alt="Haceb Whirlpool Industrial" style="max-width: 220px; height: auto;">
            </div>

            <div style="padding: 30px; color: #333333; line-height: 1.6;">
                <h2 style="text-align: center; color: #072B31; margin-bottom: 20px; font-weight: bold;">
                    Recuperación de Contraseña
                </h2>
                
                <p style="font-size: 15px;">Hola <strong>' . htmlspecialchars($nombreCompleto) . '</strong>,</p>
                <p style="font-size: 15px;">Hemos recibido una solicitud para restablecer el acceso a tu cuenta en el <strong>Sistema de Proveedores de Haceb Whirlpool Industrial S.A.S</strong>.</p>
                
                <p style="font-size: 15px;">Tu nueva contraseña temporal para iniciar sesión es:</p>
                
                <div style="text-align: center; margin: 25px 0;">
                    <span style="display: inline-block; background-color: #f0f8fa; border: 2px dashed #0093B2; padding: 12px 25px; font-size: 20px; font-weight: bold; letter-spacing: 2px; color: #072B31; border-radius: 6px;">
                        ' . htmlspecialchars($password_temp) . '
                    </span>
                </div>
                
                <p style="font-size: 14px; color: #c0392b; background-color: #fdf2f2; padding: 10px 15px; border-left: 4px solid #e74c3c; border-radius: 4px;">
                    <strong>Importante:</strong> Por motivos de seguridad, el sistema te solicitará cambiar esta contraseña temporal inmediatamente después de iniciar sesión.
                </p>
                
                <div style="text-align: center; margin-top: 30px; margin-bottom: 10px;">
                    <a href="' . $loginUrl . '" style="display: inline-block; padding: 12px 28px; background-color: #0093B2; color: #ffffff; text-decoration: none; font-weight: bold; border-radius: 5px; font-size: 16px;">
                        Acceder al Sistema
                    </a>
                </div>
            </div>

            <div style="text-align: center; padding: 15px; background-color: #f9f9f9; color: #888888; font-size: 12px; border-top: 1px solid #eeeeee;">
                <p style="margin: 0;">Copyright © Haceb Whirlpool Industrial S.A.S</p>
            </div>
        </div>
    </body>
    </html>
    ';

    $mail->send();

    echo json_encode([
        'success' => true,
        'message' => 'Se ha enviado una contraseña temporal a tu correo electrónico. Por favor, revisa tu bandeja de entrada o spam.'
    ]);
    exit;

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'No se pudo enviar el correo electrónico. Motivo: ' . $e->getMessage()
    ]);
    exit;
}
