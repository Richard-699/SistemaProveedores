<?php

namespace App\Aplication\Interface\Repository;

interface IChangePasswordRepository {
    /**
     * Actualiza la contraseña de un usuario o administrador y quita la bandera temporal
     */
    public function updatePassword(bool $isAdmin, string $userId, string $hashedPassword): bool;
}
