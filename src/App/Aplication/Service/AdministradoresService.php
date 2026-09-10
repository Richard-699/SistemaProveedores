<?php

namespace App\Aplication\Service;

use App\Aplication\Interface\Service\IAdministradoresService;
use App\Infrastructure\Repository\AdministradoresRepository;
use App\Infrastructure\Repository\AdministradoresPermisosRepository;
use App\Domain\DTO\AdministradoresDTO;
use App\Domain\DTO\AdministradoresPermisosDTO;
use App\Infrastructure\Repository\PermisosRepository;
use App\Infrastructure\Database\Connection;
use App\Shared\Util\Utils;
use App\Shared\Mapper\Mapper;
use App\Infrastructure\Repository\AreasRepository;
use Exception;
use Throwable;

class AdministradoresService implements IAdministradoresService
{

    private \PDO $db;
    private AdministradoresRepository $administradoresRepository;
    private AdministradoresPermisosRepository $administradoresPermisosRepository;
    private PermisosRepository $permisosRepository;
    private AreasRepository $areasRepository;

    public function __construct()
    {
        $this->db = (new Connection())->dbsistemas_proveedores;
        $this->administradoresRepository = new AdministradoresRepository($this->db);
        $this->administradoresPermisosRepository = new AdministradoresPermisosRepository($this->db);
        $this->permisosRepository = new PermisosRepository($this->db);
        $this->areasRepository = new AreasRepository($this->db);
    }

    public function obtenerAdministradorPorId(string $idAdmin): AdministradoresDTO
    {
        try {
            $administradorModel = $this->administradoresRepository->findById($idAdmin);
            if (!$administradorModel) {
                throw new Exception("El administrador no existe.");
            }
            return Mapper::modelToAdministradoresDTO($administradorModel);
        } catch (Throwable $e) {
            throw $e;
        }
    }

