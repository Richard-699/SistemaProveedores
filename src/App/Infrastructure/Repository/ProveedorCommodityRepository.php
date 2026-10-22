<?php

namespace App\Infrastructure\Repository;

use App\Aplication\Interface\Repository\IProveedorCommodityRepository;
use App\Domain\Model\ProveedorCommodity;
use PDO;

class ProveedorCommodityRepository implements IProveedorCommodityRepository
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * @return ProveedorCommodity[]
     */
    public function findAll(): array
    {
        $stmt = $this->db->query("
            SELECT * 
            FROM proveedores_hwi_proveedor_commodity
        ");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map([ProveedorCommodity::class, 'fromArray'], $rows);
    }

    /**
     * @return ProveedorCommodity[]
     */
    public function findByIdProveedor(string $idProveedor): array
    {
        $stmt = $this->db->prepare("
            SELECT * 
            FROM proveedores_hwi_proveedor_commodity
            WHERE id_proveedor = :id
        ");
        $stmt->bindParam(':id', $idProveedor);
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map([ProveedorCommodity::class, 'fromArray'], $rows);
    }

    public function delete(string $idProveedor): bool
    {
        $stmtDel = $this->db->prepare("DELETE FROM proveedores_hwi_proveedor_commodity WHERE id_proveedor = :id");
        $stmtDel->bindParam(':id', $idProveedor);
        return $stmtDel->execute();
    }

    public function save(ProveedorCommodity $commodity): bool
    {
        try {
            $data = $commodity->toArray();
            $columns = implode(', ', array_keys($data));
            $placeholders = implode(', ', array_map(fn($key) => ':' . $key, array_keys($data)));

            $stmt = $this->db->prepare(
                "INSERT INTO proveedores_hwi_proveedor_commodity ($columns) VALUES ($placeholders)"
            );

            foreach ($data as $key => $value) {
                $stmt->bindValue(':' . $key, $data[$key]);
            }

            return $stmt->execute();
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
