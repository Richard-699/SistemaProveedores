<?php
require_once __DIR__ . '/../../../../vendor/autoload.php';
session_start();
ob_start();

use App\Aplication\Service\LoginService;
use App\Domain\DTO\LoginDTO;
use App\Domain\DTO\ResetPasswordDTO;
use App\Shared\Validation\Validator;

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    login($_POST);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método no permitido']);
    exit;
}

function login(array $formData)
{
    try {
        $usuario = isset($formData["usuario"]) ? trim(htmlspecialchars($formData["usuario"])) : '';
        $password = isset($formData["password"]) ? trim($formData["password"]) : '';

        $dominio = explode('@', strtolower($usuario))[1] ?? '';
        $isAdmin = (strpos($dominio, 'whirlpool') !== false || strpos($dominio, 'haceb') !== false);

        $loginDTO = new LoginDTO(
            usuario: $usuario,
            password: $password,
            isAdmin: $isAdmin
        );

        Validator::validateDTO($loginDTO);

        $loginService = new LoginService();
        $loginDTO = $loginService->obtenerdatoslogin($loginDTO);

        if ($loginDTO->administradorDTO === null && $loginDTO->proveedorDTO === null) {
            throw new \Exception("Usuario incorrecto, ingresa nuevamente.");
        }

        $is_admin = null;
        $is_temporal = 0;

        if ($loginDTO->isAdmin && $loginDTO->administradorDTO !== null) {
            $is_admin = true;
            $admin = $loginDTO->administradorDTO;

            if (!password_verify($password, $admin->password_administrador)) {
                throw new \Exception("Contraseña incorrecta, ingresa nuevamente.");
            }
            if ($admin->id_estado_administrador != 4) {
                throw new \Exception("Parece que aún no tienes acceso, solícitalo al administrador e intenta más tarde.");
            }

            $_SESSION['administrador'] = $admin;
            $_SESSION['id_usuario'] = $admin->id_administrador;
            $_SESSION['is_admin'] = true;
            $is_temporal = $admin->password_is_temporal;
        } else if (!$loginDTO->isAdmin && $loginDTO->proveedorDTO !== null) {
            $is_admin = false;
            $prov = $loginDTO->proveedorDTO;

            if (!password_verify($password, $prov->password_proveedor)) {
                throw new \Exception("Contraseña incorrecta, ingresa nuevamente.");
            }

            $_SESSION['proveedor'] = $prov;
            $_SESSION['id_proveedor_usuarios'] = $prov->id_proveedor;
            $_SESSION['is_admin'] = false;
            $is_temporal = $prov->password_is_temporal_proveedor;
        }

        if ($is_temporal == 1) {
            $_SESSION['is_temporal'] = 1;
        } else {
            $_SESSION['is_temporal'] = 0;
        }

        echo json_encode(['status' => 'success', 'data' => [
            'is_admin' => $is_admin,
            'is_temporal' => $is_temporal
        ]]);
        exit;
    } catch (\Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        exit;
    }
}
