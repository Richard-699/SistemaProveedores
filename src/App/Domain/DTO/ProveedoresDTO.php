<?php

namespace App\Domain\DTO;

class ProveedoresDTO
{
    /**
     * @param ProveedorCommodityDTO[] $commodities
     * @param CorreosDTO[] $correos
     */
    public function __construct(
        public ?string $id_proveedor = null,
        public ?string $numero_acreedor_proveedor = null,
        public ?string $nombre_proveedor = null,
        public ?int $id_tipo_proveedor = null,
        public ?int $id_idioma_proveedor = null,
        public ?int $id_estado_proveedor = null,
        public ?int $maneja_formato_costbreakdown_proveedor = null,
        public ?string $historia_proveedor = null,
        public ?string $descripcion_proveedor = null,
        public ?float $porcentaje_bom_proveedor = null,
        public ?string $logo_proveedor = null,
        public ?string $id_srm_proveedor = null,
        public ?int $id_categoria_proveedor = null,
        public ?int $id_sub_categoria_proveedor = null,
        public ?int $formulario_ambiental_proveedor = null,
        public ?int $permitir_carta_beneficiarios_finales_proveedor = null,
        public ?string $id_administrador_proveedor = null,
        public ?string $usuario_proveedor = null,
        public ?string $password_proveedor = null,
        public ?int $password_is_temporal_proveedor = null,
        public ?string $tempPassword = null,
        public ?string $nombre_administrador = null,
        public ?string $nombre_tipo = null,
        public ?string $nombre_estado = null,
        public ?int $id_estado_cumplimiento = null,
        public ?string $nombre_estado_cumplimiento = null,
        public ?int $id_estado_negociacion = null,
        public ?string $nombre_estado_negociacion = null,
        public ?int $id_estado_ambiental = null,
        public ?string $nombre_estado_ambiental = null,
        public ?int $oficial_cumplimiento__laft = null,
        public ?int $id_commodity = null,
        public ?string $nombre_commodity = null,
        public ?string $nombre_categoria = null,
        public ?string $nombre_subcategoria = null,
        public ?int $tiene_gestion_ambiental = null,
        public ?int $tiene_costbreakdown = null,
        public ?int $tiene_laft = null,
        public array $commodities = [],
        public array $correos = []
    ) {}
}
