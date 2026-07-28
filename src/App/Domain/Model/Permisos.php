<?php

namespace App\Domain\Model;

class Permisos {
    public function __construct(
        public int $id_permiso,
        public string $nombre_permiso,
        public string $descripcion_permiso
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            $data['id_permiso'] ?? null,
            $data['nombre_permiso'] ?? null,
            $data['descripcion_permiso'] ?? null
        );
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
