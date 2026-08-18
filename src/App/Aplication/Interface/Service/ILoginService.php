<?php

namespace App\Aplication\Interface\Service;

use App\Domain\DTO\LoginDTO;
use App\Domain\DTO\ChangePasswordDTO;

interface ILoginService
{
    public function obtenerdatoslogin(LoginDTO $dto): LoginDTO;
    public function recuperarContrasena(ChangePasswordDTO $dto): bool;
}
