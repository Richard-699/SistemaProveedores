<?php

namespace App\Domain\Enum;

enum SubCategoriaServicioSuministro: int
{
    case ACADEMICO = 1;
    case CURSOS = 2;
    case CERTIFICACIONES = 3;
    case ALIMENTACION = 4;
    case EVENTOS = 5;
    case RESTAURANTE = 6;
    case ODONTOLOGIA = 7;
    case EDICION_DE_LIBROS = 8;
    case PERFIL_DE_DESTREZA = 9;
    case OUTSOURCING_DE_PROFESIONALES = 10;
    case CONSULTORIA = 11;
    case EDICION_VIDEO = 12;
    case VISITA_DOMICILIARIA = 13;
    case LABORATORIO_CLINICO = 14;
    case MEDICINA_LABORAL = 15;
    case ACOMETIDAS_ELECTRICAS = 16;
    case MONTAJES_ESPECIALES = 17;
    case MEDICIONES = 18;
    case CONSULTORIA_AMBIENTAL = 19;
    case SANDBLASTING = 20;
    case BANDAS_TRANSPORTADORAS = 21;
    case LIMPIEZA = 22;
    case MANTENIMIENTO_DE_COMPRESORES = 23;
    case VIGILANCIA = 24;
    case AUTOMATIZACION = 25;
    case MANTENIMIENTO_ASCENSORES = 26;
    case SERVICIOS_ENERGETICOS = 27;
    case MANTENIMIENTO_HIDRAULICO = 28;
    case MANTENIMIENTO_NEUMATICO = 29;
    case DISPOSICION_DE_RESIDUOS = 30;
    case RECARGA_EXTINTORES = 31;
    case SERVICIO_DE_INGENIERIA_Y_DISENO = 32;
    case METALMECANICO = 33;
    case AGENCIA_DE_VIAJES = 34;
    case AGENTE_DE_CARGA = 35;
    case ALMACENAMIENTO = 36;
    case INMOBILIARIA = 37;
    case ARRENDAMIENTO = 38;
    case ASESORIA = 39;
    case LOGISTICA_INTERNACIONAL = 40;
    case OPERADOR_LOGISTICO = 41;
    case ALQUILER_DE_MONTACARGAS = 42;
    case MENSAJERIA = 43;
    case TRANSPORTE_EJECUTIVO = 44;
    case AGENCIAMIENTO_MARITIMO_Y_AEREO = 45;
    case ASEGURADORA = 46;
    case ASESORIA_JURIDICA = 47;
    case ABOGADO = 48;
    case AUDITORIA = 49;
    case REVISORIA_FISCAL = 50;
    case SEGURIDAD_SOCIAL = 51;
    case PROTECCION_DE_DATOS = 52;
    case AMBIENTAL = 53;
    case SERVICIOS_DE_NUBE = 54;
    case INFRAESTRUCTURA_IT = 55;
    case BANCA_DIGITAL = 56;
    case TECNOLOGIA = 57;
    case PERIFERICOS = 58;
    case LICENCIAMIENTO_DE_SOFTWARE = 59;
    case CIBERSEGURIDAD = 60;
    case MANTENIMIENTO_INFORMATICO = 61;
    case CONECTIVIDAD_Y_TELECOMUNICACIONES = 62;
    case IMPRESION = 63;
    case MAQUINARIA_Y_EQUIPO = 64;
    case IMPORTACION_REPUESTOS = 65;
    case INSTRUMENTACION = 66;
    case RODAMIENTOS = 67;
    case MEDIDORES = 68;
    case FERRETERIA = 69;
    case PINTURAS = 70;
    case VIDRIO = 71;
    case COMBUSTIBLES = 72;
    case HERRAMIENTAS = 73;
    case ELEMENTOS_DE_PROTECCION_PERSONAL = 74;
    case DISPOSITIVOS = 75;
    case ACEITES = 76;
    case LUBRICANTES = 77;
    case REFRIGERANTES = 78;
    case TORNILLERIA_Y_FIJACION = 79;
    case FILTROS_INDUSTRIALES = 80;
    case MATERIAL_ELECTRICO = 81;
    case VARIADORES = 82;
    case QUIMICOS = 83;
    case REPRESENTANTE_DE_MARCA = 84;
    case AIRES_ACONDICIONADOS = 85;
    case MOBILIARIO = 86;
    case SISTEMAS_DE_SEGURIDAD = 87;
    case ELEMENTOS_DE_SEGURIDAD = 88;
    case SISTEMAS_MODULARES = 89;
    case VIDRIOS = 90;
    case LAMPARAS = 91;
    case GRIFERIA_Y_BANOS = 92;
    case PINTURA = 93;
    case RECUBRIMIENTOS_ESPECIALES = 94;
    case CONTROL_DE_ACCESO = 95;
    case ILUMINACION_INDUSTRIAL = 96;
    case PUERTAS_INDUSTRIALES = 97;
    case CUBIERTAS = 98;
    case CERRAMIENTOS_Y_MALLAS = 99;
    case CANASTAS = 100;
    case CARROS_ABASTECIMIENTO = 101;
    case ESTIBAS = 102;
    case ROTULOS = 103;
    case ETIQUETAS = 104;
    case TABLEROS = 105;
    case CINTAS = 106;
    case PAPEL_STRECH = 107;
    case INSUMOS_DE_EMPAQUE = 108;
    case ESTANTERIA_Y_RACKS = 109;
    case ESCALERAS_Y_AISLAMIENTO = 110;
    case ESTIBAS_ESPECIALES = 111;
    case ACRILICO = 112;
    case DOTACION = 113;
    case AVISOS = 114;
    case SOUVENIRS = 115;
    case SENALIZACION = 116;
    case CORTE_LASER = 117;
    case BASCULAS_Y_BALANZAS = 118;
    case BONOS = 119;
    case CALZADO_DE_SEGURIDAD = 120;
    case CONSUMIBLES_DE_IMPRESION = 121;
    case PAPELERIA = 122;
    case CARPAS = 123;
    case MEDIOS_KANBAN = 124;
    case COLAPSIBLES = 125;
    case MEDICION = 126;
    case MATERIAL_LEAN = 127;
    case SENALETICA = 128;
    case MARCACION = 129;

