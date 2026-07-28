<?php

namespace App\Aplication\Interface\Service;

interface IMailService {
    public function enviarPasswordTemporal(string $correo, string $passwordTemp): bool;
}
