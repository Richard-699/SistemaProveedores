<?php

namespace App\Domain\Model;

class Administradores
{
    public function __construct(
        public string $id_administrador,
        public string $nombre_administrador,
        public string $apellidos_administrador,
        public string $correo_hwi_administrador,
        public int $id_area_administrador,
        public string $password_administrador,
        public int $id_estado_administrador,
        public int $password_is_temporal
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            $data['id_administrador'] ?? null,
            $data['nombre_administrador'] ?? null,
            $data['apellidos_administrador'] ?? null,
            $data['correo_hwi_administrador'] ?? null,
            $data['id_area_administrador'] ?? null,
            $data['password_administrador'] ?? null,
            $data['id_estado_administrador'] ?? null,
            $data['password_is_temporal'] ?? null
        );
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
