<?php

namespace App\Domain\Model;

class Areas
{
    public function __construct(
        public int $id_area,
        public string $nombre_area
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            $data['id_area'] ?? null,
            $data['nombre_area'] ?? null
        );
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
