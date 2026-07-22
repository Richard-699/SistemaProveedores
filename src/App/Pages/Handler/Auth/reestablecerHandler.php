<?php
session_start();
require_once __DIR__ . '/../../../../../vendor/autoload.php';

use App\Infrastructure\Database\Connection;
use App\Infrastructure\Repository\Auth\PasswordResetRepository;
use App\Aplication\Service\Auth\PasswordResetService;
use App\Shared\Util\PHPMailerService;

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btnRecuperar'])) {
    $correo = $_POST['correo'] ?? '';

    try {
        $db = (new Connection())->dbsistemas_proveedores;
        $repository = new PasswordResetRepository($db);
        $mailService = new PHPMailerService();
        $service = new PasswordResetService($repository, $mailService);
        
        $response = $service->resetPassword($correo);
        echo json_encode($response);
        
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error', 
            'message' => $e->getMessage()
        ]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Petición inválida.']);
}
