<?php

namespace App\Aplication\Service;

use App\Aplication\Interface\Service\IRegistroService;
use App\Domain\DTO\AdministradoresDTO;
use App\Shared\Mapper\Mapper;
use App\Infrastructure\Repository\AdministradoresRepository;
use App\Infrastructure\Database\Connection;

class RegistroService implements IRegistroService
{
    private $db;
    private $adminRepository;

    public function __construct()
    {
        $this->db = (new Connection())->dbsistemas_proveedores;
        $this->adminRepository = new AdministradoresRepository($this->db);
    }

    public function validar_email_registrado(string $email): bool
    {
        $count = $this->adminRepository->findByEmail($email);
        return $count > 0;
    }

    public function guardar_administrador(AdministradoresDTO $administradorDTO): bool
    {
        try {
            if ($this->validar_email_registrado($administradorDTO->correo_hwi_administrador)) {
                throw new \Exception("Este usuario ya se encuentra registrado.");
            }

            $administrador = Mapper::administradoresDTOToModel($administradorDTO);
            $guardado = $this->adminRepository->save($administrador);

            if (!$guardado) {
                throw new \Exception("Error al registrar, intente nuevamente.");
            }

            return true;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
