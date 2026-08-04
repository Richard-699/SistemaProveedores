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

            $response = $service->restablecerContrasena($resetDTO);

            echo json_encode($response);
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
            $response = $service->cambiarContrasenaTemporal($changePasswordDTO);
            
            if ($response['status'] === 'success') {
                $_SESSION['is_temporal'] = 0;
                if ($isAdmin && isset($_SESSION['administrador'])) {
                    $_SESSION['administrador']->password_is_temporal = 0;
                } elseif (!$isAdmin && isset($_SESSION['proveedor'])) {
                    $_SESSION['proveedor']->password_is_temporal_proveedor = 0;
                }
            }
            
            echo json_encode($response);
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
