<?php
require_once __DIR__ . '/../../../../../vendor/autoload.php';
session_start();
ob_start();

use App\Aplication\Service\ProveedoresService;

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
            case 'onGetProveedores':
                onGetProveedores();
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

function onGetProveedores()
{
    try {
        $proveedoresService = new ProveedoresService();
        $proveedores = $proveedoresService->obtenerProveedores();
        echo json_encode(['success' => true, 'data' => $proveedores]);
        exit;
    } catch (\Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        exit;
    }
}
