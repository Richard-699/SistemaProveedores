<?php

namespace App\Aplication\Interface\Service;

use App\Domain\DTO\AdministradoresDTO;

interface IRegistroService {
    public function validar_email_registrado(string $email): bool;
    public function guardar_administrador(AdministradoresDTO $administradorDTO): bool;
}

?>
