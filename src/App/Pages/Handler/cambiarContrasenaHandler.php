<?php
require_once __DIR__ . '/../../../../vendor/autoload.php';
session_start();

use App\Aplication\Service\LoginService;

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'changePassword';

    if ($action === 'sendEmail') {
        try {
            $resetDTO = new \App\Domain\DTO\ResetPasswordDTO(correo: $_POST['correo'] ?? '');
            
            $service = new LoginService();
            $resetDTO = $service->obtenerDatosReset($resetDTO);

            \App\Shared\Validation\Validator::validateDTO($resetDTO);

            $tempPassword = \App\Shared\Util\Utils::generarContrasenaTemporal();
            $hashedPassword = password_hash($tempPassword, PASSWORD_DEFAULT);

            if ($resetDTO->isAdmin) {
                $adminDTO = $resetDTO->administradorDTO;
                $adminDTO->password_administrador = $hashedPassword;
                $adminDTO->password_is_temporal = 1;
                $service->actualizarPasswordAdministrador($adminDTO);
                
                $asunto = "Recuperación de Contraseña - Administrador";
                $cuerpo = \App\Shared\Util\EmailTemplates::getResetPasswordTemplate($tempPassword);
                $enviado = \App\Shared\Util\Utils::enviarCorreo($asunto, $cuerpo, $resetDTO->correosList);
            } else {
                $proveedorDTO = $resetDTO->proveedorDTO;
                $proveedorDTO->password_proveedor = $hashedPassword;
                $proveedorDTO->password_is_temporal_proveedor = 1;
                $service->actualizarPasswordProveedor($proveedorDTO);

                $asunto = "Recuperación de Contraseña - Proveedor";
                $cuerpo = \App\Shared\Util\EmailTemplates::getResetPasswordTemplate($tempPassword);
                $enviado = \App\Shared\Util\Utils::enviarCorreo($asunto, $cuerpo, $resetDTO->correosList);
            }

            if (!$enviado) {
                throw new \Exception("Se actualizó la contraseña pero hubo un error al enviar el correo.");
            }

            echo json_encode(['status' => 'success', 'message' => 'Se ha enviado una contraseña temporal a tu correo.']);
            exit;
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
            exit;
        }
    } else if ($action === 'changePassword') {
        if (!isset($_SESSION['is_temporal']) || $_SESSION['is_temporal'] != 1) {
            echo json_encode(['status' => 'error', 'message' => 'No tienes permisos para realizar esta acción.']);
            exit;
        }

        $nuevaPassword = $_POST['nuevaPassword'] ?? '';
        $confirmPassword = $_POST['confirmPassword'] ?? '';

        $isAdmin = $_SESSION['is_admin'] ?? false;
        $userId = $isAdmin ? ($_SESSION['id_usuario'] ?? '') : ($_SESSION['id_proveedor_usuarios'] ?? '');
        $usuarioCorreo = $isAdmin ? ($_SESSION['administrador']->correo_hwi_administrador ?? '') : '';

        try {
            $changePasswordDTO = new \App\Domain\DTO\ChangePasswordDTO(
                nuevaPassword: $nuevaPassword,
                confirmPassword: $confirmPassword,
                isAdmin: $isAdmin,
                usuarioId: $userId,
                usuarioCorreo: $usuarioCorreo
            );
            \App\Shared\Validation\Validator::validateDTO($changePasswordDTO);

            $service = new LoginService();
            $hashedPassword = password_hash($changePasswordDTO->nuevaPassword, PASSWORD_DEFAULT);

            if ($isAdmin) {
                $adminDTO = new \App\Domain\DTO\AdministradoresDTO(
                    id_administrador: $userId,
                    password_administrador: $hashedPassword,
                    password_is_temporal: 0
                );
                $service->actualizarPasswordAdministrador($adminDTO);
                $_SESSION['administrador']->password_is_temporal = 0;
                
                $asunto = "Cambio de Contraseña Exitoso";
                $cuerpo = \App\Shared\Util\EmailTemplates::getPasswordChangedTemplate();
                \App\Shared\Util\Utils::enviarCorreo($asunto, $cuerpo, $usuarioCorreo);
                
                $redirect = "../../../../../Views/Admin/index.php";
            } else {
                $proveedorDTO = new \App\Domain\DTO\ProveedoresDTO(
                    id_proveedor: $userId,
                    password_proveedor: $hashedPassword,
                    password_is_temporal_proveedor: 0
                );
                $service->actualizarPasswordProveedor($proveedorDTO);
                $_SESSION['proveedor']->password_is_temporal_proveedor = 0;

                $correosList = $service->getCorreosProveedor($userId);
                if (!empty($correosList)) {
                    $asunto = "Cambio de Contraseña Exitoso";
                    $cuerpo = \App\Shared\Util\EmailTemplates::getPasswordChangedTemplate();
                    \App\Shared\Util\Utils::enviarCorreo($asunto, $cuerpo, $correosList);
                }

                $redirect = "../../../../../Views/Supplier/index.php";
            }
            $_SESSION['is_temporal'] = 0;

            echo json_encode([
                'status' => 'success',
                'message' => 'Tu contraseña ha sido actualizada exitosamente.',
                'redirect' => $redirect
            ]);
            exit;
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
            exit;
        }
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Petición inválida.']);
    exit;
}
