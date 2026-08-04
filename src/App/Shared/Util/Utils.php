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

    private static function getMailer(): PHPMailer
    {
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

    public static function enviarCorreo(string $asunto, string $cuerpoHTML, array|string $destinatarios): bool
    {
        try {
            $mail = self::getMailer();
            $mail->Subject = $asunto;

            if (is_string($destinatarios)) {
                $mail->addAddress(trim($destinatarios));
            } elseif (is_array($destinatarios)) {
                foreach ($destinatarios as $dest) {
                    if (!empty(trim($dest))) {
                        $mail->addAddress(trim($dest));
                    }
                }
            }

            $logoPath = __DIR__ . '/../../../../public/img/hwiLogo.png';
            if (file_exists($logoPath)) {
                $mail->AddEmbeddedImage($logoPath, 'logo_hwi');
                $cuerpoHTML = str_replace('{{LOGO_TAG}}', '<img src="cid:logo_hwi" alt="Haceb Whirlpool Industrial" style="max-width: 180px; height: auto;">', $cuerpoHTML);
            } else {
                $cuerpoHTML = str_replace('{{LOGO_TAG}}', '<h2 style="color: #1D92B2; margin: 0;">Haceb Whirlpool</h2>', $cuerpoHTML);
            }
            
            $mail->Body = $cuerpoHTML;
            
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
