<?php

namespace App\Domain\DTO\Auth;

class ResetPasswordDTO {
    public function __construct(
        public string $correo
    ) {}
}
