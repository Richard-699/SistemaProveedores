<?php

namespace App\Aplication\Interface\Repository\Auth;

interface ILoginRepository {
    public function findUserForAuth(string $usuario, bool $isAdmin): ?array;
    public function findPermissionsByUserId(string $idAdministrador): array;
}
