<?php

namespace App\Shared\Util;

use Exception as GlobalException;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

// PHPMailer ahora se carga a través de Composer (vendor/autoload.php)

class Utils
{
    public static function generarGuid(): string
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );
    }

    public static function generarContrasenaTemporal(int $length = 10): string
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()';
        $randomStr = substr(str_shuffle($chars), 0, $length);
        return 'Hwi_' . $randomStr;
    }

    public static function enviarCorreo(array|string $destinatario, string $asunto, string $titulo, string $contenidoHtml): bool
    {
        try {
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

            $mail->setFrom('hwiverificacion@hacebwhirlpoolindustrial.com', 'Equipo BI');

            if (is_string($destinatario)) {
                $mail->addAddress(trim($destinatario));
            } elseif (is_array($destinatario)) {
                foreach ($destinatario as $dest) {
                    if (!empty(trim($dest))) {
                        $mail->addAddress(trim($dest));
                    }
                }
            }

            $mail->isHTML(true);
            $mail->Subject = $asunto;

            $logoUrl = "https://sistemaevaluacioncontratistas.hacebwhirlpoolindustrial.com/Evaluador_HWI/Imagenes/LogoBlancoHWI.png";
            $loginUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/SistemaProveedores/src/App/Pages/View/Auth/login.php";

            $mail->Body = '
            <html>
            <body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; margin: 0;">
                <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; border: 1px solid #e0e0e0; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                    
                    <div style="text-align: center; padding: 30px 20px; border-bottom: 4px solid #005691;">
                        <img src="' . $logoUrl . '" alt="Haceb Whirlpool" style="width: 200px; height: auto;">
                    </div>

                    <div style="padding: 30px; color: #333333; line-height: 1.6;">
                        <h2 style="text-align: center; color: #222222; margin-bottom: 25px; font-weight: bold;">
                            ' . $titulo . '
                        </h2>
                        
                        <div style="font-size: 15px;">
                            ' . $contenidoHtml . '
                        </div>
                        
                        <div style="text-align: center; margin-top: 30px;">
                            <a href="' . $loginUrl . '" style="display: inline-block; padding: 12px 24px; background-color: #005691; color: #ffffff; text-decoration: none; font-weight: bold; border-radius: 4px; font-size: 16px;">Acceder al Sistema</a>
                        </div>
                    </div>

                    <div style="text-align: center; padding: 20px; background-color: #f9f9f9; color: #888888; font-size: 12px; border-top: 1px solid #eeeeee;">
                        <p style="margin: 0;">Copyright © Haceb Whirlpool Industrial S.A.S</p>
                    </div>
                </div>
            </body>
            </html>
        ';

            $mail->send();
            return true;
        } catch (PHPMailerException $e) {
            error_log("Error al enviar el correo electrónico: " . $e->getMessage());
            return false;
        } catch (GlobalException $e) {
            error_log("Error al enviar el correo electrónico: " . $e->getMessage());
            return false;
        }
    }
}
