<?php

namespace App\Domain\Model;

class Estados
{
    public function __construct(
        public int $id_estado,
        public string $descripcion_estado
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            $data['id_estado'] ?? 0,
            $data['descripcion_estado'] ?? ''
        );
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
