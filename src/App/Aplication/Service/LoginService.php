<?php

namespace App\Aplication\Service;

use App\Aplication\Interface\Service\ILoginService;

use App\Domain\DTO\LoginDTO;
use App\Domain\DTO\ResetPasswordDTO;
use App\Domain\DTO\ChangePasswordDTO;
use App\Domain\DTO\AdministradoresDTO;
use App\Domain\DTO\ProveedoresDTO;
use App\Domain\Model\Administradores;
use App\Domain\Model\Proveedores;
use App\Shared\Mapper\Mapper;
use App\Infrastructure\Repository\AdministradoresRepository;
use App\Infrastructure\Repository\AdministradoresPermisosRepository;
use App\Infrastructure\Repository\ProveedoresRepository;
use App\Infrastructure\Database\Connection;
use App\Shared\Util\Utils;
use App\Shared\Util\EmailTemplates;


class LoginService implements ILoginService
{
    private $db;
    private $adminRepository;
    private $adminPermisosRepository;
    private $proveedorRepository;

    public function __construct()
    {
        $this->db = (new Connection())->dbsistemas_proveedores;
        $this->adminRepository = new AdministradoresRepository($this->db);
        $this->adminPermisosRepository = new AdministradoresPermisosRepository($this->db);
        $this->proveedorRepository = new ProveedoresRepository($this->db);
    }

    public function obtenerdatoslogin(LoginDTO $dto): LoginDTO
    {
        $usuario = $dto->usuario;
        $isAdmin = $dto->isAdmin;

        if ($isAdmin) {
            $administradorDTO = Mapper::modelToAdministradoresDTO($this->adminRepository->findByCorreo($usuario));

            if (!$administradorDTO) {
                return $dto;
            }

            $permisosDTO = Mapper::listModelToPermisosDTO($this->adminPermisosRepository->findPermissionsByUserId($administradorDTO->id_administrador));
            $administradorDTO->permisosDTO = $permisosDTO;
            $dto->administradorDTO = $administradorDTO;
        } else {
            $proveedorDTO = Mapper::modelToProveedoresDTO($this->proveedorRepository->findByUsuario($usuario));

            if (!$proveedorDTO) {
                return $dto;
            }

            $dto->proveedorDTO = $proveedorDTO;
        }

        return $dto;
    }

    public function obtenerDatosReset(ResetPasswordDTO $dto): ResetPasswordDTO
    {
        $correo = trim($dto->correo);
        $dominio = explode('@', strtolower($correo))[1] ?? '';
        $dto->isAdmin = (strpos($dominio, 'whirlpool') !== false || strpos($dominio, 'haceb') !== false);

        if ($dto->isAdmin) {
            $admin = $this->adminRepository->findByCorreo($correo);
            if ($admin) {
                $dto->administradorDTO = Mapper::modelToAdministradoresDTO($admin);
                $dto->correosList = [$admin->correo_hwi_administrador];
            }
        } else {
            $proveedor = $this->proveedorRepository->findByUsuario($correo);
            if ($proveedor) {
                $dto->proveedorDTO = Mapper::modelToProveedoresDTO($proveedor);
                
                $correosList = $this->proveedorRepository->getCorreosByProveedorId($proveedor->id_proveedor);
                if (empty($correosList)) {
                    $correosList = filter_var($correo, FILTER_VALIDATE_EMAIL) ? [$correo] : [];
                }
                $dto->correosList = $correosList;
            }
        }

        return $dto;
    }

    public function actualizarPasswordAdministrador(AdministradoresDTO $dto): bool
    {
        $admin = Mapper::administradoresDTOToModel($dto);
        return $this->adminRepository->updatePassword($admin);
    }

    public function actualizarPasswordProveedor(ProveedoresDTO $dto): bool
    {
        $proveedor = Mapper::proveedoresDTOToModel($dto);
        return $this->proveedorRepository->updatePassword($proveedor);
    }

    public function getCorreosProveedor(string $idProveedor): array
    {
        return $this->proveedorRepository->getCorreosByProveedorId($idProveedor);
    }
}
