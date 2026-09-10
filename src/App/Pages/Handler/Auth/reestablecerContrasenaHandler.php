<?php
require_once __DIR__ . '/../../../../../vendor/autoload.php';
session_start();

use App\Aplication\Service\LoginService;
use App\Domain\DTO\ChangePasswordDTO;
use App\Shared\Validation\Validator;

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['is_temporal']) || $_SESSION['is_temporal'] != 1) {
        echo json_encode(['status' => 'error', 'message' => 'No tienes permisos para realizar esta acción.']);
        exit;
    }

    $nuevaPassword = $_POST['nuevaPassword'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';

    $isAdmin = $_SESSION['is_admin'] ?? false;
    $userId = $isAdmin ? ($_SESSION['id_usuario'] ?? '') : ($_SESSION['id_proveedor_usuarios'] ?? '');
    $usuarioCorreo = $isAdmin ? ($_SESSION['administrador']->correo_hwi_administrador ?? '') : '';
    $administradorDTO = $isAdmin ? ($_SESSION['administrador'] ?? null) : null;
    $proveedorDTO = !$isAdmin ? ($_SESSION['proveedor'] ?? null) : null;
    $usuario_login = $isAdmin ? $usuarioCorreo : ($proveedorDTO->usuario_proveedor ?? '');

    try {
        $hashedPassword = password_hash($nuevaPassword, PASSWORD_DEFAULT);

        $changePasswordDTO = new ChangePasswordDTO(
            nuevaPassword: $hashedPassword,
            password_raw: $nuevaPassword,
            confirmPassword: $confirmPassword,
            isAdmin: $isAdmin,
            usuarioId: $userId,
            usuarioCorreo: $usuarioCorreo,
            administradorDTO: $administradorDTO,
            proveedorDTO: $proveedorDTO,
            usuario: $usuario_login,
            isTemporal: 0
        );
        Validator::validateDTO($changePasswordDTO);

        $loginService = new LoginService();
        $changePasswordDTO = $loginService->recuperarContrasena($changePasswordDTO);

        if ($isAdmin) {
            $_SESSION['administrador']->password_is_temporal = 0;
            $redirect = "login.php";
        } else {
            $_SESSION['proveedor']->password_is_temporal_proveedor = 0;
            $redirect = "login.php";
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
} else {
    echo json_encode(['status' => 'error', 'message' => 'Petición inválida.']);
    exit;
}
