<?php

namespace App\Domain\DTO;

class ChangePasswordDTO
{
    public function __construct(
        public string $nuevaPassword,
        public string $confirmPassword,
        public bool $isAdmin,
        public string $usuarioId,
        public string $usuarioCorreo
    ) {}
}
