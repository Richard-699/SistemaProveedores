<?php

namespace App\Shared\Mapper;

use App\Domain\Model\Administradores;
use App\Domain\Model\Proveedores;
use App\Domain\Model\Permisos;
use App\Domain\Model\Correos;
use App\Domain\DTO\AdministradoresDTO;
use App\Domain\DTO\ProveedoresDTO;
use App\Domain\DTO\PermisosDTO;
use App\Domain\DTO\CorreosDTO;
use App\Domain\Model\AdministradoresPermisos;
use App\Domain\DTO\AdministradoresPermisosDTO;
use App\Domain\Model\ProveedorCommodity;
use App\Domain\DTO\ProveedorCommodityDTO;
use App\Domain\Model\Vinculacion;
use App\Domain\DTO\VinculacionDTO;
use App\Domain\Model\Laft;
use App\Domain\DTO\LaftDTO;
use App\Domain\Model\GestionAmbiental;
use App\Domain\DTO\GestionAmbientalDTO;
use App\Domain\Model\Costbreakdown;
use App\Domain\DTO\CostbreakdownDTO;
use App\Domain\Model\PartNumbers;
use App\Domain\DTO\PartNumbersDTO;
use App\Domain\Enum\Area;
use App\Domain\Enum\Categoria;
use App\Domain\Enum\Commodity;
use App\Domain\Enum\Estado;
use App\Domain\Enum\Idioma;
use App\Domain\Enum\SubCategoria;
use App\Domain\Enum\TipoProveedor;

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
            nombre_area: $nombre_area ?? (Area::tryFrom($administrador->id_area_administrador ?? 0)?->label() ?? 'Sin área'),
            id_tipo_negociador: $administrador->id_tipo_negociador
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
            password_is_temporal: $dto->password_is_temporal,
            id_tipo_negociador: $dto->id_tipo_negociador
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
            nombre_tipo: TipoProveedor::tryFrom($proveedor->id_tipo_proveedor)?->label(),
            id_idioma_proveedor: $proveedor->id_idioma_proveedor,
            id_estado_proveedor: $proveedor->id_estado_proveedor,
            nombre_estado: Estado::tryFrom($proveedor->id_estado_proveedor)?->label(),
            maneja_formato_costbreakdown_proveedor: $proveedor->maneja_formato_costbreakdown_proveedor,
            historia_proveedor: $proveedor->historia_proveedor,
            descripcion_proveedor: $proveedor->descripcion_proveedor,
            porcentaje_bom_proveedor: $proveedor->porcentaje_bom_proveedor,
            logo_proveedor: $proveedor->logo_proveedor,
            id_srm_proveedor: $proveedor->id_srm_proveedor,
            id_categoria_proveedor: $proveedor->id_categoria_proveedor,
            nombre_categoria: Categoria::tryFrom($proveedor->id_categoria_proveedor)?->label(),
            id_sub_categoria_proveedor: $proveedor->id_sub_categoria_proveedor,
            nombre_subcategoria: SubCategoria::tryFrom($proveedor->id_sub_categoria_proveedor)?->label(),
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
                id_correo: $model->id_correo,
                id_proveedor_correo: $model->id_proveedor_correo,
                correo: $model->correo
            );
        }, $correos);
    }

    public static function correosDTOToModel(CorreosDTO $dto): Correos
    {
        return new Correos(
            id_correo: $dto->id_correo,
            id_proveedor_correo: $dto->id_proveedor_correo,
            correo: $dto->correo
        );
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
            return self::modelToAdministradoresDTO($model);
        }, $administradoresModels);
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
            return self::modelToProveedoresDTO($model);
        }, $proveedoresModels);
    }




    // --- PROVEEDOR COMMODITY ---
    public static function proveedorCommodityDTOToModel(ProveedorCommodityDTO $dto): ProveedorCommodity
    {
        return new ProveedorCommodity(
            id_proveedor: $dto->id_proveedor ?? '',
            id_commodity: (int)($dto->id_commodity ?? 0)
        );
    }

    public static function modelToProveedorCommodityDTO(ProveedorCommodity $model, ?string $descripcionCommodity = null): ProveedorCommodityDTO
    {
        return new ProveedorCommodityDTO(
            id_proveedor: $model->id_proveedor,
            id_commodity: $model->id_commodity,
            descripcion_commodity: $descripcionCommodity ?? Commodity::tryFrom($model->id_commodity ?? 0)?->label()
        );
    }

    /**
     * @param ProveedorCommodity[] $models
     * @return ProveedorCommodityDTO[]
     */
    public static function modelToListProveedorCommodityDTO(array $models): array
    {
        if (empty($models)) {
            return [];
        }
        return array_map(function (ProveedorCommodity $model): ProveedorCommodityDTO {
            return self::modelToProveedorCommodityDTO($model);
        }, $models);
    }

    // --- VINCULACION ---
    public static function modelToVinculacionDTO(Vinculacion $model, ?string $nombreCumplimiento = null, ?string $nombreNegociacion = null, ?string $nombreAmbiental = null): VinculacionDTO
    {
        return new VinculacionDTO(
            id_vinculacion_proveedor: $model->id_vinculacion_proveedor,
            id_estado_cumplimiento_vinculacion: $model->id_estado_cumplimiento_vinculacion,
            observaciones_cumplimiento_vinculacion: $model->observaciones_cumplimiento_vinculacion,
            id_estado_ambiental_vinculacion: $model->id_estado_ambiental_vinculacion,
            observaciones_ambiental_vinculacion: $model->observaciones_ambiental_vinculacion,
            id_estado_negociacion_vinculacion: $model->id_estado_negociacion_vinculacion,
            observaciones_negociacion_vinculacion: $model->observaciones_negociacion_vinculacion,
            id_proveedor_vinculacion_proveedor: $model->id_proveedor_vinculacion_proveedor,
            nombre_estado_cumplimiento: $nombreCumplimiento ?? Estado::tryFrom($model->id_estado_cumplimiento_vinculacion ?? 0)?->label(),
            nombre_estado_negociacion: $nombreNegociacion ?? Estado::tryFrom($model->id_estado_negociacion_vinculacion ?? 0)?->label(),
            nombre_estado_ambiental: $nombreAmbiental ?? Estado::tryFrom($model->id_estado_ambiental_vinculacion ?? 0)?->label()
        );
    }

    /**
     * @param Vinculacion[] $models
     * @return VinculacionDTO[]
     */
    public static function modelToListVinculacionDTO(array $models): array
    {
        if (empty($models)) {
            return [];
        }
        return array_map(function (Vinculacion $model): VinculacionDTO {
            return self::modelToVinculacionDTO($model);
        }, $models);
    }

    public static function vinculacionDTOToModel(VinculacionDTO $dto): Vinculacion
    {
        return new Vinculacion(
            id_vinculacion_proveedor: $dto->id_vinculacion_proveedor,
            id_estado_cumplimiento_vinculacion: $dto->id_estado_cumplimiento_vinculacion,
            observaciones_cumplimiento_vinculacion: $dto->observaciones_cumplimiento_vinculacion,
            id_estado_ambiental_vinculacion: $dto->id_estado_ambiental_vinculacion,
            observaciones_ambiental_vinculacion: $dto->observaciones_ambiental_vinculacion,
            id_estado_negociacion_vinculacion: $dto->id_estado_negociacion_vinculacion,
            observaciones_negociacion_vinculacion: $dto->observaciones_negociacion_vinculacion,
            id_proveedor_vinculacion_proveedor: $dto->id_proveedor_vinculacion_proveedor
        );
    }

    // --- LAFT ---
    public static function modelToLaftDTO(Laft $model): LaftDTO
    {
        return new LaftDTO(
            id_laft: $model->id_laft,
            fecha_solicitud_laft: $model->fecha_solicitud_laft,
            ultima_actualizacion_laft: $model->ultima_actualizacion_laft,
            proceso_laft: $model->proceso_laft,
            tipo_persona_laft: $model->tipo_persona_laft,
            oficial_cumplimiento__laft: $model->oficial_cumplimiento__laft,
            declaracion_origen_fondos_informacion_laft: $model->declaracion_origen_fondos_informacion_laft,
            autorizacion_proteccion_datos_laft: $model->autorizacion_proteccion_datos_laft,
            declaracion_etica_laft: $model->declaracion_etica_laft,
            id_proveedor_laft: $model->id_proveedor_laft
        );
    }

    /**
     * @param Laft[] $models
     * @return LaftDTO[]
     */
    public static function modelToListLaftDTO(array $models): array
    {
        if (empty($models)) {
            return [];
        }
        return array_map(function (Laft $model): LaftDTO {
            return self::modelToLaftDTO($model);
        }, $models);
    }

    public static function laftDTOToModel(LaftDTO $dto): Laft
    {
        return new Laft(
            id_laft: $dto->id_laft,
            fecha_solicitud_laft: $dto->fecha_solicitud_laft,
            ultima_actualizacion_laft: $dto->ultima_actualizacion_laft,
            proceso_laft: $dto->proceso_laft,
            tipo_persona_laft: $dto->tipo_persona_laft,
            oficial_cumplimiento__laft: $dto->oficial_cumplimiento__laft,
            declaracion_origen_fondos_informacion_laft: $dto->declaracion_origen_fondos_informacion_laft,
            autorizacion_proteccion_datos_laft: $dto->autorizacion_proteccion_datos_laft,
            declaracion_etica_laft: $dto->declaracion_etica_laft,
            id_proveedor_laft: $dto->id_proveedor_laft
        );
    }

    // --- GESTION AMBIENTAL ---
    public static function modelToGestionAmbientalDTO(GestionAmbiental $model): GestionAmbientalDTO
    {
        return new GestionAmbientalDTO(
            id_gestion_ambiental: $model->id_gestion_ambiental,
            cuenta_sistema_gestion_ambiental: $model->cuenta_sistema_gestion_ambiental,
            certificado_ISO_14001_gestion_ambiental: $model->certificado_ISO_14001_gestion_ambiental,
            cuenta_departamento_gestion_politica_ambiental: $model->cuenta_departamento_gestion_politica_ambiental,
            tiene_identificados_aspectos_impactos_gestion_ambiental: $model->tiene_identificados_aspectos_impactos_gestion_ambiental,
            principales_requisitos_legales_gestion_ambiental: $model->principales_requisitos_legales_gestion_ambiental,
            realiza_registro_anual_autoridades_gestion_ambiental: $model->realiza_registro_anual_autoridades_gestion_ambiental,
            ha_obtenido_sancion_gestion_ambiental: $model->ha_obtenido_sancion_gestion_ambiental,
            permiso_uso_recursos_naturales: $model->permiso_uso_recursos_naturales,
            permisos_cuenta_gestion_ambiental: $model->permisos_cuenta_gestion_ambiental,
            plan_manejo_integral_residuos_gestion_ambiental: $model->plan_manejo_integral_residuos_gestion_ambiental,
            genera_residuos_posconsumo_gestion_ambiental: $model->genera_residuos_posconsumo_gestion_ambiental,
            controles_realiza_gestion_residuos_solidos_peligrosos_gestion_am: $model->controles_realiza_gestion_residuos_solidos_peligrosos_gestion_am,
            genera_vertimiento_aguas_residuales_industriales_gestion_ambient: $model->genera_vertimiento_aguas_residuales_industriales_gestion_ambient,
            controles_realiza_gestion_vertimientos_gestion_ambiental: $model->controles_realiza_gestion_vertimientos_gestion_ambiental,
            genera_emisiones_atmosfericas_gestion_ambiental: $model->genera_emisiones_atmosfericas_gestion_ambiental,
            controles_realiza_gestion_emisiones_gestion_ambiental: $model->controles_realiza_gestion_emisiones_gestion_ambiental,
            plan_contingencia_manejo_transporte_gestion_ambiental: $model->plan_contingencia_manejo_transporte_gestion_ambiental,
            controles_realiza_gestion_sustancias_quimicas_gestion_ambiental: $model->controles_realiza_gestion_sustancias_quimicas_gestion_ambiental,
            observaciones_gestion_ambiental: $model->observaciones_gestion_ambiental,
            id_proveedor_gestion_ambiental: $model->id_proveedor_gestion_ambiental
        );
    }

    /**
     * @param GestionAmbiental[] $models
     * @return GestionAmbientalDTO[]
     */
    public static function modelToListGestionAmbientalDTO(array $models): array
    {
        if (empty($models)) {
            return [];
        }
        return array_map(function (GestionAmbiental $model): GestionAmbientalDTO {
            return self::modelToGestionAmbientalDTO($model);
        }, $models);
    }

    public static function gestionAmbientalDTOToModel(GestionAmbientalDTO $dto): GestionAmbiental
    {
        return new GestionAmbiental(
            id_gestion_ambiental: $dto->id_gestion_ambiental,
            cuenta_sistema_gestion_ambiental: $dto->cuenta_sistema_gestion_ambiental,
            certificado_ISO_14001_gestion_ambiental: $dto->certificado_ISO_14001_gestion_ambiental,
            cuenta_departamento_gestion_politica_ambiental: $dto->cuenta_departamento_gestion_politica_ambiental,
            tiene_identificados_aspectos_impactos_gestion_ambiental: $dto->tiene_identificados_aspectos_impactos_gestion_ambiental,
            principales_requisitos_legales_gestion_ambiental: $dto->principales_requisitos_legales_gestion_ambiental,
            realiza_registro_anual_autoridades_gestion_ambiental: $dto->realiza_registro_anual_autoridades_gestion_ambiental,
            ha_obtenido_sancion_gestion_ambiental: $dto->ha_obtenido_sancion_gestion_ambiental,
            permiso_uso_recursos_naturales: $dto->permiso_uso_recursos_naturales,
            permisos_cuenta_gestion_ambiental: $dto->permisos_cuenta_gestion_ambiental,
            plan_manejo_integral_residuos_gestion_ambiental: $dto->plan_manejo_integral_residuos_gestion_ambiental,
            genera_residuos_posconsumo_gestion_ambiental: $dto->genera_residuos_posconsumo_gestion_ambiental,
            controles_realiza_gestion_residuos_solidos_peligrosos_gestion_am: $dto->controles_realiza_gestion_residuos_solidos_peligrosos_gestion_am,
            genera_vertimiento_aguas_residuales_industriales_gestion_ambient: $dto->genera_vertimiento_aguas_residuales_industriales_gestion_ambient,
            controles_realiza_gestion_vertimientos_gestion_ambiental: $dto->controles_realiza_gestion_vertimientos_gestion_ambiental,
            genera_emisiones_atmosfericas_gestion_ambiental: $dto->genera_emisiones_atmosfericas_gestion_ambiental,
            controles_realiza_gestion_emisiones_gestion_ambiental: $dto->controles_realiza_gestion_emisiones_gestion_ambiental,
            plan_contingencia_manejo_transporte_gestion_ambiental: $dto->plan_contingencia_manejo_transporte_gestion_ambiental,
            controles_realiza_gestion_sustancias_quimicas_gestion_ambiental: $dto->controles_realiza_gestion_sustancias_quimicas_gestion_ambiental,
            observaciones_gestion_ambiental: $dto->observaciones_gestion_ambiental,
            id_proveedor_gestion_ambiental: $dto->id_proveedor_gestion_ambiental
        );
    }

    // --- COST BREAKDOWN ---
    public static function modelToCostbreakdownDTO(Costbreakdown $model): CostbreakdownDTO
    {
        return new CostbreakdownDTO(
            id_costbreakdown: $model->id_costbreakdown,
            id_proveedor_costbreakdow: $model->id_proveedor_costbreakdow,
            diligencio_costbreakdown: $model->diligencio_costbreakdown,
            fecha_costbreakdown: $model->fecha_costbreakdown,
            moneda_costbreakdown: $model->moneda_costbreakdown,
            incoterm_costbreakdown: $model->incoterm_costbreakdown,
            volumen_anual_costbreakdown: $model->volumen_anual_costbreakdown,
            moneda_pieza_embalaje_costbreakdown: $model->moneda_pieza_embalaje_costbreakdown,
            porcentaje_total_embalaje_costbreakdown: $model->porcentaje_total_embalaje_costbreakdown,
            porcentaje_scrap_costbreakdown: $model->porcentaje_scrap_costbreakdown,
            moneda_pieza_scrap_costbreakdown: $model->moneda_pieza_scrap_costbreakdown,
            porcentaje_total_scrap_costbreakdown: $model->porcentaje_total_scrap_costbreakdown,
            porcentaje_flete_costbreakdown: $model->porcentaje_flete_costbreakdown,
            moneda_pieza_flete_costbreakdown: $model->moneda_pieza_flete_costbreakdown,
            porcentaje_sga_costbreakdown: $model->porcentaje_sga_costbreakdown,
            moneda_pieza_sga_costbreakdown: $model->moneda_pieza_sga_costbreakdown,
            porcentaje_margen_beneficio_costbreakdown: $model->porcentaje_margen_beneficio_costbreakdown,
            moneda_pieza_margen_beneficio_costbreakdown: $model->moneda_pieza_margen_beneficio_costbreakdown,
            precio_neto_total_costbreakdown: $model->precio_neto_total_costbreakdown,
            id_partnumber_costbreakdown: $model->id_partnumber_costbreakdown
        );
    }

    /**
     * @param Costbreakdown[] $models
     * @return CostbreakdownDTO[]
     */
    public static function modelToListCostbreakdownDTO(array $models): array
    {
        if (empty($models)) {
            return [];
        }
        return array_map(function (Costbreakdown $model): CostbreakdownDTO {
            return self::modelToCostbreakdownDTO($model);
        }, $models);
    }

    public static function costbreakdownDTOToModel(CostbreakdownDTO $dto): Costbreakdown
    {
        return new Costbreakdown(
            id_costbreakdown: $dto->id_costbreakdown,
            id_proveedor_costbreakdow: $dto->id_proveedor_costbreakdow,
            diligencio_costbreakdown: $dto->diligencio_costbreakdown,
            fecha_costbreakdown: $dto->fecha_costbreakdown,
            moneda_costbreakdown: $dto->moneda_costbreakdown,
            incoterm_costbreakdown: $dto->incoterm_costbreakdown,
            volumen_anual_costbreakdown: $dto->volumen_anual_costbreakdown,
            moneda_pieza_embalaje_costbreakdown: $dto->moneda_pieza_embalaje_costbreakdown,
            porcentaje_total_embalaje_costbreakdown: $dto->porcentaje_total_embalaje_costbreakdown,
            porcentaje_scrap_costbreakdown: $dto->porcentaje_scrap_costbreakdown,
            moneda_pieza_scrap_costbreakdown: $dto->moneda_pieza_scrap_costbreakdown,
            porcentaje_total_scrap_costbreakdown: $dto->porcentaje_total_scrap_costbreakdown,
            porcentaje_flete_costbreakdown: $dto->porcentaje_flete_costbreakdown,
            moneda_pieza_flete_costbreakdown: $dto->moneda_pieza_flete_costbreakdown,
            porcentaje_sga_costbreakdown: $dto->porcentaje_sga_costbreakdown,
            moneda_pieza_sga_costbreakdown: $dto->moneda_pieza_sga_costbreakdown,
            porcentaje_margen_beneficio_costbreakdown: $dto->porcentaje_margen_beneficio_costbreakdown,
            moneda_pieza_margen_beneficio_costbreakdown: $dto->moneda_pieza_margen_beneficio_costbreakdown,
            precio_neto_total_costbreakdown: $dto->precio_neto_total_costbreakdown,
            id_partnumber_costbreakdown: $dto->id_partnumber_costbreakdown
        );
    }



    // --- PART NUMBERS ---
    public static function modelToPartNumbersDTO(PartNumbers $model): PartNumbersDTO
    {
        return new PartNumbersDTO(
            id_partnumber: $model->id_partnumber,
            partnumber: $model->partnumber,
            descripcion_partnumber: $model->descripcion_partnumber,
            imagen_partnumber: $model->imagen_partnumber,
            porcentaje_peso_bom_partnumber: $model->porcentaje_peso_bom_partnumber,
            id_commodity_partnumber: $model->id_commodity_partnumber,
            id_proveedor_partnumber: $model->id_proveedor_partnumber,
            descripcion_commodity: Commodity::tryFrom($model->id_commodity_partnumber ?? 0)?->label() ?? 'Sin commodity'
        );
    }

    /**
     * @param PartNumbers[] $models
     * @return PartNumbersDTO[]
     */
    public static function modelToListPartNumbersDTO(array $models): array
    {
        if (empty($models)) {
            return [];
        }
        return array_map(function (PartNumbers $model): PartNumbersDTO {
            return self::modelToPartNumbersDTO($model);
        }, $models);
    }

    public static function partNumbersDTOToModel(PartNumbersDTO $dto): PartNumbers
    {
        return new PartNumbers(
            id_partnumber: $dto->id_partnumber,
            partnumber: $dto->partnumber,
            descripcion_partnumber: $dto->descripcion_partnumber,
            imagen_partnumber: $dto->imagen_partnumber,
            porcentaje_peso_bom_partnumber: $dto->porcentaje_peso_bom_partnumber,
            id_commodity_partnumber: $dto->id_commodity_partnumber,
            id_proveedor_partnumber: $dto->id_proveedor_partnumber
        );
    }

    /**
     * @param ProveedoresDTO[] $proveedoresDTOs
     * @return array
     */
    public static function proveedoresDTOListToExcelData(array $proveedoresDTOs): array
    {
        $data = [];
        foreach ($proveedoresDTOs as $dto) {
            $acreedor = (!empty($dto->numero_acreedor_proveedor) && $dto->numero_acreedor_proveedor !== '0' && trim((string)$dto->numero_acreedor_proveedor) !== '')
                ? trim((string)$dto->numero_acreedor_proveedor)
                : 'Sin asignar';

            $razonSocial = !empty($dto->nombre_proveedor) ? mb_convert_case(trim($dto->nombre_proveedor), MB_CASE_TITLE, "UTF-8") : '';
            $tipoProveedor = !empty($dto->nombre_tipo) ? $dto->nombre_tipo : 'Sin tipo';
            $negociadorEncargado = (!empty($dto->nombre_administrador) && $dto->nombre_administrador !== 'Sin asignar')
                ? mb_convert_case(trim($dto->nombre_administrador), MB_CASE_TITLE, "UTF-8")
                : 'Sin asignar';
            $estado = !empty($dto->nombre_estado) ? $dto->nombre_estado : 'Desconocido';

            $data[] = [
                'numero_acreedor' => $acreedor,
                'razon_social' => $razonSocial,
                'tipo_proveedor' => $tipoProveedor,
                'creado_por' => $negociadorEncargado,
                'estado' => $estado,
            ];
        }

        return $data;
    }
}
