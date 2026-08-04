<?php

namespace App\Shared\Util;

class EmailTemplates
{
    public static function getResetPasswordTemplate(string $passwordTemp): string
    {
        return '
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
                    {{LOGO_TAG}}
                </div>
                <div class="content">
                    <div class="title">Recuperación de Contraseña</div>
                    <p>Hola,</p>
                    <p>Hemos recibido una solicitud para restablecer el acceso a tu cuenta en el <strong>Sistema de Proveedores de Haceb Whirlpool Industrial S.A.S.</strong></p>
                    <p>A continuación encontrarás tu nueva contraseña temporal generada de forma segura:</p>
                    
                    <div class="password-box">
                        <span class="password">' . htmlspecialchars($passwordTemp) . '</span>
                    </div>
                    
                    <p style="text-align: center; font-size: 14px; color: #666;">
                        <em>Por motivos de seguridad, el sistema te solicitará que cambies esta contraseña inmediatamente después de iniciar sesión.</em>
                    </p>
                    
                    <div class="btn-container">
                        <a href="https://sistema.proveedores_hwi.hacebwhirlpoolindustrial.com/SistemaProveedores/index.php" class="btn" style="background-color: #1D92B2; color: #ffffff; text-decoration: none; padding: 14px 28px; border-radius: 6px; font-weight: bold; display: inline-block;">Ingresar al Sistema</a>
                    </div>
                </div>
                <div class="footer">
                    <p>Copyright © ' . date("Y") . ' Haceb Whirlpool Industrial S.A.S.<br>Todos los derechos reservados.</p>
                    <p>Si no solicitaste este cambio, por favor contacta al administrador del sistema inmediatamente.</p>
                </div>
            </div>
        </body>
        </html>';
    }

    public static function getPasswordChangedTemplate(): string
    {
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { margin: 0; padding: 0; background-color: #f4f7f6; font-family: "Helvetica Neue", Helvetica, Arial, sans-serif; }
                .container { max-width: 600px; margin: 40px auto; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
                .header { text-align: center; padding: 30px 20px; background-color: #ffffff; border-bottom: 2px solid #f0f0f0; }
                .content { padding: 40px 30px; text-align: left; color: #444444; line-height: 1.6; }
                .title { color: #1D92B2; font-size: 22px; font-weight: bold; margin-bottom: 20px; text-align: center; }
                .footer { text-align: center; padding: 20px; background-color: #fafafa; color: #888888; font-size: 12px; border-top: 1px solid #eeeeee; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    {{LOGO_TAG}}
                </div>
                <div class="content">
                    <div class="title">Contraseña Actualizada</div>
                    <p>Hola,</p>
                    <p>Te informamos que la contraseña de tu cuenta en el <strong>Sistema de Proveedores de Haceb Whirlpool Industrial S.A.S.</strong> ha sido actualizada correctamente y tu contraseña temporal ha vencido.</p>
                    <p>Si tú realizaste este cambio, no es necesario realizar ninguna acción adicional.</p>
                </div>
                <div class="footer">
                    <p>Copyright © ' . date("Y") . ' Haceb Whirlpool Industrial S.A.S.<br>Todos los derechos reservados.</p>
                    <p>Si no solicitaste este cambio, por favor contacta al administrador del sistema inmediatamente.</p>
                </div>
            </div>
        </body>
        </html>';
    }
}
