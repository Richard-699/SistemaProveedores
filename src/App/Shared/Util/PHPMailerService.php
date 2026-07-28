<?php

namespace App\Shared\Util;

use App\Aplication\Interface\Service\IMailService;
use Exception as GlobalException;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

// Requerir manualmente los archivos de PHPMailer ya que no se usa Composer para esto
require_once __DIR__ . '/../../../../Services/Exception.php';
require_once __DIR__ . '/../../../../Services/PHPMailer.php';
require_once __DIR__ . '/../../../../Services/SMTP.php';

class PHPMailerService implements IMailService {

    private function getMailer(): PHPMailer {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host       = 'gtxm1009.siteground.biz';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'hwiverificacion@hacebwhirlpoolindustrial.com';
        $mail->Password   = 'HWI2023*';
        $mail->SMTPSecure = 'ssl';
        $mail->Port       = 465;
        $mail->setFrom('hwiverificacion@hacebwhirlpoolindustrial.com', 'Equipo BI Haceb Whirlpool');
        $mail->addBCC('ricardo.rojas@hacebwhirlpool.com');
        $mail->CharSet    = 'UTF-8';
        $mail->isHTML(true);

        return $mail;
    }

    public function enviarPasswordTemporal(string $correo, string $passwordTemp): bool {
        try {
            $mail = $this->getMailer();
            $mail->addAddress($correo);
            $mail->Subject = 'Recuperación de Contraseña - Haceb Whirlpool';

            $logoPath = __DIR__ . '/../../../../img/hwiLogo.png';
            if (file_exists($logoPath)) {
                $mail->AddEmbeddedImage($logoPath, 'logo_hwi');
                $imgTag = '<img src="cid:logo_hwi" alt="Haceb Whirlpool Industrial" style="max-width: 180px; height: auto;">';
            } else {
                $imgTag = '<h2 style="color: #1D92B2; margin: 0;">Haceb Whirlpool</h2>';
            }
            
            $mail->Body = '
            <!DOCTYPE html>
            <html>
            <head>
                <style>
                    body { margin: 0; padding: 0; background-color: #f4f7f6; font-family: "Helvetica Neue", Helvetica, Arial, sans-serif; }
                    .container { max-width: 600px; margin: 40px auto; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
                    .header { text-align: center; padding: 30px 20px; background-color: #ffffff; border-bottom: 2px solid #f0f0f0; }
                    .content { padding: 40px 30px; text-align: left; color: #444444; line-height: 1.6; }
                    .title { color: #1D92B2; font-size: 22px; font-weight: bold; margin-bottom: 20px; text-align: center; }
                    .password-box { text-align: center; margin: 30px 0; }
                    .password { display: inline-block; font-family: monospace; font-size: 24px; color: #1D92B2; background-color: #f0f8ff; padding: 15px 30px; border-radius: 8px; border: 1px dashed #1D92B2; letter-spacing: 2px; }
                    .btn-container { text-align: center; margin: 35px 0 20px 0; }
                    .btn { background-color: #1D92B2; color: #ffffff; text-decoration: none; padding: 14px 28px; border-radius: 6px; font-weight: bold; display: inline-block; }
                    .footer { text-align: center; padding: 20px; background-color: #fafafa; color: #888888; font-size: 12px; border-top: 1px solid #eeeeee; }
                </style>
            </head>
            <body>
                <div class="container">
                    <div class="header">
                        ' . $imgTag . '
                    </div>
                    <div class="content">
                        <div class="title">Recuperación de Contraseña</div>
                        <p>Cordial saludo,</p>
                        <p>Hemos recibido una solicitud para restablecer el acceso a tu cuenta en el <strong>Sistema de Proveedores de Haceb Whirlpool Industrial S.A.S.</strong></p>
                        <p>A continuación encontrarás tu nueva contraseña temporal generada de forma segura:</p>
                        
                        <div class="password-box">
                            <span class="password">' . htmlspecialchars($passwordTemp) . '</span>
                        </div>
                        
                        <p style="text-align: center; font-size: 14px; color: #666;">
                            <em>Por motivos de seguridad, el sistema te solicitará que cambies esta contraseña inmediatamente después de iniciar sesión.</em>
                        </p>
                        
                        <div class="btn-container">
                            <a href="https://sistema.proveedores_hwi.hacebwhirlpoolindustrial.com/SistemaProveedores/index.php" style="background-color: #1D92B2; color: #ffffff; text-decoration: none; padding: 14px 28px; border-radius: 6px; font-weight: bold; display: inline-block;">Ingresar al Sistema</a>
                        </div>
                    </div>
                    <div class="footer">
                        <p>Copyright © ' . date("Y") . ' Haceb Whirlpool Industrial S.A.S.<br>Todos los derechos reservados.</p>
                        <p>Si no solicitaste este cambio, por favor contacta al administrador del sistema inmediatamente.</p>
                    </div>
                </div>
            </body>
            </html>';
            
            $mail->send();
            return true;
        } catch (PHPMailerException $e) {
            error_log("Error PHPMailer: " . $mail->ErrorInfo);
            return false;
        } catch (GlobalException $e) {
            error_log("Error enviando correo: " . $e->getMessage());
            return false;
        }
    }
}
