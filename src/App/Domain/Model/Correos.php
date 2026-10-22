<?php

namespace App\Domain\Model;

class Correos
{
    public function __construct(
        public ?int $id_correo = null,
        public ?string $id_proveedor_correo = null,
        public ?string $correo = null
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            $data['id_correo'] ?? null,
            $data['id_proveedor_correo'] ?? null,
            $data['correo'] ?? null
        );
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
