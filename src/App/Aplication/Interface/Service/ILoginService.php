<?php

namespace App\Aplication\Interface\Service;

use App\Domain\DTO\LoginDTO;

interface ILoginService {
    /**
     * Busca al usuario en la base de datos y retorna el DTO populado
     * @param LoginDTO $dto
     * @return LoginDTO
     */
    public function obtenerDatosUsuario(LoginDTO $dto): LoginDTO;
}
