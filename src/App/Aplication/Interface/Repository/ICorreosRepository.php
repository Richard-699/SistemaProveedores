<?php

namespace App\Aplication\Interface\Repository;

use App\Domain\Model\Correos;

interface ICorreosRepository
{
    public function findAll(): array;
    public function findByProveedorId(string $idProveedor): array;
    public function save(Correos $correo): bool;
    public function deleteByProveedorId(string $idProveedor): bool;
}
