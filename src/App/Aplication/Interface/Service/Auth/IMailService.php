<?php

namespace App\Aplication\Interface\Service\Auth;

interface IMailService {
    public function enviarPasswordTemporal(string $correo, string $passwordTemp): bool;
}
