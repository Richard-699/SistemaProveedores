<?php

namespace App\Domain\Model\Auth;

class User {
    public function __construct(
        public string $nombre_usuario,
        public string $apellidos_usuario,
        public string $correo_usuario,
        public string $area_pertenece,
        public string $password_usuario,
        public ?string $id_usuario = null,
        public ?string $fecha_registro = null,
        public ?int $estado_usuario = 1,
        public ?bool $is_temporal = false
    ) {}
}   