<?php

namespace App\Aplication\Interface\Repository;

use App\Domain\Model\ProveedorCommodity;

interface IProveedorCommodityRepository
{
    public function findAll(): array;
    public function findByIdProveedor(string $idProveedor): array;
    public function delete(string $idProveedor): bool;
    public function save(ProveedorCommodity $commodity): bool;
}
