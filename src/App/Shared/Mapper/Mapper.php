<?php

namespace App\Shared\Mapper;



class Mapper {

    public static function modelToAdministradoresDTO(\App\Domain\Model\Administradores $administrador, array $permisos = []): \App\Domain\DTO\AdministradoresDTO {
        return new \App\Domain\DTO\AdministradoresDTO(
            id_administrador: $administrador->id_administrador,
            nombre_administrador: $administrador->nombre_administrador,
            apellidos_administrador: $administrador->apellidos_administrador,
            correo_hwi_administrador: $administrador->correo_hwi_administrador,
            id_area_administrador: $administrador->id_area_administrador,
            password_administrador: $administrador->password_administrador,
            id_estado_administrador: $administrador->id_estado_administrador,
            password_is_temporal: $administrador->password_is_temporal,
            permisosDTO: $permisos
        );
    }

    public static function administradoresDTOToModel(\App\Domain\DTO\AdministradoresDTO $dto): \App\Domain\Model\Administradores {
        return new \App\Domain\Model\Administradores(
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
    public static function modelToProveedoresDTO(\App\Domain\Model\Proveedores $proveedor): \App\Domain\DTO\ProveedoresDTO {
        return new \App\Domain\DTO\ProveedoresDTO(
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

    public static function proveedoresDTOToModel(\App\Domain\DTO\ProveedoresDTO $dto): \App\Domain\Model\Proveedores {
        return new \App\Domain\Model\Proveedores(
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
     * @param \App\Domain\Model\Permisos[] $permisos
     * @return \App\Domain\DTO\PermisosDTO[]
     */
    public static function listModelToPermisosDTO(array $permisos): array {
        $dtos = [];
        foreach ($permisos as $permiso) {
            $dtos[] = new \App\Domain\DTO\PermisosDTO(
                id_permiso: $permiso->id_permiso,
                nombre_permiso: $permiso->nombre_permiso,
                descripcion_permiso: $permiso->descripcion_permiso
            );
        }
        return $dtos;
    }

    public static function permisosDTOToModel(\App\Domain\DTO\PermisosDTO $dto): \App\Domain\Model\Permisos {
        return new \App\Domain\Model\Permisos(
            id_permiso: $dto->id_permiso,
            nombre_permiso: $dto->nombre_permiso,
            descripcion_permiso: $dto->descripcion_permiso
        );
    }
}