    public function label(): string
    {
        return match ($this) {
            self::ACADEMICO => 'Académico',
            self::CURSOS => 'Cursos',
            self::CERTIFICACIONES => 'Certificaciones',
            self::ALIMENTACION => 'Alimentación',
            self::EVENTOS => 'Eventos',
            self::RESTAURANTE => 'Restaurante',
            self::ODONTOLOGIA => 'Odontología',
            self::EDICION_DE_LIBROS => 'Edición de libros',
            self::PERFIL_DE_DESTREZA => 'Perfil de destreza',
            self::OUTSOURCING_DE_PROFESIONALES => 'Outsourcing de profesionales',
            self::CONSULTORIA => 'Consultoría',
            self::EDICION_VIDEO => 'Edición video',
            self::VISITA_DOMICILIARIA => 'Visita domiciliaria',
            self::LABORATORIO_CLINICO => 'Laboratorio clínico',
            self::MEDICINA_LABORAL => 'Medicina laboral',
            self::ACOMETIDAS_ELECTRICAS => 'Acometidas eléctricas',
            self::MONTAJES_ESPECIALES => 'Montajes especiales',
            self::MEDICIONES => 'Mediciones',
            self::CONSULTORIA_AMBIENTAL => 'Consultoría ambiental',
            self::SANDBLASTING => 'Sandblasting',
            self::BANDAS_TRANSPORTADORAS => 'Bandas transportadoras',
            self::LIMPIEZA => 'Limpieza',
            self::MANTENIMIENTO_DE_COMPRESORES => 'Mantenimiento de compresores',
            self::VIGILANCIA => 'Vigilancia',
            self::AUTOMATIZACION => 'Automatización',
            self::MANTENIMIENTO_ASCENSORES => 'Mantenimiento ascensores',
            self::SERVICIOS_ENERGETICOS => 'Servicios energéticos',
            self::MANTENIMIENTO_HIDRAULICO => 'Mantenimiento hidráulico',
            self::MANTENIMIENTO_NEUMATICO => 'Mantenimiento neumático',
            self::DISPOSICION_DE_RESIDUOS => 'Disposición de residuos',
            self::RECARGA_EXTINTORES => 'Recarga extintores',
            self::SERVICIO_DE_INGENIERIA_Y_DISENO => 'Servicio de ingeniería y diseño',
            self::METALMECANICO => 'Metalmecánico',
            self::AGENCIA_DE_VIAJES => 'Agencia de viajes',
            self::AGENTE_DE_CARGA => 'Agente de carga',
            self::ALMACENAMIENTO => 'Almacenamiento',
            self::INMOBILIARIA => 'Inmobiliaria',
            self::ARRENDAMIENTO => 'Arrendamiento',
            self::ASESORIA => 'Asesoría',
            self::LOGISTICA_INTERNACIONAL => 'Logística internacional',
            self::OPERADOR_LOGISTICO => 'Operador logístico',
            self::ALQUILER_DE_MONTACARGAS => 'Alquiler de montacargas',
            self::MENSAJERIA => 'Mensajería',
            self::TRANSPORTE_EJECUTIVO => 'Transporte ejecutivo',
            self::AGENCIAMIENTO_MARITIMO_Y_AEREO => 'Agenciamiento marítimo y aéreo',
            self::ASEGURADORA => 'Aseguradora',
            self::ASESORIA_JURIDICA => 'Asesoría jurídica',
            self::ABOGADO => 'Abogado',
            self::AUDITORIA => 'Auditoría',
            self::REVISORIA_FISCAL => 'Revisoría fiscal',
            self::SEGURIDAD_SOCIAL => 'Seguridad social',
            self::PROTECCION_DE_DATOS => 'Protección de datos',
            self::AMBIENTAL => 'Ambiental',
            self::SERVICIOS_DE_NUBE => 'Servicios de Nube',
            self::INFRAESTRUCTURA_IT => 'Infraestructura IT',
            self::BANCA_DIGITAL => 'Banca digital',
            self::TECNOLOGIA => 'Tecnología',
            self::PERIFERICOS => 'Periféricos',
            self::LICENCIAMIENTO_DE_SOFTWARE => 'Licenciamiento de software',
            self::CIBERSEGURIDAD => 'Ciberseguridad',
            self::MANTENIMIENTO_INFORMATICO => 'Mantenimiento informático',
            self::CONECTIVIDAD_Y_TELECOMUNICACIONES => 'Conectividad y telecomunicaciones',
            self::IMPRESION => 'Impresión',
            self::MAQUINARIA_Y_EQUIPO => 'Maquinaria y equipo',
            self::IMPORTACION_REPUESTOS => 'Importación repuestos',
            self::INSTRUMENTACION => 'Instrumentación',
            self::RODAMIENTOS => 'Rodamientos',
            self::MEDIDORES => 'Medidores',
            self::FERRETERIA => 'Ferretería',
            self::PINTURAS => 'Pinturas',
            self::VIDRIO => 'Vidrio',
            self::COMBUSTIBLES => 'Combustibles',
            self::HERRAMIENTAS => 'Herramientas',
            self::ELEMENTOS_DE_PROTECCION_PERSONAL => 'Elementos de protección personal',
            self::DISPOSITIVOS => 'Dispositivos',
            self::ACEITES => 'Aceites',
            self::LUBRICANTES => 'Lubricantes',
            self::REFRIGERANTES => 'Refrigerantes',
            self::TORNILLERIA_Y_FIJACION => 'Tornillería y fijación',
            self::FILTROS_INDUSTRIALES => 'Filtros industriales',
            self::MATERIAL_ELECTRICO => 'Material eléctrico',
            self::VARIADORES => 'Variadores',
            self::QUIMICOS => 'Químicos',
            self::REPRESENTANTE_DE_MARCA => 'Representante de marca',
            self::AIRES_ACONDICIONADOS => 'Aires acondicionados',
            self::MOBILIARIO => 'Mobiliario',
            self::SISTEMAS_DE_SEGURIDAD => 'Sistemas de seguridad',
            self::ELEMENTOS_DE_SEGURIDAD => 'Elementos de seguridad',
            self::SISTEMAS_MODULARES => 'Sistemas modulares',
            self::VIDRIOS => 'Vidrios',
            self::LAMPARAS => 'Lámparas',
            self::GRIFERIA_Y_BANOS => 'Grifería y baños',
            self::PINTURA => 'Pintura',
            self::RECUBRIMIENTOS_ESPECIALES => 'Recubrimientos especiales',
            self::CONTROL_DE_ACCESO => 'Control de acceso',
            self::ILUMINACION_INDUSTRIAL => 'Iluminación industrial',
            self::PUERTAS_INDUSTRIALES => 'Puertas industriales',
            self::CUBIERTAS => 'Cubiertas',
            self::CERRAMIENTOS_Y_MALLAS => 'Cerramientos y mallas',
            self::CANASTAS => 'Canastas',
            self::CARROS_ABASTECIMIENTO => 'Carros abastecimiento',
            self::ESTIBAS => 'Estibas',
            self::ROTULOS => 'Rótulos',
            self::ETIQUETAS => 'Etiquetas',
            self::TABLEROS => 'Tableros',
            self::CINTAS => 'Cintas',
            self::PAPEL_STRECH => 'Papel strech',
            self::INSUMOS_DE_EMPAQUE => 'Insumos de empaque',
            self::ESTANTERIA_Y_RACKS => 'Estantería y racks',
            self::ESCALERAS_Y_AISLAMIENTO => 'Escaleras y aislamiento',
            self::ESTIBAS_ESPECIALES => 'Estibas especiales',
            self::ACRILICO => 'Acrílico',
            self::DOTACION => 'Dotación',
            self::AVISOS => 'Avisos',
            self::SOUVENIRS => 'Souvenirs',
            self::SENALIZACION => 'Señalización',
            self::CORTE_LASER => 'Corte Laser',
            self::BASCULAS_Y_BALANZAS => 'Básculas y balanzas',
            self::BONOS => 'Bonos',
            self::CALZADO_DE_SEGURIDAD => 'Calzado de seguridad',
            self::CONSUMIBLES_DE_IMPRESION => 'Consumibles de impresión',
            self::PAPELERIA => 'Papelería',
            self::CARPAS => 'Carpas',
            self::MEDIOS_KANBAN => 'Medios kanban',
            self::COLAPSIBLES => 'Colapsibles',
            self::MEDICION => 'Medición',
            self::MATERIAL_LEAN => 'Material lean',
            self::SENALETICA => 'Señalética',
            self::MARCACION => 'Marcación',
        };
    }

