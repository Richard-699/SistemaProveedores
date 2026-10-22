<?php

namespace App\Aplication\Interface\Repository;

use App\Domain\Model\Costbreakdown;

interface ICostbreakdownRepository
{
    /**
     * @return Costbreakdown[]
     */
    public function findAll(): array;

    public function findByIdProveedor(string $idProveedor): ?Costbreakdown;
}
