<?php

namespace App\Aplication\Service\Auth;

use App\Aplication\Interface\Repository\Auth\IRegistroRepository;
use App\Aplication\Interface\Service\Auth\IRegistroService;
use App\Domain\DTO\Auth\UsuariosDTO;
use Exception;
use App\Shared\Mapper\Mapper;

class RegistroService implements IRegistroService {

    private IRegistroRepository $usuariosRepository;

    public function __construct(IRegistroRepository $usuariosRepository) {
        $this->usuariosRepository = $usuariosRepository;
    }

    public function validar_email_registrado(string $email): bool {
        $count = $this->usuariosRepository->findByEmail($email);
        return $count > 0;
    }

    public function guardar_usuario(UsuariosDTO $usuariosDTO): bool {
        $usuario = Mapper::usuariosDTOToModel($usuariosDTO);
        return $this->usuariosRepository->save($usuario);
    }
}
