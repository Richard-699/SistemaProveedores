<?php

namespace App\Shared\Mapper;

use App\Domain\Model\Administradores;
use App\Domain\Model\Proveedores;
use App\Domain\Model\Permisos;
use App\Domain\Model\Correos;
use App\Domain\Model\Areas;
use App\Domain\DTO\AdministradoresDTO;
use App\Domain\DTO\ProveedoresDTO;
use App\Domain\DTO\PermisosDTO;
use App\Domain\DTO\CorreosDTO;
use App\Domain\DTO\AreasDTO;
use App\Domain\Model\AdministradoresPermisos;
use App\Domain\DTO\AdministradoresPermisosDTO;
use App\Domain\Model\TipoProveedor;
use App\Domain\DTO\TipoProveedorDTO;
use App\Domain\Model\Estados;
use App\Domain\DTO\EstadosDTO;

class Mapper
{
    public static function administradoresPermisosDTOToModel(AdministradoresPermisosDTO $dto): AdministradoresPermisos
    {
        return new AdministradoresPermisos(
            id_administrador_permiso: $dto->id_administrador_permiso,
            id_permiso_administrador: $dto->id_permiso_administrador
        );
    }

    public static function modelToAdministradoresDTO(Administradores $administrador, array $permisos = [], ?string $nombre_area = null): AdministradoresDTO
    {
        return new AdministradoresDTO(
            id_administrador: $administrador->id_administrador,
            nombre_administrador: $administrador->nombre_administrador,
            apellidos_administrador: $administrador->apellidos_administrador,
            correo_hwi_administrador: $administrador->correo_hwi_administrador,
            id_area_administrador: $administrador->id_area_administrador,
            password_administrador: $administrador->password_administrador,
            id_estado_administrador: $administrador->id_estado_administrador,
            password_is_temporal: $administrador->password_is_temporal,
            permisosDTO: $permisos,
            nombre_area: $nombre_area
        );
    }

    public static function administradoresDTOToModel(AdministradoresDTO $dto): Administradores
    {
        return new Administradores(
            id_administrador: $dto->id_administrador,
            nombre_administrador: $dto->nombre_administrador,
            apellidos_administrador: $dto->apellidos_administrador,
            correo_hwi_administrador: $dto->correo_hwi_administrador,
            id_area_administrador: $dto->id_area_administrador,
            password_administrador: $dto->password_administrador,
            id_estado_administrador: $dto->id_estado_administrador,
            password_is_temporal: $dto->password_is_temporal
        );
    }

    public static function modelToPermisosDTO(Permisos $permiso): PermisosDTO
    {
        return new PermisosDTO(
            id_permiso: $permiso->id_permiso,
            nombre_permiso: $permiso->nombre_permiso,
            descripcion_permiso: $permiso->descripcion_permiso
        );
    }

    /**
     * @param Permisos[] $permisosModels
     * @return PermisosDTO[]
     */
    public static function modelToListPermisosDTO(array $permisosModels): array
    {
        if (empty($permisosModels)) {
            return [];
        }
        return array_map(function (Permisos $model): PermisosDTO {
            return new PermisosDTO(
                id_permiso: $model->id_permiso,
                nombre_permiso: $model->nombre_permiso,
                descripcion_permiso: $model->descripcion_permiso
            );
        }, $permisosModels);
    }

