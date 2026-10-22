<?php

namespace App\Domain\Enum;

enum Idioma: int
{
    case ES = 1;
    case EN = 2;

    public function label(): string
    {
        return match ($this) {
            self::ES => 'Es - Nacional',
            self::EN => 'En - Internacional',
        };
    }

    /**
     * @return array<int, array{id: int, nombre: string}>
     */
    public static function toArray(): array
    {
        return array_map(fn(self $item) => [
            'id' => $item->value,
            'nombre' => $item->label(),
        ], self::cases());
    }
}
