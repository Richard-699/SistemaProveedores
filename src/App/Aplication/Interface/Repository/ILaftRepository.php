<?php

namespace App\Aplication\Interface\Repository;

use App\Domain\Model\Laft;

interface ILaftRepository
{
    /**
     * @return Laft[]
     */
    public function findAll(): array;

    public function findByIdProveedor(string $idProveedor): ?Laft;
}
