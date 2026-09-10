<?php

namespace App\Domain\DTO;

class TipoProveedorDTO
{
    public function __construct(
        public int $id_tipo_proveedor,
        public string $descripcion_tipo_proveedor
    ) {}
}
