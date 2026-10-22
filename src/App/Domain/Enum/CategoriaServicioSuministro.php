<?php

namespace App\Domain\Enum;

enum CategoriaServicioSuministro: int
{
    case GESTION_HUMANA_FORMACION_BIENESTAR = 1;
    case MANTENIMIENTO_INDUSTRIAL_INFRAESTRUCTURA_INGENIERIA = 2;
    case LOGISTICA_VIAJES_OPERACIONES_CADENA_SUMINISTRO = 3;
    case SERVICIOS_JURIDICOS_ASEGURADORAS_GOBIERNO_CORPORATIVO = 4;
    case TECNOLOGIA_INFORMACION_AMBIENTAL = 5;
    case MRO_REPUESTOS_INDUSTRIALES = 6;
    case INFRAESTRUCTURA_INSTALACIONES_MOBILIARIO = 7;
    case LOGISTICA_MANEJO_MATERIALES_ALMACENAMIENTO = 8;
    case DOTACION_TECNOLOGIA_PAPELERIA = 9;
    case EQUIPAMIENTO_INGENIERIA_CADENA_SUMINISTRO = 10;

    public function label(): string
    {
        return match ($this) {
            self::GESTION_HUMANA_FORMACION_BIENESTAR => 'Gestión Humana, Formación y Bienestar (SG&A)',
            self::MANTENIMIENTO_INDUSTRIAL_INFRAESTRUCTURA_INGENIERIA => 'Mantenimiento Industrial, Infraestructura e Ingeniería',
            self::LOGISTICA_VIAJES_OPERACIONES_CADENA_SUMINISTRO => 'Logística, Viajes y Operaciones de Cadena de Suministro',
            self::SERVICIOS_JURIDICOS_ASEGURADORAS_GOBIERNO_CORPORATIVO => 'Servicios Jurídicos, Aseguradoras y Gobierno Corporativo',
            self::TECNOLOGIA_INFORMACION_AMBIENTAL => 'Tecnología de la Información (TI) y Ambiental',
            self::MRO_REPUESTOS_INDUSTRIALES => 'MRO (Mantenimiento, Reparación y Operaciones) y Repuestos Industriales',
            self::INFRAESTRUCTURA_INSTALACIONES_MOBILIARIO => 'Infraestructura, Instalaciones y Mobiliario',
            self::LOGISTICA_MANEJO_MATERIALES_ALMACENAMIENTO => 'Logística, Manejo de Materiales y Almacenamiento',
            self::DOTACION_TECNOLOGIA_PAPELERIA => 'Dotación, Tecnología y papelería',
            self::EQUIPAMIENTO_INGENIERIA_CADENA_SUMINISTRO => 'Equipamiento e Ingeniería para Cadena de Suministro',
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
