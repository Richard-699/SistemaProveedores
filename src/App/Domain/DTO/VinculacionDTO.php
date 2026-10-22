<?php

namespace App\Domain\DTO;

class VinculacionDTO
{
    public function __construct(
        public ?int $id_vinculacion_proveedor = null,
        public ?int $id_estado_cumplimiento_vinculacion = null,
        public ?string $observaciones_cumplimiento_vinculacion = null,
        public ?int $id_estado_ambiental_vinculacion = null,
        public ?string $observaciones_ambiental_vinculacion = null,
        public ?int $id_estado_negociacion_vinculacion = null,
        public ?string $observaciones_negociacion_vinculacion = null,
        public ?string $id_proveedor_vinculacion_proveedor = null,
        public ?string $nombre_estado_cumplimiento = null,
        public ?string $nombre_estado_negociacion = null,
        public ?string $nombre_estado_ambiental = null
    ) {}
}
