<?php

namespace App\Infrastructure\Repository\Auth;

use App\Domain\Model\Auth\User;
use App\Aplication\Interface\Repository\Auth\IRegistroRepository;
use PDO;

class UsuariosRepository implements IRegistroRepository {

    private $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function findByEmail(string $correo): int {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM proveedores_hwi_administradores WHERE correo_hwi_administrador = :correo");
        $stmt->bindParam(':correo', $correo);
        $stmt->execute();
        
        return (int) $stmt->fetchColumn();
    }

    public function save(User $user): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO proveedores_hwi_administradores 
            (id_administrador, nombre_administrador, apellidos_administrador, correo_hwi_administrador, id_area_administrador, password_administrador, estado_administrador, password_is_temporal) 
            VALUES (UUID(), :nombre, :apellidos, :correo, :id_area, :password, :estado, :is_temporal)"
        );
        $stmt->bindParam(':nombre', $user->nombre_usuario);
        $stmt->bindParam(':apellidos', $user->apellidos_usuario);
        $stmt->bindParam(':correo', $user->correo_usuario);
        
        $id_area = (int) $user->area_pertenece;
        $stmt->bindParam(':id_area', $id_area, PDO::PARAM_INT);
        
        $stmt->bindParam(':password', $user->password_usuario);
        
        $estado = $user->estado_usuario ?? 1;
        $stmt->bindParam(':estado', $estado, PDO::PARAM_INT);
        
        $is_temp_int = $user->is_temporal ? 1 : 0;
        $stmt->bindParam(':is_temporal', $is_temp_int, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function updatePassword(User $user): bool {
        $stmt = $this->db->prepare(
            "UPDATE proveedores_hwi_administradores SET password_administrador = :password WHERE id_administrador = :id"
        );
        $stmt->bindParam(':password', $user->password_usuario);
        $stmt->bindParam(':id', $user->id_usuario);

        return $stmt->execute();
    }
}
