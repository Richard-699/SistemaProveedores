<?php

namespace App\Infrastructure\Repository\Auth;

use App\Aplication\Interface\Repository\Auth\ILoginRepository;
use PDO;

class LoginRepository implements ILoginRepository {
    
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function findUserForAuth(string $usuario, bool $isAdmin): ?array {
        if ($isAdmin) {

            $stmt = $this->db->prepare("SELECT * FROM proveedores_hwi_administradores WHERE correo_hwi_administrador = :usuario LIMIT 1");
        } else {

            $stmt = $this->db->prepare("SELECT * FROM proveedores_hwi WHERE usuario_proveedor = :usuario LIMIT 1");
        }
        
        $stmt->bindParam(':usuario', $usuario);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $row ?: null;
    }

    public function findPermissionsByUserId(string $idAdministrador): array {
        $stmt = $this->db->prepare("
            SELECT p.id_permiso 
            FROM proveedores_hwi_administradores_permisos ap
            JOIN proveedores_hwi_permisos p ON ap.id_permiso_administrador = p.id_permiso
            WHERE ap.id_administrador_permiso = :id
        ");
        $stmt->bindParam(':id', $idAdministrador);
        $stmt->execute();
        
        $permisos = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $permisos[] = (int) $row['id_permiso'];
        }
        
        return $permisos;
    }
}
