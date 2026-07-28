<?php

namespace App\Aplication\Interface\Repository;

interface IPasswordResetRepository {
    /**
     * Busca el correo en la base de datos de administradores o proveedores_hwi.
     * Retorna un arreglo con el tipo de usuario ('admin' o 'proveedor') y su respectivo ID.
     * Retorna null si no se encuentra.
     */
    public function findUserByEmail(string $correo): ?array;

    /**
     * Actualiza la contraseña en la base de datos y la marca como temporal.
     */
    public function updateTemporaryPassword(string $userType, string $userId, string $hashedPassword): bool;
}
