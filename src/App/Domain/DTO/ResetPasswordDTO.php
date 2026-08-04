<?php

namespace App\Domain\DTO;

class ResetPasswordDTO
{
    public function __construct(
        public string $correo
    ) {}
}
