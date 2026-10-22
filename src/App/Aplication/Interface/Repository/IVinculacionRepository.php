<?php

namespace App\Aplication\Interface\Repository;

use App\Domain\Model\Vinculacion;

interface IVinculacionRepository
{
    /**
     * @return Vinculacion[]
     */
    public function findAll(): array;

    public function findByIdProveedor(string $idProveedor): ?Vinculacion;
}
