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
            $emailCount = $this->findByEmail($administrador->correo_hwi_administrador);
            if ($emailCount > 0) {
                throw new \Exception("Este usuario ya se encuentra registrado.");
            }

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
}
