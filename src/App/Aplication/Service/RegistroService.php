<?php

namespace App\Aplication\Service;

use App\Aplication\Interface\Repository\IAdministradoresRepository;
use App\Aplication\Interface\Service\IRegistroService;
use App\Domain\DTO\AdministradoresDTO;
use Exception;
use App\Shared\Mapper\Mapper;

class RegistroService implements IRegistroService {

    private IAdministradoresRepository $administradoresRepository;

    public function __construct(IAdministradoresRepository $administradoresRepository) {
        $this->administradoresRepository = $administradoresRepository;
    }

    public function validar_email_registrado(string $email): bool {
        $count = $this->administradoresRepository->findByEmail($email);
        return $count > 0;
    }

    public function guardar_administrador(AdministradoresDTO $administradorDTO): bool {
        $administrador = Mapper::administradoresDTOToModel($administradorDTO);
        return $this->administradoresRepository->save($administrador);
    }
}
