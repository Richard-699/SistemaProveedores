<?php

namespace App\Infrastructure\Repository;

use App\Aplication\Interface\Repository\IEstadosRepository;
use App\Domain\Model\Estados;
use PDO;

class EstadosRepository implements IEstadosRepository
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function findAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM proveedores_hwi_estados ORDER BY id_estado ASC");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map([Estados::class, 'fromArray'], $rows);
    }
}
