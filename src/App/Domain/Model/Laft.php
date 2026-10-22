<?php

namespace App\Domain\Model;

class Laft
{
    public function __construct(
        public int $id_laft,
        public ?string $fecha_solicitud_laft,
        public ?string $ultima_actualizacion_laft,
        public ?string $proceso_laft,
        public ?string $tipo_persona_laft,
        public int $oficial_cumplimiento__laft,
        public int $declaracion_origen_fondos_informacion_laft,
        public int $autorizacion_proteccion_datos_laft,
        public int $declaracion_etica_laft,
        public string $id_proveedor_laft
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            $data['id_laft'] ?? null,
            $data['fecha_solicitud_laft'] ?? null,
            $data['ultima_actualizacion_laft'] ?? null,
            $data['proceso_laft'] ?? null,
            $data['tipo_persona_laft'] ?? null,
            $data['oficial_cumplimiento__laft'] ?? null,
            $data['declaracion_origen_fondos_informacion_laft'] ?? null,
            $data['autorizacion_proteccion_datos_laft'] ?? null,
            $data['declaracion_etica_laft'] ?? null,
            $data['id_proveedor_laft'] ?? null
        );
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
