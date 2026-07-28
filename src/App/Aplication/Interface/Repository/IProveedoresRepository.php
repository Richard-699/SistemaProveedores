<?php

namespace App\Aplication\Interface\Repository;

use App\Domain\Model\Proveedores;

interface IProveedoresRepository {
    // You can define methods specific to Proveedores here in the future
    // like findByUsuario, save, updatePassword, etc.
    public function findByUsuario(string $usuario): ?int;
    public function findByUsuarioForAuth(string $usuario): ?array;
    public function save(Proveedores $proveedor): bool;
    public function updatePassword(Proveedores $proveedor): bool;
}
