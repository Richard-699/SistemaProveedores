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
            $data['id_administrador_permiso'] ?? '',
            $data['id_permiso_administrador'] ?? 0
        );
    }

    public function toArray(): array
    {
        return [
            'id_administrador_permiso' => $this->id_administrador_permiso,
            'id_permiso_administrador' => $this->id_permiso_administrador
        ];
    }
}

