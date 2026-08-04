<?php

namespace App\Domain\DTO;

class ResetPasswordDTO
{
    public ?AdministradoresDTO $administradorDTO = null;
    public ?ProveedoresDTO $proveedorDTO = null;
    public ?bool $isAdmin = null;
    public array $correosList = [];

    public function __construct(
        public string $correo
    ) {}
}
