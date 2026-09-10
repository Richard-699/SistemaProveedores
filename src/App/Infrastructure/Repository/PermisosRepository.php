<?php

namespace App\Infrastructure\Repository;

use App\Aplication\Interface\Repository\IPermisosRepository;
use App\Domain\Model\Permisos;
use PDO;

class PermisosRepository implements IPermisosRepository
{

    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function findAll(): array
    {
        $stmt = $this->db->prepare("SELECT * FROM proveedores_hwi_permisos");
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map([Permisos::class, 'fromArray'], $rows);
    }
}
