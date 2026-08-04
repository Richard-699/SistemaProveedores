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

    public function restablecerContrasena(ResetPasswordDTO $dto): array
    {
        $tempPassword = Utils::generarContrasenaTemporal();
        $hashedPassword = password_hash($tempPassword, PASSWORD_DEFAULT);

        if ($dto->isAdmin) {
            $admin = Mapper::administradoresDTOToModel($dto->administradorDTO);
            $admin->password_administrador = $hashedPassword;
            $admin->password_is_temporal = 1;
            $this->adminRepository->updatePassword($admin);

            $asunto = "Recuperación de Contraseña - Administrador";
            $cuerpo = EmailTemplates::getResetPasswordTemplate($tempPassword);
            $enviado = Utils::enviarCorreo($asunto, $cuerpo, $dto->correosList);

            if (!$enviado) {
                throw new \Exception("Se actualizó la contraseña pero hubo un error al enviar el correo.");
            }
        } else {
            $proveedor = Mapper::proveedoresDTOToModel($dto->proveedorDTO);
            $proveedor->password_proveedor = $hashedPassword;
            $proveedor->password_is_temporal_proveedor = 1;
            $this->proveedorRepository->updatePassword($proveedor);

            $asunto = "Recuperación de Contraseña - Proveedor";
            $cuerpo = EmailTemplates::getResetPasswordTemplate($tempPassword);
            $enviado = Utils::enviarCorreo($asunto, $cuerpo, $dto->correosList);

            if (!$enviado) {
                throw new \Exception("Se actualizó la contraseña pero hubo un error al enviar el correo a los contactos del proveedor.");
            }
        }

        return [
            'status' => 'success',
            'message' => 'Se ha enviado una contraseña temporal a tu correo.'
        ];
    }

    public function cambiarContrasenaTemporal(ChangePasswordDTO $dto): array
    {
        $hashedPassword = password_hash($dto->nuevaPassword, PASSWORD_DEFAULT);

        if ($dto->isAdmin) {
            $adminDTO = new AdministradoresDTO(
                id_administrador: $dto->usuarioId,
                password_administrador: $hashedPassword
            );
            $admin = Mapper::administradoresDTOToModel($adminDTO);
            $this->adminRepository->updatePassword($admin);
            
            $asunto = "Cambio de Contraseña Exitoso";
            $cuerpo = EmailTemplates::getPasswordChangedTemplate();
            Utils::enviarCorreo($asunto, $cuerpo, $dto->usuarioCorreo);
            
            $redirect = "../../../../../Views/Admin/index.php";
        } else {
            $proveedorDTO = new ProveedoresDTO(
                id_proveedor: $dto->usuarioId,
                password_proveedor: $hashedPassword
            );
            $proveedor = Mapper::proveedoresDTOToModel($proveedorDTO);
            $this->proveedorRepository->updatePassword($proveedor);

            $correosList = $this->proveedorRepository->getCorreosByProveedorId($dto->usuarioId);
            if (!empty($correosList)) {
                $asunto = "Cambio de Contraseña Exitoso";
                $cuerpo = EmailTemplates::getPasswordChangedTemplate();
                Utils::enviarCorreo($asunto, $cuerpo, $correosList);
            }
            
            $redirect = "../../../../../Views/Supplier/index.php";
        }

        return [
            'status' => 'success',
            'message' => 'Tu contraseña ha sido actualizada exitosamente.',
            'redirect' => $redirect
        ];
    }
}