    /**
     * @param AdministradoresPermisos[] $models
     * @return AdministradoresPermisosDTO[]
     */
    public static function modelToListAdministradoresPermisosDTO(array $models): array
    {
        if (empty($models)) {
            return [];
        }
        return array_map(function (AdministradoresPermisos $model): AdministradoresPermisosDTO {
            return new AdministradoresPermisosDTO(
                id_administrador_permiso: $model->id_administrador_permiso,
                id_permiso_administrador: $model->id_permiso_administrador
            );
        }, $models);
    }
    public static function modelToProveedoresDTO(Proveedores $proveedor): ProveedoresDTO
    {
        return new ProveedoresDTO(
            id_proveedor: $proveedor->id_proveedor,
            numero_acreedor_proveedor: $proveedor->numero_acreedor_proveedor,
            nombre_proveedor: $proveedor->nombre_proveedor,
            id_tipo_proveedor: $proveedor->id_tipo_proveedor,
            id_idioma_proveedor: $proveedor->id_idioma_proveedor,
            id_estado_proveedor: $proveedor->id_estado_proveedor,
            maneja_formato_costbreakdown_proveedor: $proveedor->maneja_formato_costbreakdown_proveedor,
            historia_proveedor: $proveedor->historia_proveedor,
            descripcion_proveedor: $proveedor->descripcion_proveedor,
            porcentaje_bom_proveedor: $proveedor->porcentaje_bom_proveedor,
            logo_proveedor: $proveedor->logo_proveedor,
            id_srm_proveedor: $proveedor->id_srm_proveedor,
            id_categoria_proveedor: $proveedor->id_categoria_proveedor,
            id_sub_categoria_proveedor: $proveedor->id_sub_categoria_proveedor,
            formulario_ambiental_proveedor: $proveedor->formulario_ambiental_proveedor,
            permitir_carta_beneficiarios_finales_proveedor: $proveedor->permitir_carta_beneficiarios_finales_proveedor,
            id_administrador_proveedor: $proveedor->id_administrador_proveedor,
            usuario_proveedor: $proveedor->usuario_proveedor,
            password_proveedor: $proveedor->password_proveedor,
            password_is_temporal_proveedor: $proveedor->password_is_temporal_proveedor
        );
    }

    public static function proveedoresDTOToModel(ProveedoresDTO $dto): Proveedores
    {
        return new Proveedores(
            id_proveedor: $dto->id_proveedor,
            numero_acreedor_proveedor: $dto->numero_acreedor_proveedor,
            nombre_proveedor: $dto->nombre_proveedor,
            id_tipo_proveedor: $dto->id_tipo_proveedor,
            id_idioma_proveedor: $dto->id_idioma_proveedor,
            id_estado_proveedor: $dto->id_estado_proveedor,
            maneja_formato_costbreakdown_proveedor: $dto->maneja_formato_costbreakdown_proveedor,
            historia_proveedor: $dto->historia_proveedor,
            descripcion_proveedor: $dto->descripcion_proveedor,
            porcentaje_bom_proveedor: $dto->porcentaje_bom_proveedor,
            logo_proveedor: $dto->logo_proveedor,
            id_srm_proveedor: $dto->id_srm_proveedor,
            id_categoria_proveedor: $dto->id_categoria_proveedor,
            id_sub_categoria_proveedor: $dto->id_sub_categoria_proveedor,
            formulario_ambiental_proveedor: $dto->formulario_ambiental_proveedor,
            permitir_carta_beneficiarios_finales_proveedor: $dto->permitir_carta_beneficiarios_finales_proveedor,
            id_administrador_proveedor: $dto->id_administrador_proveedor,
            usuario_proveedor: $dto->usuario_proveedor,
            password_proveedor: $dto->password_proveedor,
            password_is_temporal_proveedor: $dto->password_is_temporal_proveedor
        );
    }

    /**
     * @param Permisos[] $permisos
     * @return PermisosDTO[]
     */
    public static function listModelToPermisosDTO(array $permisos): array
    {
        if (empty($permisos)) {
            return [];
        }
        return array_map(function (Permisos $model): PermisosDTO {
            return new PermisosDTO(
                id_permiso: $model->id_permiso,
                nombre_permiso: $model->nombre_permiso,
                descripcion_permiso: $model->descripcion_permiso
            );
        }, $permisos);
    }

    public static function permisosDTOToModel(PermisosDTO $dto): Permisos
    {
        return new Permisos(
            id_permiso: $dto->id_permiso,
            nombre_permiso: $dto->nombre_permiso,
            descripcion_permiso: $dto->descripcion_permiso
        );
    }

    /**
     * @param Correos[] $correos
     * @return CorreosDTO[]
     */
    public static function listModelToCorreosDTO(array $correos): array
    {
        if (empty($correos)) {
            return [];
        }
        return array_map(function (Correos $model): CorreosDTO {
            return new CorreosDTO(
                correo: $model->correo
            );
        }, $correos);
    }

