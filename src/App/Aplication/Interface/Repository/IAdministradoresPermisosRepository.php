<?php

namespace App\Aplication\Interface\Repository;

use App\Domain\Model\AdministradoresPermisos;

interface IAdministradoresPermisosRepository
{

    public function findByIdAdministrador(string $idAdministrador): array;
    public function delete(string $idAdministrador): bool;
    public function save(AdministradoresPermisos $permiso): bool;
}
