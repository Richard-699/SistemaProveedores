<?php

namespace App\Infrastructure\Repository;

use App\Aplication\Interface\Repository\IGestionAmbientalRepository;
use App\Domain\Model\GestionAmbiental;
use PDO;

class GestionAmbientalRepository implements IGestionAmbientalRepository
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * @return GestionAmbiental[]
     */
    public function findAll(): array
    {
        $stmt = $this->db->query("
            SELECT *
            FROM proveedores_hwi_gestion_ambiental
        ");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map([GestionAmbiental::class, 'fromArray'], $rows);
    }

    public function findByIdProveedor(string $idProveedor): ?GestionAmbiental
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM proveedores_hwi_gestion_ambiental
            WHERE id_proveedor_gestion_ambiental = :id
            LIMIT 1
        ");
        $stmt->bindParam(':id', $idProveedor);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? GestionAmbiental::fromArray($row) : null;
    }
}
