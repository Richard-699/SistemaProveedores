<?php

namespace App\Domain\DTO\Auth;

class LoginDTO {
    public function __construct(
        public string $usuario,
        public string $password
    ) {}
}
