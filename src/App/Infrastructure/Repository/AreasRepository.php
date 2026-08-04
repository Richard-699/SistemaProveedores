<?php

namespace App\Infrastructure\Repository;

use App\Aplication\Interface\Repository\IAreasRepository;
use App\Domain\Model\Areas;
use PDO;

class AreasRepository implements IAreasRepository
{

    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function findAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM proveedores_hwi_areas ORDER BY nombre_area ASC");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn($row) => Areas::fromArray($row), $rows);
    }
}
