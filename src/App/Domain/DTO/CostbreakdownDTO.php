<?php

namespace App\Domain\DTO;

class CostbreakdownDTO
{
    public function __construct(
        public ?string $id_costbreakdown = null,
        public ?string $id_proveedor_costbreakdow = null,
        public ?string $diligencio_costbreakdown = null,
        public ?string $fecha_costbreakdown = null,
        public ?int $moneda_costbreakdown = null,
        public ?int $incoterm_costbreakdown = null,
        public ?float $volumen_anual_costbreakdown = null,
        public ?float $moneda_pieza_embalaje_costbreakdown = null,
        public ?float $porcentaje_total_embalaje_costbreakdown = null,
        public ?float $porcentaje_scrap_costbreakdown = null,
        public ?float $moneda_pieza_scrap_costbreakdown = null,
        public ?float $porcentaje_total_scrap_costbreakdown = null,
        public ?float $porcentaje_flete_costbreakdown = null,
        public ?float $moneda_pieza_flete_costbreakdown = null,
        public ?float $porcentaje_sga_costbreakdown = null,
        public ?float $moneda_pieza_sga_costbreakdown = null,
        public ?float $porcentaje_margen_beneficio_costbreakdown = null,
        public ?float $moneda_pieza_margen_beneficio_costbreakdown = null,
        public ?float $precio_neto_total_costbreakdown = null,
        public ?int $id_partnumber_costbreakdown = null
    ) {}
}
