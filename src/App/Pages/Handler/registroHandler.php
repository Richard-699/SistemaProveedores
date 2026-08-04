<?php
require_once __DIR__ . '/../../../../vendor/autoload.php';
session_start();
ob_start();

use App\Aplication\Service\RegistroService;
use App\Aplication\Service\AreasService;
use App\Domain\DTO\AdministradoresDTO;
use App\Shared\Validation\Validator;
use App\Shared\Util\Utils;

header('Content-Type: application/json; charset=utf-8');

$requestMethod = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? null;

if ($requestMethod === 'POST' && ($action === 'registrarAdministrador' || empty($action))) {
    registrar($_POST);
} elseif ($requestMethod === 'GET' && $action === 'getAreas') {
    getAreas();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método no permitido']);
    exit;
}

function registrar(array $formData)
{
    try {
        $password_raw = $formData['password'] ?? '';
        $nombre = ucwords(strtolower($formData['nombre_usuario'] ?? ''));
        $apellidos = ucwords(strtolower($formData['apellidos_usuario'] ?? ''));

        $usuario_prefijo = trim($formData['usuario_prefijo'] ?? '');
        $usuario_dominio = trim($formData['usuario_dominio'] ?? '');
        $correo_completo = $usuario_prefijo !== '' ? $usuario_prefijo . $usuario_dominio : '';

        $id_area_usuario = isset($formData['id_area_usuario']) && $formData['id_area_usuario'] !== ''
            ? (int) $formData['id_area_usuario']
            : null;

        $id_admin = Utils::generarGuid();
        $id_estado = 2;

        $password_hash = password_hash($password_raw, PASSWORD_DEFAULT);

        $administradorDTO = new AdministradoresDTO(
            id_administrador: $id_admin,
            nombre_administrador: $nombre,
            apellidos_administrador: $apellidos,
            correo_hwi_administrador: $correo_completo,
            id_area_administrador: $id_area_usuario ?? null, //hacer la validación en el validator
            password_administrador: $password_hash,
            id_estado_administrador: 3,
            password_is_temporal: 0,
            permisosDTO: [],
            is_admin: null,
            password_raw: $password_raw,
            confirm_password: $formData['confirmPassword'] ?? null
        );

        Validator::validateDTO($administradorDTO);


        $registroService = new RegistroService();

        $guardado = $registroService->guardar_administrador($administradorDTO);

        if (!$guardado) {
            throw new \Exception("Error al registrar, intente nuevamente.");
        }

        echo json_encode(['status' => 'success']);
        exit;
    } catch (\Throwable $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        exit;
    }
}

function getAreas()
{
    try {
        $areasService = new AreasService();
        $areas = $areasService->obtenerAreas();
        echo json_encode(['status' => 'success', 'data' => $areas]);
        exit;
    } catch (\Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Error al cargar áreas: ' . $e->getMessage()]);
        exit;
    }
}
