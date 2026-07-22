<?php

namespace App\Aplication\Interface\Service\Auth;

use App\Domain\DTO\Auth\LoginDTO;

interface ILoginService {
    /**
     * Autentica al usuario y devuelve sus datos de sesión o lanza una excepción en caso de error
     * @param LoginDTO $dto
     * @return array
     * @throws \Exception
     */
    public function autenticar(LoginDTO $dto): array;
}
