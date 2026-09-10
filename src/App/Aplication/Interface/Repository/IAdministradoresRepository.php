<?php

namespace App\Aplication\Interface\Repository;

use App\Domain\Model\Administradores;

interface IAdministradoresRepository
{
    public function findByEmail(string $correo): ?int;
    public function findByCorreo(string $correo): ?Administradores;
    public function save(Administradores $administrador): bool;
    public function updatePassword(Administradores $administrador): bool;
    /**
     * @return Administradores[]
     */
    public function findAdministradorByIdPermisoandIdEstado(int $idPermiso, int $idEstado): array;

    /**
     * @return Administradores[]
     */
    public function findAll(): array;
    public function updateEstado(string $id, int $estado): bool;
    public function eliminarAdministrador(string $id): bool;
    public function findById(string $id): ?Administradores;
}
