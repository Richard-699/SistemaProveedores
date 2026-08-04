<?php

namespace App\Infrastructure\Repository;

use App\Aplication\Interface\Repository\IAdministradoresPermisosRepository;
use App\Domain\Model\Permisos;
use PDO;

class AdministradoresPermisosRepository implements IAdministradoresPermisosRepository {

    private $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function findPermissionsByUserId(string $idAdministrador): array {
        $stmt = $this->db->prepare("
            SELECT p.* 
            FROM proveedores_hwi_administradores_permisos ap
            JOIN proveedores_hwi_permisos p ON ap.id_permiso_administrador = p.id_permiso
            WHERE ap.id_administrador_permiso = :id
        ");
        $stmt->bindParam(':id', $idAdministrador);
        $stmt->execute();
        
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return array_map(fn($row) => Permisos::fromArray($row), $rows);
    }
}
