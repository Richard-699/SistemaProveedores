<?php
session_start();
ob_start();

require_once __DIR__ . '/../../../../vendor/autoload.php';

use App\Aplication\Service\LoginService;
use App\Domain\DTO\LoginDTO;
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

        // Se envian al DTO
        $loginDTO = new LoginDTO(
            usuario: $usuario,
            password: $password,
            isAdmin: $isAdmin
        );

        Validator::validateDTO($loginDTO);

        $loginService = new LoginService();
        $loginDTO = $loginService->obtenerDatosUsuario($loginDTO);

        if ($loginDTO->administradorDTO === null && $loginDTO->proveedorDTO === null) {
            throw new \Exception("Usuario incorrecto, ingresa nuevamente.");
        }

        $is_temporal = null;
        $is_admin = null;

        if ($loginDTO->isAdmin && $loginDTO->administradorDTO !== null) {
            $admin = $loginDTO->administradorDTO;

            if (!password_verify($password, $admin->password_administrador)) {
                throw new \Exception("Contraseña incorrecta, ingresa nuevamente.");
            }
            if ($admin->id_estado_administrador != 4) {
                throw new \Exception("Parece que aún no tienes acceso, solícitalo al administrador e intenta más tarde.");
            }

            $_SESSION['id'] = $admin->id_administrador;
            $_SESSION['usuario'] = $loginDTO->usuario;
            $_SESSION['is_admin'] = true;
            $_SESSION['password_is_temporal'] = $admin->password_is_temporal;
            
            $is_temporal = $admin->password_is_temporal;
            $is_admin = true;
        } else if (!$loginDTO->isAdmin && $loginDTO->proveedorDTO !== null) {
            $prov = $loginDTO->proveedorDTO;

            if (!password_verify($password, $prov->password_proveedor)) {
                throw new \Exception("Contraseña incorrecta, ingresa nuevamente.");
            }

            $_SESSION['id'] = $prov->id_proveedor;
            $_SESSION['usuario'] = $loginDTO->usuario;
            $_SESSION['is_admin'] = false;
            $_SESSION['password_is_temporal'] = $prov->password_is_temporal_proveedor;
            
            $is_temporal = $prov->password_is_temporal_proveedor;
            $is_admin = false;
        }

        echo json_encode(['status' => 'success', 'data' => [
            'is_temporal' => $is_temporal,
            'is_admin' => $is_admin
        ]]);
        exit;
    } catch (\Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        exit;
    }
}
