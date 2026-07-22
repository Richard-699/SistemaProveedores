<?php

namespace App\Infrastructure\Repository\Auth;

use App\Aplication\Interface\Repository\Auth\IChangePasswordRepository;
use PDO;

class ChangePasswordRepository implements IChangePasswordRepository {
    
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function updatePassword(bool $isAdmin, string $userId, string $hashedPassword): bool {
        if ($isAdmin) {
            $stmtUpdate = $this->db->prepare("UPDATE proveedores_hwi_administradores SET password_administrador = :pass, password_is_temporal = 0 WHERE id_administrador = :id");
        } else {
            $stmtUpdate = $this->db->prepare("UPDATE proveedores_hwi SET password_proveedor = :pass, password_is_temporal_proveedor = 0 WHERE id_proveedor = :id");
        }
        
        $stmtUpdate->bindParam(':pass', $hashedPassword);
        $stmtUpdate->bindParam(':id', $userId);
        
        return $stmtUpdate->execute();
    }
}
