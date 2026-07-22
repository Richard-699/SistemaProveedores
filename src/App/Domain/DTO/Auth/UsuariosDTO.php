<?php

namespace App\Domain\DTO\Auth;

class UsuariosDTO {
    public function __construct(
        public string $nombre_usuario,
        public string $apellidos_usuario,
        public string $correo_usuario,
        public string $password_usuario,
        public ?string $password_confirmacion = null,
        public ?string $id_usuario = null,
        public ?int $id_area_usuario = null,
        public ?int $estado_registro = 1,
        public ?bool $is_temporal = false
    ) {}
}
