<?php

namespace App\Domain\DTO;

class ResetPasswordDTO
{
    /**
     * @param CorreosDTO[] $correosList
     */
    public function __construct(
        public string $usuario,
        public array $correosList = [],
        public bool $isAdmin = false,
        public ?AdministradoresDTO $administradorDTO = null,
        public ?ProveedoresDTO $proveedorDTO = null,
        public ?string $tempPassword = null
    ) {}
}
