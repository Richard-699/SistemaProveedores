<?php

namespace App\Domain\DTO;

class ChangePasswordDTO
{
    public function __construct(
        public ?string $nuevaPassword = null,
        public ?string $password_raw = null,
        public ?string $confirmPassword = null,
        public ?bool $isAdmin = null,
        public ?string $usuarioId = null,
        public ?string $usuarioCorreo = null,
        public ?AdministradoresDTO $administradorDTO = null,
        public ?ProveedoresDTO $proveedorDTO = null,
        public array $correosList = [],
        public ?string $usuario = null,
        public ?string $tempPassword = null,
        public ?int $isTemporal = null
    ) {}
}
