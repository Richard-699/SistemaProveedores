<?php

namespace App\Domain\DTO;

class ProveedorCommodityDTO
{
    public function __construct(
        public ?string $id_proveedor = null,
        public ?int $id_commodity = null,
        public ?string $descripcion_commodity = null
    ) {}
}
