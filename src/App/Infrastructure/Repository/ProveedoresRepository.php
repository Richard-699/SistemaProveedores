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
        $stmt = $this->db->prepare("SELECT * FROM proveedores_hwi WHERE usuario_proveedor = :usuario LIMIT 1");
        $stmt->bindParam(':usuario', $usuario);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? Proveedores::fromArray($row) : null;
    }

    public function save(Proveedores $proveedor): bool
    {
        $data = $proveedor->toArray();
        unset($data['id_proveedor']);

        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_map(fn($key) => ':' . $key, array_keys($data)));

        $stmt = $this->db->prepare(
            "INSERT INTO proveedores_hwi (id_proveedor, $columns) VALUES (UUID(), $placeholders)"
        );

        foreach ($data as $key => $value) {
            $stmt->bindParam(':' . $key, $data[$key]);
        }

        return $stmt->execute();
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
    public function getCorreosByProveedorId(string $id): array
    {
        $stmt = $this->db->prepare("SELECT correo FROM proveedores_hwi_correos WHERE id_proveedor_correo = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $correos = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $correos[] = $row['correo'];
        }
        return $correos;
    }
}
