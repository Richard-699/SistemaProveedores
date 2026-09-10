<?php

namespace App\Infrastructure\Repository;

use App\Domain\Model\Administradores;
use App\Aplication\Interface\Repository\IAdministradoresRepository;
use PDO;

class AdministradoresRepository implements IAdministradoresRepository
{

    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function findByEmail(string $correo): ?int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM proveedores_hwi_administradores WHERE correo_hwi_administrador = :correo");
        $stmt->bindParam(':correo', $correo);
        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }

    public function findByCorreo(string $correo): ?Administradores
    {
        $stmt = $this->db->prepare("SELECT * FROM proveedores_hwi_administradores WHERE correo_hwi_administrador = :correo LIMIT 1");
        $stmt->bindParam(':correo', $correo);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? Administradores::fromArray($row) : null;
    }

    public function save(Administradores $administrador): bool
    {
        try {

            $data = $administrador->toArray();
            $columns = implode(', ', array_keys($data));
            $placeholders = implode(', ', array_map(fn($key) => ':' . $key, array_keys($data)));

            $stmt = $this->db->prepare(
                "INSERT INTO proveedores_hwi_administradores ($columns) VALUES ($placeholders)"
            );

            foreach ($data as $key => $value) {
                $stmt->bindParam(':' . $key, $data[$key]);
            }

            return $stmt->execute();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function updatePassword(Administradores $administrador): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE proveedores_hwi_administradores SET password_administrador = :password, password_is_temporal = :temporal WHERE id_administrador = :id"
        );
        $stmt->bindParam(':password', $administrador->password_administrador);
        $stmt->bindParam(':temporal', $administrador->password_is_temporal);
        $stmt->bindParam(':id', $administrador->id_administrador);

        return $stmt->execute();
    }

    /**
     * @return Administradores[]
     */
    public function findAdministradorByIdPermisoandIdEstado(int $idPermiso, int $idEstado): array
    {
        $stmt = $this->db->prepare("
            SELECT DISTINCT a.* 
            FROM proveedores_hwi_administradores a
            JOIN proveedores_hwi_administradores_permisos ap ON a.id_administrador = ap.id_administrador_permiso
            WHERE ap.id_permiso_administrador = :idPermiso AND a.id_estado_administrador = :idEstado
        ");
        $stmt->bindParam(':idPermiso', $idPermiso, PDO::PARAM_INT);
        $stmt->bindParam(':idEstado', $idEstado, PDO::PARAM_INT);
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $administradores = [];
        foreach ($rows as $row) {
            $administradores[] = Administradores::fromArray($row);
        }

        return $administradores;
    }

    /**
     * @return Administradores[]
     */
    public function findAll(): array
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM proveedores_hwi_administradores
        ");
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map([Administradores::class, 'fromArray'], $rows);
    }

    public function findById(string $id): ?Administradores
    {
        $stmt = $this->db->prepare("SELECT * FROM proveedores_hwi_administradores WHERE id_administrador = :id LIMIT 1");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? Administradores::fromArray($row) : null;
    }

    public function updateEstado(string $id, int $estado): bool
    {
        $stmt = $this->db->prepare("UPDATE proveedores_hwi_administradores SET id_estado_administrador = :estado WHERE id_administrador = :id");
        $stmt->bindParam(':estado', $estado, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function eliminarAdministrador(string $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM proveedores_hwi_administradores WHERE id_administrador = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
