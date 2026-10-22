<?php

namespace App\Domain\DTO;

class LaftDTO
{
    public function __construct(
        public ?int $id_laft = null,
        public ?string $fecha_solicitud_laft = null,
        public ?string $ultima_actualizacion_laft = null,
        public ?string $proceso_laft = null,
        public ?string $tipo_persona_laft = null,
        public ?int $oficial_cumplimiento__laft = null,
        public ?int $declaracion_origen_fondos_informacion_laft = null,
        public ?int $autorizacion_proteccion_datos_laft = null,
        public ?int $declaracion_etica_laft = null,
        public ?string $id_proveedor_laft = null
    ) {}
}
