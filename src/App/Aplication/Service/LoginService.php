<?php

namespace App\Aplication\Service;

use App\Aplication\Interface\Service\ILoginService;

use App\Domain\DTO\LoginDTO;
use App\Domain\DTO\AdministradoresDTO;
use App\Domain\DTO\ProveedoresDTO;
use App\Domain\Model\Administradores;
use App\Domain\Model\Proveedores;
use App\Shared\Mapper\Mapper;
use App\Infrastructure\Repository\AdministradoresRepository;
use App\Infrastructure\Repository\ProveedoresRepository;
use App\Infrastructure\Database\Connection;


class LoginService implements ILoginService
{
    private $db;
    private $adminRepository;
    private $proveedorRepository;

    public function __construct()
    {
        $this->db = (new Connection())->dbsistemas_proveedores;
        $this->adminRepository = new AdministradoresRepository($this->db);
        $this->proveedorRepository = new ProveedoresRepository($this->db);
    }

    public function obtenerDatosUsuario(LoginDTO $dto): LoginDTO
    {
        $usuario = $dto->usuario;
        $isAdmin = $dto->isAdmin;

        if ($isAdmin) {
            $userData = $this->adminRepository->findByCorreoForAuth($usuario);
            
            if (!$userData) {
                return $dto;
            }

            $permisosRaw = $this->adminRepository->findPermissionsByUserId($userData['id_administrador']);
            $permisosModels = [];
            foreach ($permisosRaw as $permisoRow) {
                $permisosModels[] = \App\Domain\Model\Permisos::fromArray($permisoRow);
            }
            $permisosDTOs = Mapper::listModelToPermisosDTO($permisosModels);

            $dto->administradorDTO = Mapper::modelToAdministradoresDTO(Administradores::fromArray($userData), $permisosDTOs);
        } else {
            $userData = $this->proveedorRepository->findByUsuarioForAuth($usuario);
            
            if (!$userData) {
                return $dto;
            }

            $dto->proveedorDTO = Mapper::modelToProveedoresDTO(Proveedores::fromArray($userData));
        }

        return $dto;
    }
}
