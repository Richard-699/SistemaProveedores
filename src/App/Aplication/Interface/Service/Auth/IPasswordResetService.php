<?php

namespace App\Aplication\Interface\Service\Auth;

interface IPasswordResetService {
    /**
     * Procesa la solicitud de restablecimiento de contraseña.
     * @param string $correo Correo ingresado por el usuario
     * @return array Arreglo con status y message
     */
    public function resetPassword(string $correo): array;
}
