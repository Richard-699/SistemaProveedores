<?php

namespace App\Infrastructure\Repository;

use App\Aplication\Interface\Repository\ICostbreakdownRepository;
use App\Domain\Model\Costbreakdown;
use PDO;

class CostbreakdownRepository implements ICostbreakdownRepository
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * @return Costbreakdown[]
     */
    public function findAll(): array
    {
        $stmt = $this->db->query("
            SELECT *
            FROM proveedores_hwi_costbreakdown
        ");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map([Costbreakdown::class, 'fromArray'], $rows);
    }

    public function findByIdProveedor(string $idProveedor): ?Costbreakdown
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM proveedores_hwi_costbreakdown
            WHERE id_proveedor_costbreakdow = :id
            LIMIT 1
        ");
        $stmt->bindParam(':id', $idProveedor);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? Costbreakdown::fromArray($row) : null;
    }
}