    public function aprobarAdministrador(AdministradoresPermisosDTO $adminPermisosDTO): bool
    {
        try {
            $this->db->beginTransaction();

            $idAdmin = $adminPermisosDTO->id_administrador_permiso;

            $administradorDTO = $this->obtenerAdministradorPorId($idAdmin);

            $idEstado = 4; // Estado: Activo / Aprobado
            $updateEstado = $this->administradoresRepository->updateEstado($idAdmin, $idEstado);
            if (!$updateEstado) {
                throw new Exception("Error al actualizar el estado del administrador.");
            }

            foreach ($adminPermisosDTO->permisosDTO as $permisoDTO) {
                $administradoresPermisosDTOload = new AdministradoresPermisosDTO(
                    id_administrador_permiso: $idAdmin,
                    id_permiso_administrador: $permisoDTO->id_permiso
                );
                $permisoModel = Mapper::administradoresPermisosDTOToModel($administradoresPermisosDTOload);

                $savePermiso = $this->administradoresPermisosRepository->save($permisoModel);
                if (!$savePermiso) {
                    throw new Exception("Error al guardar los permisos del administrador.");
                }
            }

            $asunto = "Registro Aprobado - Sistema de Proveedores";
            $titulo = "Tu cuenta ha sido aprobada";
            $contenidoHtml = "Hola " . $administradorDTO->nombre_administrador . " " . $administradorDTO->apellidos_administrador . ",<br><br>"
                . "Te informamos que tu registro en el Sistema de Proveedores de Haceb Whirlpool Industrial S.A.S. ha sido <b>aprobado</b>.<br><br>"
                . "Ya puedes ingresar al sistema utilizando tus credenciales.<br><br>";

            $correoEnviado = Utils::enviarCorreo([$administradorDTO->correo_hwi_administrador], $asunto, $titulo, $contenidoHtml);

            if (!$correoEnviado) {
                throw new Exception("Usuario aprobado, pero no se pudo enviar el correo de notificación.");
            }

            $this->db->commit();
            return true;
        } catch (Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function rechazarAdministrador(string $idAdmin): bool
    {
        try {
            $administradorDTO = $this->obtenerAdministradorPorId($idAdmin);

            $eliminado = $this->administradoresRepository->eliminarAdministrador($idAdmin);
            if (!$eliminado) {
                throw new Exception("Error al rechazar (eliminar) el administrador.");
            }

            $asunto = "Registro Rechazado - Sistema de Proveedores";
            $titulo = "Tu solicitud de registro ha sido rechazada";
            $contenidoHtml = "Hola " . $administradorDTO->nombre_administrador . " " . $administradorDTO->apellidos_administrador . ",<br><br>"
                . "Te informamos que tu solicitud de registro en el Sistema de Proveedores de Haceb Whirlpool Industrial S.A.S. ha sido <b>rechazada</b>.<br><br>"
                . "Si consideras que esto es un error, por favor contacta con el administrador del sistema.<br><br>";

            Utils::enviarCorreo([$administradorDTO->correo_hwi_administrador], $asunto, $titulo, $contenidoHtml);

            return true;
        } catch (Throwable $e) {
            throw $e;
        }
    }

    public function eliminarAdministrador(string $idAdmin): bool
    {
        try {
            $this->db->beginTransaction();

            $this->administradoresPermisosRepository->delete($idAdmin);

            $eliminado = $this->administradoresRepository->eliminarAdministrador($idAdmin);
            if (!$eliminado) {
                throw new Exception("Error al eliminar el administrador.");
            }

            $this->db->commit();
            return true;
        } catch (Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function actualizarPermisosAdministrador(AdministradoresPermisosDTO $adminPermisosDTO): bool
    {
        $idAdmin = (string) $adminPermisosDTO->id_administrador_permiso;
        try {
            $this->db->beginTransaction();

            $deletePermisos = $this->administradoresPermisosRepository->delete($idAdmin);
            if (!$deletePermisos) {
                throw new Exception("Error al limpiar los permisos previos del administrador.");
            }

            foreach ($adminPermisosDTO->permisosDTO as $idPermiso) {
                $administradoresPermisosDTOload = new AdministradoresPermisosDTO(
                    id_administrador_permiso: $idAdmin,
                    id_permiso_administrador: (int) $idPermiso
                );
                $permisoModel = Mapper::administradoresPermisosDTOToModel($administradoresPermisosDTOload);

                $savePermiso = $this->administradoresPermisosRepository->save($permisoModel);
                if (!$savePermiso) {
                    throw new Exception("Error al actualizar los permisos del administrador.");
                }
            }

            $this->db->commit();
            return true;
        } catch (Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function obtenerAdministradores(): array
    {
        try {
            $administradoresModels = $this->administradoresRepository->findAll();
            if (empty($administradoresModels)) {
                throw new Exception("No se encontraron administradores.");
            }
            $administradoresDTO = Mapper::modelToListAdministradoresDTO($administradoresModels);

            $areasModels = $this->areasRepository->findAll();
            if (empty($areasModels)) {
                throw new Exception("No se encontraron áreas.");
            }

            $areasDTO = Mapper::modelToListAreasDTO($areasModels);

            $areasMap = [];
            foreach ($areasDTO as $areaDTO) {
                $areasMap[$areaDTO->id_area] = $areaDTO->nombre_area;
            }

            foreach ($administradoresDTO as $adminDTO) {
                if ($adminDTO->id_area_administrador !== null && isset($areasMap[$adminDTO->id_area_administrador])) {
                    $adminDTO->nombre_area = $areasMap[$adminDTO->id_area_administrador];
                }
            }

            return $administradoresDTO;
        } catch (Throwable $e) {
            throw $e;
        }
    }

    public function obtenerPermisos(): array
    {
        try {
            $permisosModels = $this->permisosRepository->findAll();

            if (empty($permisosModels)) {
                throw new Exception("No se encontraron permisos registrados en el sistema.");
            }

            $permisosDTOs = Mapper::modelToListPermisosDTO($permisosModels);

            return $permisosDTOs;
        } catch (Throwable $e) {
            throw $e;
        }
    }

    public function obtenerPermisosAdmin(string $idAdministrador): AdministradoresPermisosDTO
    {
        try {
            $adminPermisosModels = $this->administradoresPermisosRepository->findByIdAdministrador($idAdministrador);
            if (empty($adminPermisosModels)) {
                throw new Exception("No se encontraron permisos para el administrador.");
            }
            $adminPermisosDTO = Mapper::modelToListAdministradoresPermisosDTO($adminPermisosModels);

            $listPermisosDTO = $this->obtenerPermisos();

            $permisosMap = [];
            foreach ($listPermisosDTO as $permisoDTO) {
                $permisosMap[$permisoDTO->id_permiso] = $permisoDTO;
            }

            $permisosAsignados = [];
            foreach ($adminPermisosDTO as $apDTO) {
                if (isset($permisosMap[$apDTO->id_permiso_administrador])) {
                    $permisosAsignados[] = $permisosMap[$apDTO->id_permiso_administrador];
                }
            }

            return new AdministradoresPermisosDTO(
                id_administrador_permiso: $idAdministrador,
                permisosDTO: $permisosAsignados
            );
        } catch (Throwable $e) {
            throw $e;
        }
    }
}
