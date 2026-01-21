<html>

<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.5/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.5/dist/sweetalert2.min.js"></script>
</head>

</html>
<?php

include("../../ConexionBD/conexion.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$id_usuario = $_GET["id_usuario"];
$aprobarUsuario = mysqli_query($conexion, "UPDATE usuarios SET estado_registro='1' where Id_usuario='$id_usuario'");

$consultarUsuarios = mysqli_query($conexion, "SELECT * FROM usuarios where Id_usuario='$id_usuario'");

if (mysqli_num_rows($consultarUsuarios) > 0) {
    $correoUsuarioRow = mysqli_fetch_assoc($consultarUsuarios);
    $nombreUsuario = $correoUsuarioRow['nombre_usuario'];

    if ($nombreUsuario != null) {
        $correoUsuario = $correoUsuarioRow['correo_usuario'];

        // Realiza el envío del correo y muestra la alerta correspondiente en el servidor
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
            $mail->addAddress($correoUsuario);
            $mail->isHTML(true);
            $mail->Subject = 'Bienvenido a Haceb Whirlpool, ' . $nombreUsuario;
            $logoUrl = "https://dattics.com/wp-content/uploads/2022/03/Haceb-Whirlpool-Industrial.jpg";
            $mail->Body = '
            <div style="border-radius:10px; border: 1px solid #cccccc; max-width: 100%; max-height: 100%; margin-top: 50px; margin-left: auto; margin-right: auto; text-align: center; padding: 20px;">
                <div style="text-align: center;">
                    <div style="display: inline-block; border-radius: 10px; max-width: 200px; padding: 10px;">
                        <img src="' . $logoUrl . '" alt="Logo Empresa" style="max-width: 100%; height: auto; border-radius: 50%; border: 1px solid #cccccc;">
                    </div>
                </div>
            <h4 style="margin-top: 20px;">Bienvenido a Haceb Whirlpool, ' . $nombreUsuario . '</h4>
            <hr style="background-color: #cccccc; border: none; height: 1px; width: 100%; margin-top: 20px; margin-bottom: 20px;">
                <div style="width: 95%; text-align: justify; margin-left: auto; margin-right: auto;">
                    <div>
                        <p style="margin-top: 10px;">Has sido aprobado para ingresar al sistema de proveedores de Haceb Whirlpool Industrial S.A.S</p>
                    </div>
                </div>
            </div>
            <div style="text-align: center;">
                <p style="color: #999999;">Copyright © Haceb Whirlpool Industrial S.A.S</p>
            <div>
        ';
            $mail->send();

            echo '<script>';
            echo 'Swal.fire({
                    icon: "success",
                    title: "Aprobación Exitosa!",
                    text: "El usuario ha sido aprobado exitosamente.",
                    showConfirmButton: false,
                    timer: 2000 // Cierra automáticamente después de 2 segundos
                });
                setTimeout(function() { window.location.href = "../../Views/Admin"; }, 2000);'; // Redirige después de 2 segundos
            echo '</script>';
        } catch (Exception $e) {

            echo '<script>';
            echo 'Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Error al enviar el correo electrónico: ' . $mail->ErrorInfo . '",
                    showConfirmButton: true
                });';
            echo '</script>';
        }
    }else{
        echo '<script>';
        echo 'Swal.fire({
                icon: "success",
                title: "Aprobación Exitosa!",
                text: "El usuario ha sido aprobado exitosamente.",
                showConfirmButton: false,
                timer: 2000 // Cierra automáticamente después de 2 segundos
            });
            setTimeout(function() { window.location.href = "../../Views/Admin"; }, 2000);'; // Redirige después de 2 segundos
        echo '</script>';
    }
} else {
    echo '<script>';
    echo 'Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "El usuario no pudo ser aprobado, intenta de nuevo...",
                showConfirmButton: false,
                timer: 2000 // Cierra automáticamente después de 3 segundos
            });';
    echo 'setTimeout(function() { window.location.href = "../../Views/Admin/gestionAccesos.php"; }, 3000);'; // Redirige después de 3 segundos
    echo '</script>';
}
?>