<?php

namespace App\Infrastructure\Repository\Auth;

use App\Aplication\Interface\Repository\Auth\IPasswordResetRepository;
use PDO;

class PasswordResetRepository implements IPasswordResetRepository {
    
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function findUserByEmail(string $correo): ?array {
        // 1. Buscar en administradores
        $stmtAdmin = $this->db->prepare("SELECT id_administrador FROM proveedores_hwi_administradores WHERE correo_hwi_administrador = :correo LIMIT 1");
        $stmtAdmin->bindParam(':correo', $correo);
        $stmtAdmin->execute();
        
        if ($rowAdmin = $stmtAdmin->fetch(PDO::FETCH_ASSOC)) {
            return [
                'type' => 'admin',
                'id' => $rowAdmin['id_administrador']
            ];
        }

        // 2. Buscar en correos de proveedores
        $stmtProvCorreo = $this->db->prepare("SELECT id_proveedor_correo FROM proveedores_hwi_correos WHERE correo = :correo LIMIT 1");
        $stmtProvCorreo->bindParam(':correo', $correo);
        $stmtProvCorreo->execute();
        
        if ($rowProvCorreo = $stmtProvCorreo->fetch(PDO::FETCH_ASSOC)) {
            $idProv = $rowProvCorreo['id_proveedor_correo'];
            $stmtProv = $this->db->prepare("SELECT id_proveedor FROM proveedores_hwi WHERE id_proveedor = :id LIMIT 1");
            $stmtProv->bindParam(':id', $idProv);
            $stmtProv->execute();
            
            if ($rowProv = $stmtProv->fetch(PDO::FETCH_ASSOC)) {
                return [
                    'type' => 'proveedor',
                    'id' => $rowProv['id_proveedor']
                ];
            }
        }

        return null;
    }

    public function updateTemporaryPassword(string $userType, string $userId, string $hashedPassword): bool {
        if ($userType === 'admin') {
            $stmtUpdate = $this->db->prepare("UPDATE proveedores_hwi_administradores SET password_administrador = :pass, password_is_temporal = 1 WHERE id_administrador = :id");
        } else {
            $stmtUpdate = $this->db->prepare("UPDATE proveedores_hwi SET password_proveedor = :pass, password_is_temporal_proveedor = 1 WHERE id_proveedor = :id");
        }
        
        $stmtUpdate->bindParam(':pass', $hashedPassword);
        $stmtUpdate->bindParam(':id', $userId);
        return $stmtUpdate->execute();
    }
}
