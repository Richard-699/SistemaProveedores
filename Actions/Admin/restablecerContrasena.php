<?php
session_start();

include("../../ConexionBD/conexion.php");

$id_usuario = $_POST["id_usuario"];
$password_temp = $_POST['password_temp'];
$hashedPassword = password_hash($password_temp, PASSWORD_DEFAULT);
$correo_envio = $_POST['correo_envio'];
$is_temporal = true;

$actualizarContrasena = mysqli_prepare($conexion, "UPDATE usuarios 
    SET password_usuario = ?, is_temporal = ?
    WHERE Id_usuario = ?");

mysqli_stmt_bind_param(
    $actualizarContrasena,
    "ssi",
    $hashedPassword,
    $is_temporal,
    $id_usuario
);
mysqli_stmt_execute($actualizarContrasena);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($actualizarContrasena) {

    echo json_encode(array("success" => true));

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
        $mail->addAddress($correo_envio);
        $mail->addBCC('ricardo.rojas@hacebwhirlpool.com');
        $mail->isHTML(true);
        $mail->Subject = 'Contrasena Restablecida';
        $logoUrl = "https://dattics.com/wp-content/uploads/2022/03/Haceb-Whirlpool-Industrial.jpg";
        $mail->Body = '
            <div style="border-radius:10px; border: 1px solid #cccccc; max-width: 100%; max-height: 100%; margin-top: 50px; margin-left: auto; margin-right: auto; text-align: center; padding: 20px;">
                <div style="text-align: center;">
                    <div style="display: inline-block; border-radius: 10px; max-width: 200px; padding: 10px;">
                        <img src="' . $logoUrl . '" alt="Logo Empresa" style="max-width: 100%; height: auto; border-radius: 50%; border: 1px solid #cccccc;">
                    </div>
                </div>
            <h4 style="margin-top: 20px;">Contraseña Restablecida</h4>
            <hr style="background-color: #cccccc; border: none; height: 1px; width: 100%; margin-top: 20px; margin-bottom: 20px;">
                <div style="width: 95%; text-align: justify; margin-left: auto; margin-right: auto;">
                    <div>
                        <p style="margin-top: 10px;">La siguiente es la nueva contraseña de acceso al sistema de proveedores de Haceb Whirlpool Industrial S.A.S</p>
                        <p><span style="font-weight: bold;">Contraseña temporal:</span> ' . $password_temp . '</p>
                        <p><span style="font-weight: bold;">Enlace de acceso: </span><a href="https://sistema.proveedores.hacebwhirlpoolindustrial.com/SistemaProveedores/index.php">Haceb Whirlpool</a></p>
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
} else {
    echo json_encode(array("success" => false));
}
