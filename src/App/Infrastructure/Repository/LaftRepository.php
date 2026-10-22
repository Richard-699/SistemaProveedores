<?php

namespace App\Infrastructure\Repository;

use App\Aplication\Interface\Repository\ILaftRepository;
use App\Domain\Model\Laft;
use PDO;

class LaftRepository implements ILaftRepository
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * @return Laft[]
     */
    public function findAll(): array
    {
        $stmt = $this->db->query("
            SELECT *
            FROM proveedores_hwi_laft
        ");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map([Laft::class, 'fromArray'], $rows);
    }

    public function findByIdProveedor(string $idProveedor): ?Laft
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM proveedores_hwi_laft
            WHERE id_proveedor_laft = :id
            LIMIT 1
        ");
        $stmt->bindParam(':id', $idProveedor);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? Laft::fromArray($row) : null;
    }
}
