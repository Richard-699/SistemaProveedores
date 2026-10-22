<?php

namespace App\Domain\Enum;

enum Categoria: int
{
    case INDUSTRIAL_CLUSTER = 1;
    case SGA_CLUSTER = 2;
    case SUPPLY_CHAIN_CLUSTER = 3;

    public function label(): string
    {
        return match ($this) {
            self::INDUSTRIAL_CLUSTER => 'Industrial Cluster',
            self::SGA_CLUSTER => 'SG&A Cluster',
            self::SUPPLY_CHAIN_CLUSTER => 'Supply Chain Cluster',
        };
    }

    /**
     * @return array<int, array{id: int, nombre: string, id_categoria: int, descripcion_categoria: string}>
     */
    public static function toArray(): array
    {
        return array_map(fn(self $item) => [
            'id' => $item->value,
            'nombre' => $item->label(),
            'id_categoria' => $item->value,
            'descripcion_categoria' => $item->label(),
        ], self::cases());
    }
}
