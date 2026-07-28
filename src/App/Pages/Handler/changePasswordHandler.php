<?php
session_start();
require_once __DIR__ . '/../../../../vendor/autoload.php';

use App\Infrastructure\Database\Connection;
use App\Infrastructure\Repository\ChangePasswordRepository;
use App\Aplication\Service\ChangePasswordService;

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btnCambiar'])) {
    
    if (!isset($_SESSION['is_temporal']) || $_SESSION['is_temporal'] != 1) {
        echo json_encode(['status' => 'error', 'message' => 'No tienes permisos para realizar esta acción.']);
        exit;
    }

    $nuevaPassword = $_POST['nuevaPassword'] ?? '';
    $isAdmin = $_SESSION['is_admin'] ?? false;
    $userId = $isAdmin ? ($_SESSION['id_usuario'] ?? '') : ($_SESSION['id_proveedor_usuarios'] ?? '');

    try {
        $db = (new Connection())->dbsistemas_proveedores;
        $repository = new ChangePasswordRepository($db);
        $service = new ChangePasswordService($repository);
        
        $response = $service->changePassword($nuevaPassword, $isAdmin, $userId);
        
        if ($response['status'] === 'success') {
            $_SESSION['is_temporal'] = 0; // Actualizar la sesión si fue exitoso
        }
        
        echo json_encode($response);
        
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Petición inválida.']);
}
