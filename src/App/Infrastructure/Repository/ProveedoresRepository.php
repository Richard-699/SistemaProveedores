<?php

namespace App\Infrastructure\Repository;

use App\Domain\Model\Proveedores;
use App\Aplication\Interface\Repository\IProveedoresRepository;
use PDO;

class ProveedoresRepository implements IProveedoresRepository
{

    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function countByUsuario(string $usuario): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM proveedores_hwi WHERE usuario_proveedor = :usuario");
        $stmt->bindParam(':usuario', $usuario);
        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }

    public function findByUsuario(string $usuario): ?Proveedores
    {
        $stmt = $this->db->prepare("
            SELECT p.* 
            FROM proveedores_hwi p
            LEFT JOIN proveedores_hwi_correos c ON p.id_proveedor = c.id_proveedor_correo
            WHERE p.usuario_proveedor = :usuario OR c.correo = :usuario
            LIMIT 1
        ");
        $stmt->bindParam(':usuario', $usuario);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? Proveedores::fromArray($row) : null;
    }

    public function save(Proveedores $proveedor): bool
    {
        try {
            $data = $proveedor->toArray();
            $columns = implode(', ', array_keys($data));
            $placeholders = implode(', ', array_map(fn($key) => ':' . $key, array_keys($data)));

            $stmt = $this->db->prepare(
                "INSERT INTO proveedores_hwi ($columns) VALUES ($placeholders)"
            );

            foreach ($data as $key => $value) {
                $stmt->bindValue(':' . $key, $value);
            }

            return $stmt->execute();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function updatePassword(Proveedores $proveedor): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE proveedores_hwi SET password_proveedor = :password, password_is_temporal_proveedor = :temporal WHERE id_proveedor = :id"
        );
        $stmt->bindParam(':password', $proveedor->password_proveedor);
        $stmt->bindParam(':temporal', $proveedor->password_is_temporal_proveedor);
        $stmt->bindParam(':id', $proveedor->id_proveedor);

        return $stmt->execute();
    }

    /**
     * @return Proveedores[]
     */
    public function findAll(): array
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM proveedores_hwi
            ORDER BY nombre_proveedor ASC
        ");
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map([Proveedores::class, 'fromArray'], $rows);
    }

    public function findById(string $id): ?Proveedores
    {
        $stmt = $this->db->prepare("SELECT * FROM proveedores_hwi WHERE id_proveedor = :id LIMIT 1");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? Proveedores::fromArray($row) : null;
    }

    public function update(Proveedores $proveedor): bool
    {
        try {
            $data = $proveedor->toArray();
            $id = $proveedor->id_proveedor;
            unset($data['id_proveedor']);

            $setClause = implode(', ', array_map(fn($key) => "$key = :$key", array_keys($data)));

            $stmt = $this->db->prepare(
                "UPDATE proveedores_hwi SET $setClause WHERE id_proveedor = :id_proveedor"
            );

            foreach ($data as $key => $value) {
                $stmt->bindValue(':' . $key, $value);
            }
            $stmt->bindValue(':id_proveedor', $id);

            return $stmt->execute();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function updateEstado(string $id, int $estado): bool
    {
        try {
            $stmt = $this->db->prepare("UPDATE proveedores_hwi SET id_estado_proveedor = :estado WHERE id_proveedor = :id");
            $stmt->bindParam(':estado', $estado, PDO::PARAM_INT);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function findByNombreSimilar(string $nombre): ?Proveedores
    {
        $stmt = $this->db->prepare("SELECT * FROM proveedores_hwi WHERE nombre_proveedor LIKE :nombre LIMIT 1");
        $stmt->bindValue(':nombre', '%' . $nombre . '%');
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? Proveedores::fromArray($row) : null;
    }

    public function findByNumeroAcreedor(string $numeroAcreedor): bool
    {
        $stmt = $this->db->prepare("SELECT id_proveedor FROM proveedores_hwi WHERE numero_acreedor_proveedor = :acreedor LIMIT 1");
        $stmt->bindValue(':acreedor', $numeroAcreedor);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }
}

