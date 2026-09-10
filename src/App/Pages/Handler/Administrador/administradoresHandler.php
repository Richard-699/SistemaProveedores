<?php
require_once __DIR__ . '/../../../../../vendor/autoload.php';
session_start();
ob_start();

use App\Aplication\Service\AdministradoresService;
use App\Domain\DTO\AdministradoresPermisosDTO;
use App\Shared\Validation\Validator;

header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit();
}

$requestMethod = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? ($_POST['action'] ?? '');

switch ($requestMethod) {
    case 'GET':
        switch ($action) {
            case 'onGetAdministradores':
                onGetAdministradores();
                break;
            case 'obtenerPermisos':
                obtenerPermisos();
                break;
            case 'obtenerPermisosAdmin':
                obtenerPermisosAdmin($_GET);
                break;
            default:
                echo json_encode(['success' => false, 'message' => 'Acción no permitida']);
                exit;
        }
        break;

    case 'POST':
        switch ($action) {
            case 'aprobarAdministrador':
                aprobarAdministrador($_POST);
                break;
            case 'actualizarPermisosAdministrador':
                actualizarPermisosAdministrador($_POST);
                break;
            case 'rechazar':
                rechazarAdministrador($_POST);
                break;
            case 'eliminar':
                eliminarAdministrador($_POST);
                break;
            default:
                echo json_encode(['success' => false, 'message' => 'Acción no permitida']);
                exit;
        }
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Método o acción no permitida']);
        exit;
}

function onGetAdministradores()
{
    try {
        $administradoresService = new AdministradoresService();
        $administradores = $administradoresService->obtenerAdministradores();
        echo json_encode(['success' => true, 'data' => $administradores]);
        exit;
    } catch (\Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        exit;
    }
}

function obtenerPermisos()
{
    try {
        $administradoresService = new AdministradoresService();
        $permisos = $administradoresService->obtenerPermisos();
        echo json_encode(['success' => true, 'data' => $permisos]);
        exit;
    } catch (\Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        exit;
    }
}

function obtenerPermisosAdmin(array $data)
{
    try {
        $id_admin = $data['id_administrador'] ?? '';
        if (empty($id_admin)) {
            throw new Exception("ID de administrador requerido.");
        }

        $service = new AdministradoresService();
        $adminPermisosDTO = $service->obtenerPermisosAdmin($id_admin);

        $ids = array_map(fn($permisoDTO) => $permisoDTO->id_permiso, $adminPermisosDTO->permisosDTO);

        echo json_encode(['success' => true, 'data' => $ids]);
        exit;
    } catch (\Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        exit;
    }
}

function aprobarAdministrador(array $formData)
{
    try {
        $permisos = $formData['permisos'] ?? [];

        $adminPermisosDTO = new AdministradoresPermisosDTO(
            id_administrador_permiso: $formData['id_administrador'],
            permisosDTO: $permisos
        );

        Validator::validatePermisosAdministrador($adminPermisosDTO);

        $administradoresService = new AdministradoresService();
        $administradoresService->aprobarAdministrador($adminPermisosDTO);

        echo json_encode(['success' => true, 'message' => 'Administrador aprobado y permisos asignados con Éxito.']);
        exit;
    } catch (\Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        exit;
    }
}

function actualizarPermisosAdministrador(array $formData)
{
    try {
        $id_admin = $formData['id_administrador'] ?? '';
        $permisos = $formData['permisos'] ?? [];

        $adminPermisosDTO = new AdministradoresPermisosDTO(
            id_administrador_permiso: $id_admin,
            permisosDTO: $permisos
        );

        Validator::validatePermisosAdministrador($adminPermisosDTO);

        $administradoresService = new AdministradoresService();
        $administradoresService->actualizarPermisosAdministrador($adminPermisosDTO);

        $refresh_required = false;
        if (isset($_SESSION['id_usuario']) && $_SESSION['id_usuario'] === $id_admin) {
            $adminPermisosDTO = $administradoresService->obtenerPermisosAdmin($id_admin);
            $_SESSION['administrador']->permisosDTO = $adminPermisosDTO->permisosDTO;
            $refresh_required = true;
        }

        echo json_encode(['success' => true, 'message' => 'Permisos actualizados con éxito.', 'refresh_required' => $refresh_required]);
        exit;
    } catch (\Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        exit;
    }
}

function rechazarAdministrador(array $formData)
{
    try {
        $id_admin = $formData['id_administrador'] ?? '';
        Validator::validateIdAdministrador($id_admin);

        $administradoresService = new AdministradoresService();
        $administradoresService->rechazarAdministrador($id_admin);

        echo json_encode(['success' => true, 'message' => 'Administrador rechazado correctamente.']);
        exit;
    } catch (\Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        exit;
    }
}

function eliminarAdministrador(array $formData)
{
    try {
        $id_admin = $formData['id_administrador'] ?? '';
        Validator::validateIdAdministrador($id_admin);

        $administradoresService = new AdministradoresService();
        $administradoresService->eliminarAdministrador($id_admin);

        echo json_encode(['success' => true, 'message' => 'Administrador eliminado correctamente.']);
        exit;
    } catch (\Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        exit;
    }
}
