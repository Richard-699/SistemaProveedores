<?php

namespace App\Domain\DTO;

class PartNumbersDTO
{
    public function __construct(
        public ?int $id_partnumber = null,
        public ?string $partnumber = null,
        public ?string $descripcion_partnumber = null,
        public ?string $imagen_partnumber = null,
        public ?float $porcentaje_peso_bom_partnumber = null,
        public ?int $id_commodity_partnumber = null,
        public ?string $id_proveedor_partnumber = null,
        public ?string $nombre_proveedor = null,
        public ?string $descripcion_commodity = null,
        public ?string $extImagenPartNumber = null
    ) {}
}
