<?php

namespace App\Domain\Model;

class Costbreakdown
{
    public function __construct(
        public string $id_costbreakdown,
        public string $id_proveedor_costbreakdow,
        public string $diligencio_costbreakdown,
        public string $fecha_costbreakdown,
        public int $moneda_costbreakdown,
        public ?int $incoterm_costbreakdown,
        public float $volumen_anual_costbreakdown,
        public float $moneda_pieza_embalaje_costbreakdown,
        public float $porcentaje_total_embalaje_costbreakdown,
        public float $porcentaje_scrap_costbreakdown,
        public float $moneda_pieza_scrap_costbreakdown,
        public float $porcentaje_total_scrap_costbreakdown,
        public float $porcentaje_flete_costbreakdown,
        public float $moneda_pieza_flete_costbreakdown,
        public float $porcentaje_sga_costbreakdown,
        public float $moneda_pieza_sga_costbreakdown,
        public float $porcentaje_margen_beneficio_costbreakdown,
        public float $moneda_pieza_margen_beneficio_costbreakdown,
        public float $precio_neto_total_costbreakdown,
        public int $id_partnumber_costbreakdown
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            $data['id_costbreakdown'] ?? null,
            $data['id_proveedor_costbreakdow'] ?? null,
            $data['diligencio_costbreakdown'] ?? null,
            $data['fecha_costbreakdown'] ?? null,
            $data['moneda_costbreakdown'] ?? null,
            $data['incoterm_costbreakdown'] ?? null,
            $data['volumen_anual_costbreakdown'] ?? null,
            $data['moneda_pieza_embalaje_costbreakdown'] ?? null,
            $data['porcentaje_total_embalaje_costbreakdown'] ?? null,
            $data['porcentaje_scrap_costbreakdown'] ?? null,
            $data['moneda_pieza_scrap_costbreakdown'] ?? null,
            $data['porcentaje_total_scrap_costbreakdown'] ?? null,
            $data['porcentaje_flete_costbreakdown'] ?? null,
            $data['moneda_pieza_flete_costbreakdown'] ?? null,
            $data['porcentaje_sga_costbreakdown'] ?? null,
            $data['moneda_pieza_sga_costbreakdown'] ?? null,
            $data['porcentaje_margen_beneficio_costbreakdown'] ?? null,
            $data['moneda_pieza_margen_beneficio_costbreakdown'] ?? null,
            $data['precio_neto_total_costbreakdown'] ?? null,
            $data['id_partnumber_costbreakdown'] ?? null
        );
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
