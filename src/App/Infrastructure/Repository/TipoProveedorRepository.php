<?php

namespace App\Infrastructure\Repository;

use App\Aplication\Interface\Repository\ITipoProveedorRepository;
use App\Domain\Model\TipoProveedor;
use PDO;

class TipoProveedorRepository implements ITipoProveedorRepository
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function findAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM proveedores_hwi_tipo_proveedor ORDER BY id_tipo_proveedor ASC");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map([TipoProveedor::class, 'fromArray'], $rows);
    }
}
