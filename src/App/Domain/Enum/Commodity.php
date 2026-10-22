<?php

namespace App\Domain\Enum;

enum Commodity: int
{
    case STEEL = 1;
    case FASTENERS = 2;
    case RESINS = 3;
    case RUBBER_HOSES_MISC_PLAS = 4;
    case PUMPS = 5;
    case METAL_COMPONENTS = 6;
    case ELME = 7;
    case ELECTRONICS = 8;
    case WIRE_AND_HARNESS = 9;
    case PACKAGE_LITERATURE_INSULATION = 10;
    case STRUCTURE_AND_AESTHETICS = 11;
    case INJECTION_MOLDING = 12;
    case GLASS = 13;
    case HEAT = 14;
    case MOTORS = 15;

    public function label(): string
    {
        return match ($this) {
            self::STEEL => 'STEEL',
            self::FASTENERS => 'FASTENERS',
            self::RESINS => 'RESINS',
            self::RUBBER_HOSES_MISC_PLAS => 'RUBBER, HOSES, MISC PLAS',
            self::PUMPS => 'PUMPS',
            self::METAL_COMPONENTS => 'METAL COMPONENTS',
            self::ELME => 'ELME',
            self::ELECTRONICS => 'ELECTRONICS',
            self::WIRE_AND_HARNESS => 'WIRE & HARNESS',
            self::PACKAGE_LITERATURE_INSULATION => 'PACKAGE/LITERATURE/INSULATION',
            self::STRUCTURE_AND_AESTHETICS => 'STRUCTURE AND AESTHETICS',
            self::INJECTION_MOLDING => 'INJECTION MOLDING',
            self::GLASS => 'GLASS',
            self::HEAT => 'HEAT',
            self::MOTORS => 'MOTORS',
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
