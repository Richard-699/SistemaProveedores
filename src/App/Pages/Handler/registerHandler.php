<?php
ob_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../../../vendor/autoload.php';

use App\Aplication\Service\RegistroService;
use App\Aplication\Service\AreasService;
use App\Domain\DTO\AdministradoresDTO;
use App\Shared\Validation\Validator;

// Inyección de dependencias global
$db = (new \App\Infrastructure\Database\Connection())->dbsistemas_proveedores;
$administradoresRepository = new \App\Infrastructure\Repository\AdministradoresRepository($db);
$registroService = new RegistroService($administradoresRepository);

$areasRepository = new \App\Infrastructure\Repository\AreasRepository($db);
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

        $administradorDTO = new AdministradoresDTO(
            id_administrador: '', // El ID se genera en la base de datos
            nombre_administrador: $nombre,
            apellidos_administrador: $apellidos,
            correo_hwi_administrador: $correo_completo,
            id_area_administrador: $id_area_usuario ?? 0,
            password_administrador: $password_raw,
            id_estado_administrador: 3,
            password_is_temporal: 0
        );

        $password_confirmacion = $postData['confirmPassword'] ?? null;
        if (empty($password_confirmacion)) {
            return ['status' => 'error', 'errors' => ['confirmPassword' => 'La confirmación de contraseña es obligatoria.']];
        } elseif ($password_raw !== $password_confirmacion) {
            return ['status' => 'error', 'errors' => ['confirmPassword' => 'Las contraseñas no coinciden.']];
        }

        Validator::validateDTO($administradorDTO);

        $administradorDTO->password_administrador = password_hash($password_raw, PASSWORD_DEFAULT);



        if(!$email_registrado = $registroService->validar_email_registrado($administradorDTO->correo_hwi_administrador)){
            throw new Exception("Usuario reg");
        }

        $guardar_usuario = $registroService->guardar_administrador($administradorDTO);

        if (!$guardar_usuario) {
            throw new Exception("Error al registrar, intente nuevamente.");
        }

        return [
            'status' => 'success',
            'message' => 'Usuario registrado exitosamente.'
        ];

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
