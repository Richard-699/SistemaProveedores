<?php

namespace App\Domain\Model;

class TipoProveedor
{
    public function __construct(
        public int $id_tipo_proveedor,
        public string $descripcion_tipo_proveedor
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            $data['id_tipo_proveedor'] ?? 0,
            $data['descripcion_tipo_proveedor'] ?? ''
        );
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
