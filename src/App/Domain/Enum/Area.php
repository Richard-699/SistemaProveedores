<?php

namespace App\Domain\Enum;

enum Area: int
{
    case NEGOCIACION = 1;
    case GESTION_AMBIENTAL = 2;
    case CUMPLIMIENTO = 3;
    case CONTABILIDAD = 4;
    case BI_ADMIN = 6;

    public function label(): string
    {
        return match ($this) {
            self::NEGOCIACION => 'Negociación',
            self::GESTION_AMBIENTAL => 'Gestión Ambiental',
            self::CUMPLIMIENTO => 'Cumplimiento',
            self::CONTABILIDAD => 'Contabilidad',
            self::BI_ADMIN => 'BI - Admin',
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
