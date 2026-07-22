<?php
ob_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../../../../vendor/autoload.php';

use App\Aplication\Service\Auth\RegistroService;
use App\Aplication\Service\Auth\AreasService;
use App\Domain\DTO\Auth\UsuariosDTO;
use App\Shared\Validation\Validator;

// Inyección de dependencias global
$db = (new \App\Infrastructure\Database\Connection())->dbsistemas_proveedores;
$usuariosRepository = new \App\Infrastructure\Repository\Auth\UsuariosRepository($db);
$registroService = new RegistroService($usuariosRepository);

$areasRepository = new \App\Infrastructure\Repository\Auth\AreasRepository($db);
$areasService = new AreasService($areasRepository);

function onPostRegistrarUsuario(array $postData, RegistroService $registroService)
{
    try {

        $password_raw = $postData['password'] ?? '';

        $nombre = ucwords(strtolower($postData['nombre_usuario'] ?? ''));
        $apellidos = ucwords(strtolower($postData['apellidos_usuario'] ?? ''));


        $usuario_prefijo = trim($postData['usuario_prefijo'] ?? '');
        $usuario_dominio = trim($postData['usuario_dominio'] ?? '');
        $correo_completo = $usuario_prefijo !== '' ? $usuario_prefijo . $usuario_dominio : '';

        $id_area_usuario = isset($postData['id_area_usuario']) && $postData['id_area_usuario'] !== ''
            ? (int) $postData['id_area_usuario']
            : null;

        $usuarioDTO = new UsuariosDTO(
            nombre_usuario: $nombre,
            apellidos_usuario: $apellidos,
            correo_usuario: $correo_completo,
            password_usuario: $password_raw,
            password_confirmacion: $postData['confirmPassword'] ?? null,
            id_area_usuario: $id_area_usuario,
            estado_registro: 3,
            is_temporal: false
        );

        Validator::validateDTO($usuarioDTO);

        $usuarioDTO->password_usuario = password_hash($password_raw, PASSWORD_DEFAULT);



        if(!$email_registrado = $registroService->validar_email_registrado($usuarioDTO->correo_usuario)){
            throw new Exception("Usuario reg");
        }

        $guardar_usuario = $registroService->guardar_usuario($usuarioDTO);

        if (!$guardar_usuario) {
            throw new Exception("Error al registrar, intente nuevamente.");
        }

        return true;

    } catch (Exception $e) {
        return [
            'status' => 'error',
            'message' => $e->getMessage()
        ];
    }
}

function onGetAreas(AreasService $areasService)
{
    try {
        $areas = $areasService->obtenerAreasParaRegistro();
        return [
            'status' => 'success',
            'data' => $areas
        ];
    } catch (Exception $e) {
        return [
            'status' => 'error',
            'message' => 'Error al cargar áreas: ' . $e->getMessage()
        ];
    }
}

$response = [];
try {
    $requestMethod = $_SERVER['REQUEST_METHOD'];
    $action = $_GET['action'] ?? null;

    if ($requestMethod === 'POST') {

        if ($action === 'registrarUsuario' || empty($action)) {
            $response = onPostRegistrarUsuario($_POST, $registroService);
        } else {
            throw new Exception("Acción '$action' no reconocida.");
        }
    } elseif ($requestMethod === 'GET') {
        if ($action === 'getAreas') {
            $response = onGetAreas($areasService);
        } else {
            throw new Exception("Acción '$action' no reconocida.");
        }
    } else {
        throw new Exception("Método $requestMethod no permitido.");
    }
} catch (Exception $e) {
    $response = ['status' => 'error', 'message' => $e->getMessage()];
}

ob_clean();
header('Content-Type: application/json; charset=utf-8');
echo json_encode($response, JSON_UNESCAPED_UNICODE);
exit;
