<?php
session_start();
ob_start();

require_once __DIR__ . '/../../../../../vendor/autoload.php';

use App\Aplication\Service\Auth\LoginService;
use App\Domain\DTO\Auth\LoginDTO;
use App\Infrastructure\Database\Connection;
use App\Infrastructure\Repository\Auth\LoginRepository;

header('Content-Type: application/json; charset=utf-8');

if (!empty($_POST["btningresar"])) {
    if (!empty($_POST["usuario"]) && !empty($_POST["password"])) {

        try {
            #$db = (new Connection())->dbsistemas_proveedores;
            #$loginRepository = new LoginRepository($db);
            $loginDTO = new LoginDTO(
                usuario: $_POST["usuario"],
                password: $_POST["password"]
            );

             $loginService = new LoginService();

            $sessionData = $loginService->autenticar($loginDTO);

            foreach ($sessionData as $key => $value) {
                $_SESSION[$key] = $value;
            }

            $redirect = '';

            if ($sessionData['is_temporal']) {
                $redirect = "cambiarContrasena.php";
            } else {
                if ($_SESSION['id_rol_usuarios'] == 1) {
                    $redirect = "../../../../../Views/Admin/index.php";
                } else if ($_SESSION['id_rol_usuarios'] == 3) {

                    $redirect = "../../../../../Views/Supplier/index.php";
                } else {

                    $redirect = "../../../../../Views/User/index.php";
                }
            }

            echo json_encode(['status' => 'success', 'redirect' => $redirect]);
            exit;
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
            exit;
        }
    }
}

echo json_encode(['status' => 'error', 'message' => 'Faltan datos de inicio de sesión']);
exit;
