<?php

namespace App\Domain\Model;

class Vinculacion
{
    public function __construct(
        public int $id_vinculacion_proveedor,
        public int $id_estado_cumplimiento_vinculacion,
        public ?string $observaciones_cumplimiento_vinculacion,
        public int $id_estado_ambiental_vinculacion,
        public ?string $observaciones_ambiental_vinculacion,
        public int $id_estado_negociacion_vinculacion,
        public ?string $observaciones_negociacion_vinculacion,
        public string $id_proveedor_vinculacion_proveedor
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            $data['id_vinculacion_proveedor'] ?? null,
            $data['id_estado_cumplimiento_vinculacion'] ?? null,
            $data['observaciones_cumplimiento_vinculacion'] ?? null,
            $data['id_estado_ambiental_vinculacion'] ?? null,
            $data['observaciones_ambiental_vinculacion'] ?? null,
            $data['id_estado_negociacion_vinculacion'] ?? null,
            $data['observaciones_negociacion_vinculacion'] ?? null,
            $data['id_proveedor_vinculacion_proveedor'] ?? null
        );
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
