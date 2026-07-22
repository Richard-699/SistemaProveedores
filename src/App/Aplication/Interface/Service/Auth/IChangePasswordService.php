<?php

namespace App\Aplication\Interface\Service\Auth;

interface IChangePasswordService {
    /**
     * Procesa el cambio de contraseña
     * @param string $nuevaPassword La nueva contraseña a encriptar
     * @param bool $isAdmin Si el usuario es administrador
     * @param string $userId El ID del usuario en sesión
     * @return array Arreglo con status, message y redirect
     */
    public function changePassword(string $nuevaPassword, bool $isAdmin, string $userId): array;
}