    /**
     * @param Administradores[] $administradoresModels
     * @return AdministradoresDTO[]
     */
    public static function modelToListAdministradoresDTO(array $administradoresModels): array
    {
        if (empty($administradoresModels)) {
            return [];
        }
        return array_map(function (Administradores $model): AdministradoresDTO {
            return new AdministradoresDTO(
                id_administrador: $model->id_administrador,
                nombre_administrador: $model->nombre_administrador,
                apellidos_administrador: $model->apellidos_administrador,
                correo_hwi_administrador: $model->correo_hwi_administrador,
                id_area_administrador: $model->id_area_administrador,
                password_administrador: $model->password_administrador,
                id_estado_administrador: $model->id_estado_administrador,
                password_is_temporal: $model->password_is_temporal,
                permisosDTO: [],
                nombre_area: null
            );
        }, $administradoresModels);
    }

    /**
     * @param Areas[] $areasModels
     * @return AreasDTO[]
     */
    public static function modelToListAreasDTO(array $areasModels): array
    {
        if (empty($areasModels)) {
            return [];
        }
        return array_map(function (Areas $model): AreasDTO {
            return new AreasDTO(
                id_area: $model->id_area,
                nombre_area: $model->nombre_area
            );
        }, $areasModels);
    }

    /**
     * @param Proveedores[] $proveedoresModels
     * @return ProveedoresDTO[]
     */
    public static function modelToListProveedoresDTO(array $proveedoresModels): array
    {
        if (empty($proveedoresModels)) {
            return [];
        }
        return array_map(function (Proveedores $model): ProveedoresDTO {
            return new ProveedoresDTO(
                id_proveedor: $model->id_proveedor,
                numero_acreedor_proveedor: $model->numero_acreedor_proveedor,
                nombre_proveedor: $model->nombre_proveedor,
                id_tipo_proveedor: $model->id_tipo_proveedor,
                id_idioma_proveedor: $model->id_idioma_proveedor,
                id_estado_proveedor: $model->id_estado_proveedor,
                maneja_formato_costbreakdown_proveedor: $model->maneja_formato_costbreakdown_proveedor,
                historia_proveedor: $model->historia_proveedor,
                descripcion_proveedor: $model->descripcion_proveedor,
                porcentaje_bom_proveedor: $model->porcentaje_bom_proveedor,
                logo_proveedor: $model->logo_proveedor,
                id_srm_proveedor: $model->id_srm_proveedor,
                id_categoria_proveedor: $model->id_categoria_proveedor,
                id_sub_categoria_proveedor: $model->id_sub_categoria_proveedor,
                formulario_ambiental_proveedor: $model->formulario_ambiental_proveedor,
                permitir_carta_beneficiarios_finales_proveedor: $model->permitir_carta_beneficiarios_finales_proveedor,
                id_administrador_proveedor: $model->id_administrador_proveedor,
                usuario_proveedor: $model->usuario_proveedor,
                password_proveedor: $model->password_proveedor,
                password_is_temporal_proveedor: $model->password_is_temporal_proveedor,
                nombre_administrador: null,
                nombre_tipo: null,
                nombre_estado: null
            );
        }, $proveedoresModels);
    }

    /**
     * @param TipoProveedor[] $tiposModels
     * @return TipoProveedorDTO[]
     */
    public static function modelToListTipoProveedorDTO(array $tiposModels): array
    {
        if (empty($tiposModels)) {
            return [];
        }
        return array_map(function (TipoProveedor $model): TipoProveedorDTO {
            return new TipoProveedorDTO(
                id_tipo_proveedor: $model->id_tipo_proveedor,
                descripcion_tipo_proveedor: $model->descripcion_tipo_proveedor
            );
        }, $tiposModels);
    }

    /**
     * @param Estados[] $estadosModels
     * @return EstadosDTO[]
     */
    public static function modelToListEstadosDTO(array $estadosModels): array
    {
        if (empty($estadosModels)) {
            return [];
        }
        return array_map(function (Estados $model): EstadosDTO {
            return new EstadosDTO(
                id_estado: $model->id_estado,
                descripcion_estado: $model->descripcion_estado
            );
        }, $estadosModels);
    }
}
