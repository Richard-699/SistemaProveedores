<?php
session_start();
require_once __DIR__ . '/../../../../vendor/autoload.php';

use App\Infrastructure\Database\Connection;
use App\Infrastructure\Repository\PasswordResetRepository;
use App\Aplication\Service\PasswordResetService;
use App\Shared\Util\PHPMailerService;

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btnRecuperar'])) {
    try {
        $resetDTO = new \App\Domain\DTO\ResetPasswordDTO(
            correo: $_POST['correo'] ?? ''
        );

        $errors = \App\Shared\Validation\Validator::validateDTO($resetDTO);
        if (!empty($errors)) {
            echo json_encode(['status' => 'error', 'message' => reset($errors)]);
            exit;
        }

        $db = (new Connection())->dbsistemas_proveedores;
        $repository = new PasswordResetRepository($db);
        $mailService = new PHPMailerService();
        $service = new PasswordResetService($repository, $mailService);

        $response = $service->resetPassword($resetDTO->correo);
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
