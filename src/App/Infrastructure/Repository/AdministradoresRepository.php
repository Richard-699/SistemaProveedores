<?php

namespace App\Infrastructure\Repository;

use App\Domain\Model\Administradores;
use App\Aplication\Interface\Repository\IAdministradoresRepository;
use PDO;

class AdministradoresRepository implements IAdministradoresRepository {

    private $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function findByEmail(string $correo): ?int {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM proveedores_hwi_administradores WHERE correo_hwi_administrador = :correo");
        $stmt->bindParam(':correo', $correo);
        $stmt->execute();
        
        return (int) $stmt->fetchColumn();
    }

    public function findByCorreoForAuth(string $correo): ?array {
        $stmt = $this->db->prepare("SELECT * FROM proveedores_hwi_administradores WHERE correo_hwi_administrador = :correo LIMIT 1");
        $stmt->bindParam(':correo', $correo);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
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
        
        $permisos = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $permisos[] = $row;
        }
        
        return $permisos;
    }

    public function save(Administradores $administrador): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO proveedores_hwi_administradores 
            (id_administrador, nombre_administrador, apellidos_administrador, correo_hwi_administrador, id_area_administrador, password_administrador, id_estado_administrador, password_is_temporal) 
            VALUES (UUID(), :nombre, :apellidos, :correo, :id_area, :password, :estado, :is_temporal)"
        );
        $stmt->bindParam(':nombre', $administrador->nombre_administrador);
        $stmt->bindParam(':apellidos', $administrador->apellidos_administrador);
        $stmt->bindParam(':correo', $administrador->correo_hwi_administrador);
        
        $id_area = (int) $administrador->id_area_administrador;
        $stmt->bindParam(':id_area', $id_area, PDO::PARAM_INT);
        
        $stmt->bindParam(':password', $administrador->password_administrador);
        
        $estado = $administrador->id_estado_administrador ?? 1;
        $stmt->bindParam(':estado', $estado, PDO::PARAM_INT);
        
        $is_temp_int = $administrador->password_is_temporal ? 1 : 0;
        $stmt->bindParam(':is_temporal', $is_temp_int, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function updatePassword(Administradores $administrador): bool {
        $stmt = $this->db->prepare(
            "UPDATE proveedores_hwi_administradores SET password_administrador = :password WHERE id_administrador = :id"
        );
        $stmt->bindParam(':password', $administrador->password_administrador);
        $stmt->bindParam(':id', $administrador->id_administrador);

        return $stmt->execute();
    }
}
