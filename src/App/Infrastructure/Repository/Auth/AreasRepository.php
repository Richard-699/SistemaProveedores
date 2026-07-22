<?php

namespace App\Infrastructure\Repository\Auth;

use App\Aplication\Interface\Repository\Auth\IAreasRepository;
use PDO;

class AreasRepository implements IAreasRepository {

    private $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function getAllAreas(): array {
        $stmt = $this->db->query("SELECT * FROM proveedores_hwi_areas ORDER BY nombre_area ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
