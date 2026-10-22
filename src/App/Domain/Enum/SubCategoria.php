<?php

namespace App\Domain\Enum;

enum SubCategoria: int
{
    case UTILITIES = 1;
    case HUMAN_RESOURCES_IND = 2;
    case CT = 3;
    case FACILITIES = 4;
    case MRO = 5;
    case ENGINEERING = 6;
    case IT = 7;
    case FINANCE = 8;
    case LEGAL = 9;
    case TRAVEL = 10;
    case INTERNATIONAL_INBOUND = 11;
    case NATIONAL_INBOUND = 12;
    case HANDING_WAREHOUSING = 13;
    case HUMAN_RESOURCES_SGA = 14;

    public function label(): string
    {
        return match ($this) {
            self::UTILITIES => 'Utilities',
            self::HUMAN_RESOURCES_IND => 'Human Resources',
            self::CT => 'C&T',
            self::FACILITIES => 'Facilities',
            self::MRO => 'MRO',
            self::ENGINEERING => 'Engineering',
            self::IT => 'IT',
            self::FINANCE => 'Finance',
            self::LEGAL => 'Legal',
            self::TRAVEL => 'Travel',
            self::INTERNATIONAL_INBOUND => 'International Inbound',
            self::NATIONAL_INBOUND => 'National Inbound',
            self::HANDING_WAREHOUSING => 'Handing & Warehousing',
            self::HUMAN_RESOURCES_SGA => 'Human Resources',
        };
    }

    public function categoriaId(): int
    {
        return match ($this) {
            self::UTILITIES => 1,
            self::HUMAN_RESOURCES_IND => 1,
            self::CT => 1,
            self::FACILITIES => 1,
            self::MRO => 1,
            self::ENGINEERING => 1,
            self::IT => 2,
            self::FINANCE => 2,
            self::LEGAL => 2,
            self::TRAVEL => 2,
            self::INTERNATIONAL_INBOUND => 3,
            self::NATIONAL_INBOUND => 3,
            self::HANDING_WAREHOUSING => 3,
            self::HUMAN_RESOURCES_SGA => 2,
        };
    }

    public function categoria(): ?Categoria
    {
        return Categoria::tryFrom($this->categoriaId());
    }

    /**
     * @return self[]
     */
    public static function byCategoria(int $categoriaId): array
    {
        return array_values(array_filter(self::cases(), fn(self $sub) => $sub->categoriaId() === $categoriaId));
    }

    /**
     * @return array<int, array{id: int, nombre: string, id_sub_categoria: int, descripcion_sub_categoria: string, id_categoria: int}>
     */
    public static function toArray(): array
    {
        return array_map(fn(self $item) => [
            'id' => $item->value,
            'nombre' => $item->label(),
            'id_sub_categoria' => $item->value,
            'descripcion_sub_categoria' => $item->label(),
            'id_categoria' => $item->categoriaId(),
            'id_categoria_sub_categoria' => $item->categoriaId(),
        ], self::cases());
    }

    /**
     * @return array<int, array{id: int, nombre: string, id_sub_categoria: int, descripcion_sub_categoria: string, id_categoria: int}>
     */
    public static function toArrayByCategoria(int $categoriaId): array
    {
        return array_map(fn(self $item) => [
            'id' => $item->value,
            'nombre' => $item->label(),
            'id_sub_categoria' => $item->value,
            'descripcion_sub_categoria' => $item->label(),
            'id_categoria' => $item->categoriaId(),
            'id_categoria_sub_categoria' => $item->categoriaId(),
        ], self::byCategoria($categoriaId));
    }
}
