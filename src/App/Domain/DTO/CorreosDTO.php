<?php

namespace App\Domain\DTO;

class CorreosDTO
{
    public function __construct(
        public ?int $id_correo = null,
        public ?string $id_proveedor_correo = null,
        public ?string $correo = null
    ) {}
}
