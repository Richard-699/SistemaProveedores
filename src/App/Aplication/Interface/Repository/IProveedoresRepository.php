<?php

namespace App\Aplication\Interface\Repository;

use App\Domain\Model\Proveedores;

interface IProveedoresRepository
{

    public function countByUsuario(string $usuario): int;
    public function findByUsuario(string $usuario): ?Proveedores;
    public function save(Proveedores $proveedor): bool;
    public function updatePassword(Proveedores $proveedor): bool;
    public function findAll(): array;
    public function findById(string $id): ?Proveedores;
    public function update(Proveedores $proveedor): bool;
    public function updateEstado(string $id, int $estado): bool;
    public function findByNombreSimilar(string $nombre): ?Proveedores;
    public function findByNumeroAcreedor(string $numeroAcreedor): bool;
}
