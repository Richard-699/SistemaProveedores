<?php

namespace App\Domain\Model;

class AdministradoresPermisos
{
    public function __construct(
        public string $id_administrador_permiso,
        public int $id_permiso_administrador
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            $data['id_administrador_permiso'] ?? null,
            $data['id_permiso_administrador'] ?? null
        );
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
