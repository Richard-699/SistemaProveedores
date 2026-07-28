<?php

namespace App\Domain\DTO;

use App\Domain\DTO\AdministradoresDTO;
use App\Domain\DTO\ProveedoresDTO;

class LoginDTO
{
    public function __construct(
        public string $usuario,
        public string $password,
        public ?AdministradoresDTO $administradorDTO = null,
        public ?ProveedoresDTO $proveedorDTO = null,
        public bool $isAdmin = false
    ) {}
}
