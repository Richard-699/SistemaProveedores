<?php

namespace App\Aplication\Service;

use App\Aplication\Interface\Service\ILoginService;
use App\Domain\DTO\LoginDTO;
use App\Domain\DTO\ChangePasswordDTO;
use App\Domain\DTO\CorreosDTO;
use App\Shared\Mapper\Mapper;
use App\Shared\Util\Utils;
use App\Infrastructure\Repository\AdministradoresRepository;
use App\Infrastructure\Repository\AdministradoresPermisosRepository;
use App\Infrastructure\Repository\ProveedoresRepository;
use App\Infrastructure\Database\Connection;


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
            $administradorDTO = $this->adminRepository->findByCorreo($usuario);

            if (!$administradorDTO) {
                return $dto;
            }

            $administradorDTO = Mapper::modelToAdministradoresDTO($administradorDTO);

            $permisosDTO = Mapper::listModelToPermisosDTO($this->adminPermisosRepository->findPermissionsByUserId($administradorDTO->id_administrador));
            $administradorDTO->permisosDTO = $permisosDTO;
            $dto->administradorDTO = $administradorDTO;
        } else {
            $proveedorDTO = $this->proveedorRepository->findByUsuario($usuario);

            if (!$proveedorDTO) {
                return $dto;
            }

            $proveedorDTO = Mapper::modelToProveedoresDTO($proveedorDTO);

            $dto->proveedorDTO = $proveedorDTO;
        }

        return $dto;
    }
    public function recuperarContrasena(ChangePasswordDTO $dto): bool
    {
        try {
            $usuario = $dto->usuario; // el trim se hace en el handler

            $tempPassword = $dto->tempPassword;
            $hashedPassword = $dto->nuevaPassword;

            if ($dto->isAdmin) {
                $adminDTO = $this->adminRepository->findByCorreo($usuario);

                if (!$adminDTO) {
                    throw new \Exception("No se pudo obtener el id del administrador.");
                }

                $adminDTO = Mapper::modelToAdministradoresDTO($adminDTO);

                $adminDTO->password_administrador = $hashedPassword;
                $adminDTO->password_is_temporal = $dto->isTemporal;

                $adminModel = Mapper::administradoresDTOToModel($adminDTO);
                if (!$this->adminRepository->updatePassword($adminModel)) {
                    throw new \Exception("No se pudo actualizar la contraseña del administrador.");
                }
            } else {
                $proveedor = $this->proveedorRepository->findByUsuario($usuario);

                if (!$proveedor) {
                    throw new \Exception("No se pudo obtener el id del proveedor.");
                }

                $proveedorDTO = Mapper::modelToProveedoresDTO($proveedor);

                $proveedorId = $proveedorDTO->id_proveedor;
                $proveedorDTO->password_proveedor = $hashedPassword;
                $proveedorDTO->password_is_temporal_proveedor = $dto->isTemporal;

                $proveedorModel = Mapper::proveedoresDTOToModel($proveedorDTO);
                if (!$this->proveedorRepository->updatePassword($proveedorModel)) {
                    throw new \Exception("No se pudo actualizar la contraseña del proveedor.");
                }

                $correosModel = $this->proveedorRepository->getCorreosByProveedorId($proveedorId);
                if (empty($correosModel)) {
                    throw new \Exception("No se encontraron correos registrados para este proveedor.");
                }

                $dto->correosList = Mapper::listModelToCorreosDTO($correosModel);
            }

            $tipoUsuario = $dto->isAdmin ? "Administrador" : "Proveedor";
            $nombreUsuario = $dto->isAdmin ? $dto->administradorDTO->nombre_administrador : $dto->proveedorDTO->nombre_proveedor;

            if (($dto->isTemporal ?? 0) === 1) {
                $titulo = "Recuperación de Contraseña";
                $asunto = "Recuperación de Contraseña - " . $tipoUsuario;
                $contenidoHtml = "Hola " . $nombreUsuario . ",<br><br>"
                    . "Hemos recibido una solicitud para restablecer el acceso a tu cuenta en el Sistema de Proveedores de Haceb Whirlpool Industrial S.A.S.<br><br>"
                    . "A continuación encontrarás tu nueva contraseña temporal generada de forma segura:<br><br>"
                    . $tempPassword . "<br><br>"
                    . "Por motivos de seguridad, el sistema te solicitará que cambies esta contraseña inmediatamente después de iniciar sesión.<br><br>";
            } else {
                $titulo = "Cambio de Contraseña Exitoso";
                $asunto = "Cambio de Contraseña Exitoso";
                $contenidoHtml = "Hola " . $nombreUsuario . ",<br><br>"
                    . "Te informamos que la contraseña de tu cuenta en el Sistema de Proveedores de Haceb Whirlpool Industrial S.A.S. ha sido actualizada de manera exitosa.<br><br>"
                    . "Si no fuiste tú quien realizó este cambio, por favor contacta inmediatamente con el administrador del sistema.<br><br>";
            }

            $correosStrings = $dto->isAdmin ? [$dto->usuario] : array_map(fn($c) => $c->correo, $dto->correosList);
            if (!empty($correosStrings)) {
                $enviado = Utils::enviarCorreo($correosStrings, $asunto, $titulo, $contenidoHtml);
                if (!$enviado) {
                    throw new \Exception("Hubo un error al enviar el correo.");
                }
            }

            return true;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
