<?php

namespace App\Aplication\Interface\Repository;

use App\Domain\Model\Administradores;

interface IAdministradoresRepository {
    public function findByEmail(string $correo): ?int;
    public function findByCorreoForAuth(string $correo): ?array;
    public function findPermissionsByUserId(string $idAdministrador): array;
    public function save(Administradores $administrador): bool;
    public function updatePassword(Administradores $administrador): bool;
}
