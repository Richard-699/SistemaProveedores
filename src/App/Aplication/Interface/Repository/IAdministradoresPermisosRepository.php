<?php

namespace App\Aplication\Interface\Repository;

interface IAdministradoresPermisosRepository {
    public function findPermissionsByUserId(string $idAdministrador): array;
}
