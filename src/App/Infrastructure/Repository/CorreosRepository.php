<?php

namespace App\Infrastructure\Repository;

use App\Aplication\Interface\Repository\ICorreosRepository;
use App\Domain\Model\Correos;
use PDO;

class CorreosRepository implements ICorreosRepository
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * @return Correos[]
     */
    public function findAll(): array
    {
        $stmt = $this->db->query("
            SELECT id_correo, id_proveedor_correo, correo 
            FROM proveedores_hwi_correos
        ");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map([Correos::class, 'fromArray'], $rows);
    }

    /**
     * @return Correos[]
     */
    public function findByProveedorId(string $idProveedor): array
    {
        $stmt = $this->db->prepare("
            SELECT id_correo, id_proveedor_correo, correo 
            FROM proveedores_hwi_correos 
            WHERE id_proveedor_correo = :id
        ");
        $stmt->bindParam(':id', $idProveedor);
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map([Correos::class, 'fromArray'], $rows);
    }

    public function save(Correos $correo): bool
    {
        try {
            $data = $correo->toArray();
            $columns = implode(', ', array_keys($data));
            $placeholders = implode(', ', array_map(fn($key) => ':' . $key, array_keys($data)));

            $stmt = $this->db->prepare(
                "INSERT INTO proveedores_hwi_correos ($columns) VALUES ($placeholders)"
            );

            foreach ($data as $key => $value) {
                $stmt->bindValue(':' . $key, $value);
            }

            return $stmt->execute();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function deleteByProveedorId(string $idProveedor): bool
    {
        $stmt = $this->db->prepare("
            DELETE FROM proveedores_hwi_correos 
            WHERE id_proveedor_correo = :id
        ");
        $stmt->bindParam(':id', $idProveedor);
        return $stmt->execute();
    }
}
