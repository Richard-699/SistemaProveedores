<?php

namespace App\Aplication\Interface\Repository;

use App\Domain\Model\Proveedores;

interface IProveedoresRepository
{

    public function countByUsuario(string $usuario): int;
    public function findByUsuario(string $usuario): ?Proveedores;
    public function save(Proveedores $proveedor): bool;
    public function updatePassword(Proveedores $proveedor): bool;

    /**
     * @param string $id
     * @return \App\Domain\Model\Correos[]
     */
    public function getCorreosByProveedorId(string $id): array;
}
