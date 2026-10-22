<?php

namespace App\Infrastructure\Repository;

use App\Aplication\Interface\Repository\IVinculacionRepository;
use App\Domain\Model\Vinculacion;
use PDO;

class VinculacionRepository implements IVinculacionRepository
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * @return Vinculacion[]
     */
    public function findAll(): array
    {
        $stmt = $this->db->query("
            SELECT *
            FROM proveedores_hwi_vinculacion
        ");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map([Vinculacion::class, 'fromArray'], $rows);
    }

    public function findByIdProveedor(string $idProveedor): ?Vinculacion
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM proveedores_hwi_vinculacion
            WHERE id_proveedor_vinculacion_proveedor = :id
            LIMIT 1
        ");
        $stmt->bindParam(':id', $idProveedor);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? Vinculacion::fromArray($row) : null;
    }
}
