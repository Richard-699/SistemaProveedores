<?php

namespace App\Domain\DTO;

class EstadosDTO
{
    public function __construct(
        public int $id_estado,
        public string $descripcion_estado
    ) {}
}
