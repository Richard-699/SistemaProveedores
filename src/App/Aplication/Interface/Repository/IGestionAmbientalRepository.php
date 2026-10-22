<?php

namespace App\Aplication\Interface\Repository;

use App\Domain\Model\GestionAmbiental;

interface IGestionAmbientalRepository
{
    /**
     * @return GestionAmbiental[]
     */
    public function findAll(): array;

    public function findByIdProveedor(string $idProveedor): ?GestionAmbiental;
}
