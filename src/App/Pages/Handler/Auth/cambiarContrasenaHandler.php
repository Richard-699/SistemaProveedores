<?php
require_once __DIR__ . '/../../../../../vendor/autoload.php';
session_start();

use App\Aplication\Service\LoginService;
use App\Domain\DTO\ChangePasswordDTO;
use App\Shared\Validation\Validator;
use App\Shared\Util\Utils;

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $usuario_input = isset($_POST['usuario']) ? trim($_POST['usuario']) : '';

        $dominio = explode('@', strtolower($usuario_input))[1] ?? '';
        $isAdmin = (strpos($dominio, 'whirlpool') !== false || strpos($dominio, 'haceb') !== false);

        $tempPassword = Utils::generarContrasenaTemporal();
        $hashedPassword = password_hash($tempPassword, PASSWORD_DEFAULT);

        $changePasswordDTO = new ChangePasswordDTO(
            usuario: $usuario_input,
            isAdmin: $isAdmin,
            nuevaPassword: $hashedPassword,
            tempPassword: $tempPassword,
            isTemporal: 1
        );

        Validator::validateDTO($changePasswordDTO);

        $loginService = new LoginService(); //Siempre llamar con el mismo nombre del servicio
        $loginService->recuperarContrasena($changePasswordDTO);

        echo json_encode(['status' => 'success', 'message' => 'Se ha enviado una contraseña temporal a tu correo.']);
        exit;
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        exit;
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Petición inválida.']);
    exit;
}