    public function categoriaId(): int
    {
        return match ($this) {
            self::ACADEMICO => 1,
            self::CURSOS => 1,
            self::CERTIFICACIONES => 1,
            self::ALIMENTACION => 1,
            self::EVENTOS => 1,
            self::RESTAURANTE => 1,
            self::ODONTOLOGIA => 1,
            self::EDICION_DE_LIBROS => 1,
            self::PERFIL_DE_DESTREZA => 1,
            self::OUTSOURCING_DE_PROFESIONALES => 1,
            self::CONSULTORIA => 1,
            self::EDICION_VIDEO => 1,
            self::VISITA_DOMICILIARIA => 1,
            self::LABORATORIO_CLINICO => 1,
            self::MEDICINA_LABORAL => 1,
            self::ACOMETIDAS_ELECTRICAS => 2,
            self::MONTAJES_ESPECIALES => 2,
            self::MEDICIONES => 2,
            self::CONSULTORIA_AMBIENTAL => 2,
            self::SANDBLASTING => 2,
            self::BANDAS_TRANSPORTADORAS => 2,
            self::LIMPIEZA => 2,
            self::MANTENIMIENTO_DE_COMPRESORES => 2,
            self::VIGILANCIA => 2,
            self::AUTOMATIZACION => 2,
            self::MANTENIMIENTO_ASCENSORES => 2,
            self::SERVICIOS_ENERGETICOS => 2,
            self::MANTENIMIENTO_HIDRAULICO => 2,
            self::MANTENIMIENTO_NEUMATICO => 2,
            self::DISPOSICION_DE_RESIDUOS => 2,
            self::RECARGA_EXTINTORES => 2,
            self::SERVICIO_DE_INGENIERIA_Y_DISENO => 2,
            self::METALMECANICO => 2,
            self::AGENCIA_DE_VIAJES => 3,
            self::AGENTE_DE_CARGA => 3,
            self::ALMACENAMIENTO => 3,
            self::INMOBILIARIA => 3,
            self::ARRENDAMIENTO => 3,
            self::ASESORIA => 3,
            self::LOGISTICA_INTERNACIONAL => 3,
            self::OPERADOR_LOGISTICO => 3,
            self::ALQUILER_DE_MONTACARGAS => 3,
            self::MENSAJERIA => 3,
            self::TRANSPORTE_EJECUTIVO => 3,
            self::AGENCIAMIENTO_MARITIMO_Y_AEREO => 3,
            self::ASEGURADORA => 4,
            self::ASESORIA_JURIDICA => 4,
            self::ABOGADO => 4,
            self::AUDITORIA => 4,
            self::REVISORIA_FISCAL => 4,
            self::SEGURIDAD_SOCIAL => 4,
            self::PROTECCION_DE_DATOS => 4,
            self::AMBIENTAL => 5,
            self::SERVICIOS_DE_NUBE => 5,
            self::INFRAESTRUCTURA_IT => 5,
            self::BANCA_DIGITAL => 5,
            self::TECNOLOGIA => 5,
            self::PERIFERICOS => 5,
            self::LICENCIAMIENTO_DE_SOFTWARE => 5,
            self::CIBERSEGURIDAD => 5,
            self::MANTENIMIENTO_INFORMATICO => 5,
            self::CONECTIVIDAD_Y_TELECOMUNICACIONES => 5,
            self::IMPRESION => 5,
            self::MAQUINARIA_Y_EQUIPO => 6,
            self::IMPORTACION_REPUESTOS => 6,
            self::INSTRUMENTACION => 6,
            self::RODAMIENTOS => 6,
            self::MEDIDORES => 6,
            self::FERRETERIA => 6,
            self::PINTURAS => 6,
            self::VIDRIO => 6,
            self::COMBUSTIBLES => 6,
            self::HERRAMIENTAS => 6,
            self::ELEMENTOS_DE_PROTECCION_PERSONAL => 6,
            self::DISPOSITIVOS => 6,
            self::ACEITES => 6,
            self::LUBRICANTES => 6,
            self::REFRIGERANTES => 6,
            self::TORNILLERIA_Y_FIJACION => 6,
            self::FILTROS_INDUSTRIALES => 6,
            self::MATERIAL_ELECTRICO => 6,
            self::VARIADORES => 6,
            self::QUIMICOS => 6,
            self::REPRESENTANTE_DE_MARCA => 6,
            self::AIRES_ACONDICIONADOS => 7,
            self::MOBILIARIO => 7,
            self::SISTEMAS_DE_SEGURIDAD => 7,
            self::ELEMENTOS_DE_SEGURIDAD => 7,
            self::SISTEMAS_MODULARES => 7,
            self::VIDRIOS => 7,
            self::LAMPARAS => 7,
            self::GRIFERIA_Y_BANOS => 7,
            self::PINTURA => 7,
            self::RECUBRIMIENTOS_ESPECIALES => 7,
            self::CONTROL_DE_ACCESO => 7,
            self::ILUMINACION_INDUSTRIAL => 7,
            self::PUERTAS_INDUSTRIALES => 7,
            self::CUBIERTAS => 7,
            self::CERRAMIENTOS_Y_MALLAS => 7,
            self::CANASTAS => 8,
            self::CARROS_ABASTECIMIENTO => 8,
            self::ESTIBAS => 8,
            self::ROTULOS => 8,
            self::ETIQUETAS => 8,
            self::TABLEROS => 8,
            self::CINTAS => 8,
            self::PAPEL_STRECH => 8,
            self::INSUMOS_DE_EMPAQUE => 8,
            self::ESTANTERIA_Y_RACKS => 8,
            self::ESCALERAS_Y_AISLAMIENTO => 8,
            self::ESTIBAS_ESPECIALES => 8,
            self::ACRILICO => 9,
            self::DOTACION => 9,
            self::AVISOS => 9,
            self::SOUVENIRS => 9,
            self::SENALIZACION => 9,
            self::CORTE_LASER => 9,
            self::BASCULAS_Y_BALANZAS => 9,
            self::BONOS => 9,
            self::CALZADO_DE_SEGURIDAD => 9,
            self::CONSUMIBLES_DE_IMPRESION => 9,
            self::PAPELERIA => 9,
            self::CARPAS => 10,
            self::MEDIOS_KANBAN => 10,
            self::COLAPSIBLES => 10,
            self::MEDICION => 10,
            self::MATERIAL_LEAN => 10,
            self::SENALETICA => 10,
            self::MARCACION => 10,
        };
    }

    public function categoria(): ?CategoriaServicioSuministro
    {
        return CategoriaServicioSuministro::tryFrom($this->categoriaId());
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
