<?php

namespace App\Aplication\Service;

use App\Aplication\Interface\Service\IChangePasswordService;
use App\Aplication\Interface\Repository\IChangePasswordRepository;
use Exception;

class ChangePasswordService implements IChangePasswordService {

    private IChangePasswordRepository $repository;

    public function __construct(IChangePasswordRepository $repository) {
        $this->repository = $repository;
    }

    public function changePassword(string $nuevaPassword, bool $isAdmin, string $userId): array {
        
        if (strlen($nuevaPassword) < 8) {
            throw new Exception("La contraseña debe tener al menos 8 caracteres.");
        }

        $hashedPassword = password_hash($nuevaPassword, PASSWORD_DEFAULT);
        
        $updated = $this->repository->updatePassword($isAdmin, $userId, $hashedPassword);
        
        if (!$updated) {
            throw new Exception("No se pudo actualizar la contraseña.");
        }

        // Determinar la redirección según el rol
        if ($isAdmin) {
            $redirect = "../../../../../Views/Admin/index.php";
        } else {
            $redirect = "../../../../../Views/Supplier/index.php";
        }
        
        return [
            'status' => 'success', 
            'message' => 'Tu contraseña ha sido actualizada exitosamente.',
            'redirect' => $redirect
        ];
    }
}
