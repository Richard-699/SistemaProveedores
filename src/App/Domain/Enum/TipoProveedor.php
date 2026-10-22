<?php

namespace App\Domain\Enum;

enum TipoProveedor: int
{
    case DIRECTO = 1;
    case INDIRECTO = 2;

    public function label(): string
    {
        return match ($this) {
            self::DIRECTO => 'Directo',
            self::INDIRECTO => 'Indirecto',
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
