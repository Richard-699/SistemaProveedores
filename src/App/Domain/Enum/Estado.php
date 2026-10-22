<?php

namespace App\Domain\Enum;

enum Estado: int
{
    case RECHAZADO = 1;
    case APROBADO = 2;
    case PENDIENTE = 3;
    case ACTIVO = 4;
    case INACTIVO = 5;

    public function label(): string
    {
        return match ($this) {
            self::RECHAZADO => 'Rechazado',
            self::APROBADO => 'Aprobado',
            self::PENDIENTE => 'Pendiente',
            self::ACTIVO => 'Activo',
            self::INACTIVO => 'Inactivo',
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
