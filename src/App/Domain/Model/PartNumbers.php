<?php

namespace App\Domain\Model;

class PartNumbers
{
    public function __construct(
        public ?int $id_partnumber = null,
        public ?string $partnumber = null,
        public ?string $descripcion_partnumber = null,
        public ?string $imagen_partnumber = null,
        public ?float $porcentaje_peso_bom_partnumber = null,
        public ?int $id_commodity_partnumber = null,
        public ?string $id_proveedor_partnumber = null
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            $data['id_partnumber'] ?? null,
            $data['partnumber'] ?? null,
            $data['descripcion_partnumber'] ?? null,
            $data['imagen_partnumber'] ?? null,
            $data['porcentaje_peso_bom_partnumber'] ?? null,
            $data['id_commodity_partnumber'] ?? null,
            $data['id_proveedor_partnumber'] ?? null
        );
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
