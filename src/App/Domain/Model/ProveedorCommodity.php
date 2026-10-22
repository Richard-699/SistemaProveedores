<?php

namespace App\Domain\Model;

class ProveedorCommodity
{
    public function __construct(
        public string $id_proveedor,
        public int $id_commodity
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            $data['id_proveedor'] ?? null,
            $data['id_commodity'] ?? null
        );
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
