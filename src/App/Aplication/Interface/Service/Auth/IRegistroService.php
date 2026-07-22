<?php

namespace App\Aplication\Interface\Service\Auth;

use App\Domain\DTO\Auth\UsuariosDTO;

interface IRegistroService {
    public function validar_email_registrado(string $email): bool;
    public function guardar_usuario(UsuariosDTO $usuariosDTO): bool;
}

?>
