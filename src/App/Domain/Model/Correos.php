<?php

namespace App\Domain\Model;

class Correos {
    public function __construct(
        public string $correo
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            $data['correo'] ?? ''
        );
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
