-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 10-09-2024 a las 15:56:24
-- Versión del servidor: 10.4.27-MariaDB
-- Versión de PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `hacebwhirlpool_proveedores`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `areas`
--

CREATE TABLE `areas` (
  `id_area` int(10) NOT NULL,
  `nombre_area` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `areas`
--

INSERT INTO `areas` (`id_area`, `nombre_area`) VALUES
(1, 'Negociación'),
(2, 'Gestión Ambiental'),
(3, 'Cumplimiento'),
(4, 'Contabilidad');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `costbreakdown`
--

CREATE TABLE `costbreakdown` (
  `id_costbreakdown` varchar(36) NOT NULL,
  `id_proveedor_costbreakdow` varchar(300) NOT NULL,
  `partnumber_costbreakdown` varchar(25) NOT NULL,
  `diligencio_costbreakdown` varchar(250) NOT NULL,
  `fecha_costbreakdown` date NOT NULL,
  `moneda_costbreakdown` varchar(3) NOT NULL,
  `incoterm_costbreakdown` varchar(3) NOT NULL,
  `volumen_anual_costbreakdown` double NOT NULL,
  `moneda_pieza_embalaje` double DEFAULT NULL,
  `porcentaje_total_embalaje` double DEFAULT NULL,
  `porcentaje_scrap` double DEFAULT NULL,
  `moneda_pieza_scrap` double DEFAULT NULL,
  `porcentaje_total_scrap` double DEFAULT NULL,
  `porcentaje_flete` double DEFAULT NULL,
  `moneda_pieza_flete` double DEFAULT NULL,
  `porcentaje_SGA` double DEFAULT NULL,
  `moneda_pieza_SGA` double DEFAULT NULL,
  `porcentaje_margen_beneficio` double DEFAULT NULL,
  `moneda_pieza_margen_beneficio` double DEFAULT NULL,
  `precio_neto_total` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `costbreakdown_amortizacion`
--

CREATE TABLE `costbreakdown_amortizacion` (
  `id_amortizacion` int(11) NOT NULL,
  `descripcion_amortizacion` varchar(300) NOT NULL,
  `inversion_amortizacion` double NOT NULL,
  `piezas_amortizadas` int(10) NOT NULL,
  `moneda_pieza_amortizacion` double NOT NULL,
  `porcentaje_total_amortizacion` double NOT NULL,
  `total_moneda_pieza_amortizacion` double NOT NULL,
  `porcentaje_final_moneda_pieza_amortizacion` double NOT NULL,
  `id_costbreakdown_amortizacion` varchar(36) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `costbreakdown_materia_prima`
--

CREATE TABLE `costbreakdown_materia_prima` (
  `id_materia_prima` int(10) NOT NULL,
  `nombre_materia_prima` varchar(300) NOT NULL,
  `moneda_unidad_materia_prima` double NOT NULL,
  `unidad_materia_prima` varchar(100) NOT NULL,
  `unidad_pieza_materia_prima` double NOT NULL,
  `moneda_pieza_materia_prima` double NOT NULL,
  `porcentaje_total_materia_prima` double DEFAULT NULL,
  `total_moneda_pieza_materia_prima` double DEFAULT NULL,
  `porcentaje_final_materia_prima` double DEFAULT NULL,
  `id_costbreakdown_materia_prima` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `costbreakdown_proceso_productivo`
--

CREATE TABLE `costbreakdown_proceso_productivo` (
  `id_proceso_productivo` int(11) NOT NULL,
  `etapa_proceso_productivo` varchar(300) NOT NULL,
  `nombre_maquina_proceso_productivo` varchar(300) NOT NULL,
  `cantidad_cavidades_proceso_productivo` double NOT NULL,
  `tiempo_ciclo_proceso_productivo` double NOT NULL,
  `eficiencia_proceso_productivo` double NOT NULL,
  `costo_maquina_hora_proceso_productivo` double NOT NULL,
  `cantidad_mano_obra_directa_proceso_productivo` double NOT NULL,
  `mano_obra_directa_proceso_productivo` double NOT NULL,
  `tiempo_setup_proceso_productivo` double NOT NULL,
  `costo_setup_hora_proceso_productivo` double NOT NULL,
  `lote_setup_proceso_productivo` double NOT NULL,
  `costo_final_maquina_proceso_productivo` double NOT NULL,
  `mano_obra_directa_final_proceso_productivo` double NOT NULL,
  `costo_final_setup_hora_proceso_productivo` double NOT NULL,
  `maquina_mano_obra_directa_setup_proceso_productivo` double NOT NULL,
  `porcentaje_total_proceso_productivo` double DEFAULT NULL,
  `total_moneda_pieza_costo_maquina` double NOT NULL,
  `porcentaje_final_moneda_pieza_costo_maquina` double DEFAULT NULL,
  `total_moneda_pieza_mano_obra_directa` double NOT NULL,
  `porcentaje_final_moneda_pieza_mano_obra_directa` double DEFAULT NULL,
  `total_moneda_pieza_costo_setup` double NOT NULL,
  `porcentaje_final_moneda_pieza_costo_setup` double DEFAULT NULL,
  `id_costbreakdown_proceso_productivo` varchar(36) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `costbreakdown_simplified`
--

CREATE TABLE `costbreakdown_simplified` (
  `id_costbreakdown_simplified` varchar(36) NOT NULL,
  `descripcion_costbreakdown_simplified` varchar(250) DEFAULT NULL,
  `porcentaje_costbreakdown_simplified` float DEFAULT NULL,
  `fecha_costbreakdown_simplified` datetime NOT NULL,
  `id_proveedor_simplified` int(15) NOT NULL,
  `partnumber_costbreakdown_simplified` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `costbreakdown_simplified_history`
--

CREATE TABLE `costbreakdown_simplified_history` (
  `id_costbreakdown_simplified_history` varchar(36) NOT NULL,
  `descripcion_costbreakdown_simplified_history` varchar(250) NOT NULL,
  `porcentaje_costbreakdown_simplified_history` double NOT NULL,
  `fecha_costbreakdown_simplified_history` datetime NOT NULL,
  `id_proveedor_simplified_history` int(15) NOT NULL,
  `partnumber_costbreakdown_simplified_history` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gestion_ambiental`
--

CREATE TABLE `gestion_ambiental` (
  `Id_gestion_ambiental` int(11) NOT NULL,
  `cuenta_sistema_gestion_ambiental` varchar(5) NOT NULL,
  `certificado_ISO_14001` tinyint(4) NOT NULL,
  `cuenta_departamento_gestion_politica_ambiental` varchar(5) NOT NULL,
  `tiene_identificados_aspectos_impactos` varchar(5) NOT NULL,
  `principales_requisitos_legales` varchar(800) NOT NULL,
  `realiza_registro_anual_autoridades` varchar(5) NOT NULL,
  `ha_obtenido_sancion` tinyint(4) NOT NULL,
  `permiso_uso_recursos_naturales` varchar(5) NOT NULL,
  `permisos_cuenta` varchar(800) DEFAULT NULL,
  `plan_manejo_integral_residuos` varchar(5) NOT NULL,
  `genera_residuos_posconsumo` varchar(5) NOT NULL,
  `controles_realiza_gestion_residuos_solidos_peligrosos` varchar(800) NOT NULL,
  `genera_vertimiento_aguas_residuales_industriales` varchar(5) NOT NULL,
  `controles_realiza_gestion_vertimientos` varchar(800) NOT NULL,
  `genera_emisiones_atmosfericas` varchar(5) NOT NULL,
  `controles_realiza_gestion_emisiones` varchar(800) NOT NULL,
  `plan_contingencia_manejo_transporte` varchar(5) NOT NULL,
  `controles_realiza_gestion_sustancias_quimicas` varchar(800) NOT NULL,
  `observaciones_gestion_ambiental` varchar(800) DEFAULT NULL,
  `Id_proveedor_gestion_ambiental` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `laft`
--

CREATE TABLE `laft` (
  `Id_laft` int(10) NOT NULL,
  `fecha_solicitud_laft` datetime NOT NULL,
  `ultima_actualizacion_laft` date DEFAULT NULL,
  `proceso_laft` varchar(20) NOT NULL,
  `tipo_persona_laft` varchar(15) NOT NULL,
  `oficial_cumplimiento` tinyint(1) DEFAULT NULL,
  `declaracion_origen_fondos_informacion` tinyint(4) DEFAULT NULL,
  `autorizacion_proteccion_datos` tinyint(4) DEFAULT NULL,
  `declaracion_etica` tinyint(4) DEFAULT NULL,
  `Id_proveedor_laft` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `laft_beneficiarios_finales`
--

CREATE TABLE `laft_beneficiarios_finales` (
  `Id_beneficiarios_finales` int(10) NOT NULL,
  `nombre_razon_social_beneficiarios_finales` varchar(350) NOT NULL,
  `tipo_identificacion_beneficiarios_finales` varchar(20) NOT NULL,
  `otro_tipo_identificacion` varchar(100) DEFAULT NULL,
  `numero_identificacion_beneficiarios_finales` varchar(15) NOT NULL,
  `porcentaje_participacion_beneficiarios_finales` int(3) NOT NULL,
  `Id_laft_beneficiarios_finales` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `laft_certificaciones`
--

CREATE TABLE `laft_certificaciones` (
  `Id_certificacion` int(11) NOT NULL,
  `ISO_9001` tinyint(4) DEFAULT NULL,
  `ISO_14001` tinyint(4) DEFAULT NULL,
  `ISO_45001` tinyint(4) DEFAULT NULL,
  `BASC` tinyint(4) DEFAULT NULL,
  `OEA` tinyint(4) DEFAULT NULL,
  `otro_certificacion` varchar(150) DEFAULT NULL,
  `Id_laft_persona_juridica_certificacion` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `laft_contacto`
--

CREATE TABLE `laft_contacto` (
  `Id_contacto` int(10) NOT NULL,
  `nombres_contacto` varchar(300) NOT NULL,
  `apellidos_contacto` varchar(300) NOT NULL,
  `cargo_contacto` varchar(200) NOT NULL,
  `numero_contacto` int(15) NOT NULL,
  `correo_electronico_contacto` varchar(300) NOT NULL,
  `Id_tipos_contacto_laft_contacto` int(10) NOT NULL,
  `Id_laft_contacto` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `laft_documentos`
--

CREATE TABLE `laft_documentos` (
  `Id_documento_laft` int(10) NOT NULL,
  `tipo_documento_laft` varchar(200) NOT NULL,
  `is_url_documento_laft` tinyint(4) DEFAULT NULL,
  `documento_laft` mediumtext NOT NULL,
  `Id_laft_documentos` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `laft_historico`
--

CREATE TABLE `laft_historico` (
  `Id_laft_historico` int(11) NOT NULL,
  `fecha_actualizacion_historico` date NOT NULL,
  `Id_proveedor_laft_historico` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `laft_pep`
--

CREATE TABLE `laft_pep` (
  `Id_pep` int(11) NOT NULL,
  `nombre_pep` varchar(250) NOT NULL,
  `tipo_documento_pep` varchar(25) NOT NULL,
  `numero_identificacion_pep` int(12) NOT NULL,
  `cargo_ocupa_pep` varchar(250) NOT NULL,
  `cargo_ocupa_ocupo_cataloga_pep` varchar(250) NOT NULL,
  `desde_cuando_pep` date NOT NULL,
  `hasta_cuando_pep` date NOT NULL,
  `Id_laft_pep` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `laft_pep_infogeneral`
--

CREATE TABLE `laft_pep_infogeneral` (
  `id_pep_infogeneral` int(10) NOT NULL,
  `maneja_o_ha_manejado_recursos_publicos` tinyint(4) NOT NULL,
  `tiene_o_ha_tenido_cargo_publico` tinyint(4) NOT NULL,
  `ocupa_o_ha_ocupado_cargo_publico_organizaciones_gubernamentales` tinyint(4) NOT NULL,
  `ocupa_o_ha_ocupado_cargo_publico_pais_diferente_colombia` tinyint(4) NOT NULL,
  `Id_laft_pep_infogeneral` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `laft_persona_juridica`
--

CREATE TABLE `laft_persona_juridica` (
  `Id_persona_juridica` varchar(300) NOT NULL,
  `razon_social_persona_juridica` varchar(350) NOT NULL,
  `tipo_identificacion_persona_juridica` varchar(50) NOT NULL,
  `otro_tipo_identificacion` varchar(250) DEFAULT NULL,
  `numero_identificacion_persona_juridica` varchar(15) NOT NULL,
  `digito_verificacion` int(5) NOT NULL,
  `Id_pais_persona_juridica` int(10) NOT NULL,
  `departamento_persona_juridica` varchar(250) DEFAULT NULL,
  `ciudad_persona_juridica` varchar(250) DEFAULT NULL,
  `direccion_persona_juridica` varchar(300) DEFAULT NULL,
  `indicativo_persona_juridica` varchar(4) NOT NULL,
  `telefono_persona_juridica` int(15) NOT NULL,
  `correo_electronico_persona_juridica` varchar(350) NOT NULL,
  `codigo_ciiu_persona_juridica` varchar(15) NOT NULL,
  `requiere_permiso_licencia_operar` tinyint(4) NOT NULL,
  `condicion_pago` varchar(12) NOT NULL,
  `cuantos_dias_condicion_pago` int(10) DEFAULT NULL,
  `Id_laft_persona_juridica` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `laft_persona_natural`
--

CREATE TABLE `laft_persona_natural` (
  `Id_persona_natural` int(10) NOT NULL,
  `nombres_persona_natural` varchar(250) NOT NULL,
  `apellidos_persona_natural` varchar(250) NOT NULL,
  `tipo_identificacion_persona_natural` varchar(100) NOT NULL,
  `numero_identificacion_persona_natural` int(12) NOT NULL,
  `direccion_persona_natural` varchar(200) DEFAULT NULL,
  `ciudad_persona_natural` varchar(250) NOT NULL,
  `departamento_persona_natural` varchar(250) NOT NULL,
  `Id_pais_persona_natural` int(10) NOT NULL,
  `indicativo_persona_natural` varchar(4) NOT NULL,
  `telefono_persona_natural` int(15) NOT NULL,
  `correo_electronico_persona_natural` varchar(300) NOT NULL,
  `sector_economico_persona_natural` varchar(200) NOT NULL,
  `requiere_permiso_licencia_operar` tinyint(4) NOT NULL,
  `condicion_pago` varchar(12) NOT NULL,
  `cuantos_dias_condicion_pago` int(10) DEFAULT NULL,
  `id_laft_persona_natural` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `laft_representante_legal`
--

CREATE TABLE `laft_representante_legal` (
  `Id_representante_legal` int(10) NOT NULL,
  `nombres_representante_legal` varchar(250) NOT NULL,
  `apellidos_representante_legal` varchar(250) NOT NULL,
  `tipo_documento_representante_legal` varchar(20) NOT NULL,
  `numero_identificacion_representante_legal` varchar(15) NOT NULL,
  `correo_electronico_representante_legal` varchar(300) NOT NULL,
  `numero_contacto_representante_legal` int(12) NOT NULL,
  `tipo_representante_legal` int(1) NOT NULL,
  `Id_laft_representante_legal` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `laft_tipos_contacto`
--

CREATE TABLE `laft_tipos_contacto` (
  `Id_tipo_contacto` int(10) NOT NULL,
  `tipo_contacto` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `laft_tipos_contacto`
--

INSERT INTO `laft_tipos_contacto` (`Id_tipo_contacto`, `tipo_contacto`) VALUES
(1, 'Comercial'),
(2, 'Financiero'),
(3, 'Oficial de Cumplimiento');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `laft_tipo_representante_legal`
--

CREATE TABLE `laft_tipo_representante_legal` (
  `Id_representante_legal` int(11) NOT NULL,
  `tipo_representante_legal` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `laft_tipo_representante_legal`
--

INSERT INTO `laft_tipo_representante_legal` (`Id_representante_legal`, `tipo_representante_legal`) VALUES
(1, 'Representante Legal'),
(2, 'Suplente Representante Legal');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pais`
--

CREATE TABLE `pais` (
  `id_pais` int(11) NOT NULL,
  `pais` varchar(400) NOT NULL,
  `indicativo_pais` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `pais`
--

INSERT INTO `pais` (`id_pais`, `pais`, `indicativo_pais`) VALUES
(1, 'Afganistán', 93),
(2, 'Albania', 355),
(3, 'Alemania', 49),
(4, 'Algeria', 213),
(5, 'Andorra', 376),
(6, 'Angola', 244),
(7, 'Anguila', 1),
(8, 'Antártida', 672),
(9, 'Antigua y Barbuda', 1),
(10, 'Antillas Neerlandesas', 599),
(11, 'Arabia Saudita', 966),
(12, 'Argentina', 54),
(13, 'Armenia', 374),
(14, 'Aruba', 297),
(15, 'Australia', 61),
(16, 'Austria', 43),
(17, 'Azerbayán', 994),
(18, 'Bahamas', 1),
(19, 'Bahrein', 973),
(20, 'Bangladesh', 880),
(21, 'Barbados', 1),
(22, 'Bélgica', 32),
(23, 'Belice', 501),
(24, 'Benín', 229),
(25, 'Bhután', 975),
(26, 'Bielorrusia', 375),
(27, 'Birmania', 95),
(28, 'Bolivia', 591),
(29, 'Bosnia y Herzegovina', 387),
(30, 'Botsuana', 267),
(31, 'Brasil', 55),
(32, 'Brunéi', 673),
(33, 'Bulgaria', 359),
(34, 'Burkina Faso', 226),
(35, 'Burundi', 257),
(36, 'Cabo Verde', 238),
(37, 'Camboya', 855),
(38, 'Camerún', 237),
(39, 'Canadá', 1),
(40, 'Chad', 235),
(41, 'Chile', 56),
(42, 'China', 86),
(43, 'Chipre', 357),
(44, 'Ciudad del Vaticano', 39),
(45, 'Colombia', 57),
(46, 'Comoras', 269),
(47, 'Congo', 242),
(48, 'Congo', 243),
(49, 'Corea del Norte', 850),
(50, 'Corea del Sur', 82),
(51, 'Costa de Marfil', 225),
(52, 'Costa Rica', 506),
(53, 'Croacia', 385),
(54, 'Cuba', 53),
(55, 'Dinamarca', 45),
(56, 'Dominica', 1),
(57, 'Ecuador', 593),
(58, 'Egipto', 20),
(59, 'El Salvador', 503),
(60, 'Emiratos Árabes Unidos', 971),
(61, 'Eritrea', 291),
(62, 'Escocia', 44),
(63, 'Eslovaquia', 421),
(64, 'Eslovenia', 386),
(65, 'España', 34),
(66, 'Estados Unidos de América', 1),
(67, 'Estonia', 372),
(68, 'Etiopía', 251),
(69, 'Filipinas', 63),
(70, 'Finlandia', 358),
(71, 'Fiyi', 679),
(72, 'Francia', 33),
(73, 'Gabón', 241),
(74, 'Gales', 44),
(75, 'Gambia', 220),
(76, 'Georgia', 995),
(77, 'Ghana', 233),
(78, 'Gibraltar', 350),
(79, 'Granada', 1),
(80, 'Grecia', 30),
(81, 'Groenlandia', 299),
(82, 'Guadalupe', 0),
(83, 'Guam', 1),
(84, 'Guatemala', 502),
(85, 'Guayana Francesa', 0),
(86, 'Guernsey', 0),
(87, 'Guinea', 224),
(88, 'Guinea Ecuatorial', 240),
(89, 'Guinea-Bissau', 245),
(90, 'Guyana', 592),
(91, 'Haití', 509),
(92, 'Honduras', 504),
(93, 'Hong kong', 852),
(94, 'Hungría', 36),
(95, 'India', 91),
(96, 'Indonesia', 62),
(97, 'Inglaterra', 44),
(98, 'Irak', 964),
(99, 'Irán', 98),
(100, 'Irlanda', 353),
(101, 'Irlanda del Norte', 44),
(102, 'Isla Bouvet', 0),
(103, 'Isla de Man', 44),
(104, 'Isla de Navidad', 61),
(105, 'Isla Norfolk', 0),
(106, 'Islandia', 354),
(107, 'Islas Bermudas', 1),
(108, 'Islas Caimán', 1),
(109, 'Islas Cocos (Keeling)', 61),
(110, 'Islas Cook', 682),
(111, 'Islas de Åland', 0),
(112, 'Islas Feroe', 298),
(113, 'Islas Georgias del Sur y Sandwich del Sur', 0),
(114, 'Islas Heard y McDonald', 0),
(115, 'Islas Maldivas', 960),
(116, 'Islas Malvinas', 500),
(117, 'Islas Marianas del Norte', 1),
(118, 'Islas Marshall', 692),
(119, 'Islas Pitcairn', 870),
(120, 'Islas Salomón', 677),
(121, 'Islas Turcas y Caicos', 1),
(122, 'Islas Ultramarinas Menores de Estados Unidos', 0),
(123, 'Islas Vírgenes Británicas', 1),
(124, 'Islas Vírgenes de los Estados Unidos', 1),
(125, 'Israel', 972),
(126, 'Italia', 39),
(127, 'Jamaica', 1),
(128, 'Japón', 81),
(129, 'Jersey', 0),
(130, 'Jordania', 962),
(131, 'Kazajistán', 7),
(132, 'Kenia', 254),
(133, 'Kirgizstán', 996),
(134, 'Kiribati', 686),
(135, 'Kuwait', 965),
(136, 'Laos', 856),
(137, 'Lesoto', 266),
(138, 'Letonia', 371),
(139, 'Líbano', 961),
(140, 'Liberia', 231),
(141, 'Libia', 218),
(142, 'Liechtenstein', 423),
(143, 'Lituania', 370),
(144, 'Luxemburgo', 352),
(145, 'Macao', 853),
(146, 'Macedônia', 389),
(147, 'Madagascar', 261),
(148, 'Malasia', 60),
(149, 'Malawi', 265),
(150, 'Mali', 223),
(151, 'Malta', 356),
(152, 'Marruecos', 212),
(153, 'Martinica', 0),
(154, 'Mauricio', 230),
(155, 'Mauritania', 222),
(156, 'Mayotte', 262),
(157, 'México', 52),
(158, 'Micronesia', 691),
(159, 'Moldavia', 373),
(160, 'Mónaco', 377),
(161, 'Mongolia', 976),
(162, 'Montenegro', 382),
(163, 'Montserrat', 1),
(164, 'Mozambique', 258),
(165, 'Namibia', 264),
(166, 'Nauru', 674),
(167, 'Nepal', 977),
(168, 'Nicaragua', 505),
(169, 'Niger', 227),
(170, 'Nigeria', 234),
(171, 'Niue', 683),
(172, 'Noruega', 47),
(173, 'Nueva Caledonia', 687),
(174, 'Nueva Zelanda', 64),
(175, 'Omán', 968),
(176, 'Países Bajos', 31),
(177, 'Pakistán', 92),
(178, 'Palau', 680),
(179, 'Palestina', 0),
(180, 'Panamá', 507),
(181, 'Papúa Nueva Guinea', 675),
(182, 'Paraguay', 595),
(183, 'Perú', 51),
(184, 'Polinesia Francesa', 689),
(185, 'Polonia', 48),
(186, 'Portugal', 351),
(187, 'Puerto Rico', 1),
(188, 'Qatar', 974),
(189, 'Reino Unido', 44),
(190, 'República Centroafricana', 236),
(191, 'República Checa', 420),
(192, 'República Dominicana', 1),
(193, 'Reunión', 0),
(194, 'Ruanda', 250),
(195, 'Rumanía', 40),
(196, 'Rusia', 7),
(197, 'Sahara Occidental', 0),
(198, 'Samoa', 685),
(199, 'Samoa Americana', 1),
(200, 'San Bartolomé', 590),
(201, 'San Cristóbal y Nieves', 1),
(202, 'San Marino', 378),
(203, 'San Martín (Francia)', 1),
(204, 'San Pedro y Miquelón', 508),
(205, 'San Vicente y las Granadinas', 1),
(206, 'Santa Elena', 290),
(207, 'Santa Lucía', 1),
(208, 'Santo Tomé y Príncipe', 239),
(209, 'Senegal', 221),
(210, 'Serbia', 381),
(211, 'Seychelles', 248),
(212, 'Sierra Leona', 232),
(213, 'Singapur', 65),
(214, 'Siria', 963),
(215, 'Somalia', 252),
(216, 'Sri lanka', 94),
(217, 'Sudáfrica', 27),
(218, 'Sudán', 249),
(219, 'Suecia', 46),
(220, 'Suiza', 41),
(221, 'Surinám', 597),
(222, 'Svalbard y Jan Mayen', 0),
(223, 'Swazilandia', 268),
(224, 'Tadjikistán', 992),
(225, 'Tailandia', 66),
(226, 'Taiwán', 886),
(227, 'Tanzania', 255),
(228, 'Territorio Británico del Océano Índico', 0),
(229, 'Territorios Australes y Antárticas Franceses', 0),
(230, 'Timor Oriental', 670),
(231, 'Togo', 228),
(232, 'Tokelau', 690),
(233, 'Tonga', 676),
(234, 'Trinidad y Tobago', 1),
(235, 'Tunez', 216),
(236, 'Turkmenistán', 993),
(237, 'Turquía', 90),
(238, 'Tuvalu', 688),
(239, 'Ucrania', 380),
(240, 'Uganda', 256),
(241, 'Uruguay', 598),
(242, 'Uzbekistán', 998),
(243, 'Vanuatu', 678),
(244, 'Venezuela', 58),
(245, 'Vietnam', 84),
(246, 'Wallis y Futuna', 681),
(247, 'Yemen', 967),
(248, 'Yibuti', 253),
(249, 'Zambia', 260),
(250, 'Zimbabue', 263);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `politicas_ambientales`
--

CREATE TABLE `politicas_ambientales` (
  `Id_politica_ambiental` int(11) NOT NULL,
  `politica_sostenibilidad` int(11) NOT NULL,
  `politica_ambiental` int(11) NOT NULL,
  `seguridad_salud_trabajo` int(11) NOT NULL,
  `politica_derechos_humanos` int(11) NOT NULL,
  `politica_debida_diligencia` int(11) NOT NULL,
  `politica_prevencion` int(11) NOT NULL,
  `codigo_etica_empresarial` int(11) NOT NULL,
  `politica_igualdad` int(11) NOT NULL,
  `Id_proveedor_politicas_ambientales` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedores`
--

CREATE TABLE `proveedores` (
  `Id_proveedor` varchar(300) NOT NULL,
  `numero_acreedor` int(15) DEFAULT NULL,
  `nombre_proveedor` varchar(250) NOT NULL,
  `commodity_proveedor` varchar(250) DEFAULT NULL,
  `tipo_proveedor` varchar(12) NOT NULL,
  `idioma_proveedor` varchar(2) NOT NULL,
  `maneja_formato_costbreakdown` tinyint(1) DEFAULT NULL,
  `Id_categoria` int(11) DEFAULT NULL,
  `Id_sub_categoria` int(11) DEFAULT NULL,
  `formulario_ambiental` tinyint(1) DEFAULT NULL,
  `correo_negociador` varchar(300) NOT NULL,
  `proveedor_aprobado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedor_categorias`
--

CREATE TABLE `proveedor_categorias` (
  `Id_categoria` int(11) NOT NULL,
  `descripcion_categoria` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `proveedor_categorias`
--

INSERT INTO `proveedor_categorias` (`Id_categoria`, `descripcion_categoria`) VALUES
(1, 'Industrial Cluster'),
(2, 'SG&A Cluster'),
(3, 'Supply Chain Cluster');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedor_commodities`
--

CREATE TABLE `proveedor_commodities` (
  `Id_commodity` int(11) NOT NULL,
  `descripcion_commodity` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `proveedor_commodities`
--

INSERT INTO `proveedor_commodities` (`Id_commodity`, `descripcion_commodity`) VALUES
(1, 'STEEL\r\n'),
(2, 'FASTENERS'),
(3, 'RESINS'),
(4, 'RUBBER, HOSES, MISC PLAS'),
(5, 'PUMPS'),
(6, 'METAL COMPONENTS'),
(7, 'ELME'),
(8, 'ELECTRONICS'),
(9, 'WIRE & HARNESS'),
(10, 'PACKAGE/LITERATURE/INSULATION'),
(11, 'STRUCTURE AND AESTHETICS'),
(12, 'INJECTION MOLDING'),
(13, 'GLASS'),
(14, 'HEAT'),
(15, 'MOTORS');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedor_partnumbers`
--

CREATE TABLE `proveedor_partnumbers` (
  `partnumber` varchar(20) NOT NULL,
  `descripcion_partnumber` varchar(300) NOT NULL,
  `id_proveedor_partnumber` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedor_sub_categoria`
--

CREATE TABLE `proveedor_sub_categoria` (
  `Id_sub_categoria` int(11) NOT NULL,
  `descripcion_sub_categoria` varchar(400) NOT NULL,
  `id_categoria_sub_categoria` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `proveedor_sub_categoria`
--

INSERT INTO `proveedor_sub_categoria` (`Id_sub_categoria`, `descripcion_sub_categoria`, `id_categoria_sub_categoria`) VALUES
(1, 'Utilities', 1),
(2, 'Human Resource', 1),
(3, 'C&T', 1),
(4, 'Facilities', 1),
(5, 'MRO', 1),
(6, 'Engineering', 1),
(7, 'IT', 2),
(8, 'Finance', 2),
(9, 'Legal', 2),
(10, 'Travel', 2),
(11, 'International Inbound', 3),
(12, 'National Inbound', 3),
(13, 'Handing & Warehousing', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proyectos_programas_ambientales`
--

CREATE TABLE `proyectos_programas_ambientales` (
  `Id_proyectos_programas_ambientales` int(11) NOT NULL,
  `produccion_limpia` int(11) NOT NULL,
  `economia_circular` int(11) NOT NULL,
  `cambio_climatico` int(11) NOT NULL,
  `huella_carbono` int(11) NOT NULL,
  `net_zero_carbono_neutro` int(11) NOT NULL,
  `energias_renovables` int(11) NOT NULL,
  `energia_verde_I_REC` int(11) NOT NULL,
  `eficiencia_energetica` int(11) NOT NULL,
  `ecoeficiencia_operacional` int(11) NOT NULL,
  `sustancias_quimicas_ambientalmente_amigables` int(11) NOT NULL,
  `reutilizacion_recirculacion_agua` int(11) NOT NULL,
  `aprovechamiento_aguas_lluvias` int(11) NOT NULL,
  `automatizacion_digitalizacion_papel_cero` int(11) NOT NULL,
  `basura_cero` int(11) NOT NULL,
  `cero_vertimientos` int(11) NOT NULL,
  `cero_emisiones` int(11) NOT NULL,
  `ecodiseno_productos_embalajes` int(11) NOT NULL,
  `analisis_ciclo_vida` int(11) NOT NULL,
  `contratacion_personas_discapacidad` int(11) NOT NULL,
  `contratacion_mujeres_altos_cargos_directivos` int(11) NOT NULL,
  `seleccion_contratacion_criterios_diversidad` int(11) NOT NULL,
  `derechos_laborales` int(11) NOT NULL,
  `evaluacion_proveedores_criterios_sociales_ambientales` int(11) NOT NULL,
  `desarrollo_cadena_suministro_local` int(11) NOT NULL,
  `inversiones_sostenibles` int(11) NOT NULL,
  `Id_proveedor_proyectos_programas_ambientales` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id_rol` int(11) NOT NULL,
  `decripcion_rol` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id_rol`, `decripcion_rol`) VALUES
(1, 'Super-Admin'),
(2, 'Usuario'),
(3, 'Proveedor');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sostenibilidad_ambiental`
--

CREATE TABLE `sostenibilidad_ambiental` (
  `Id_sostenibilidad_ambiental` int(11) NOT NULL,
  `identificado_grupos_interes` varchar(5) NOT NULL,
  `realizado_analisis_materialidad` varchar(5) NOT NULL,
  `cuenta_estrategia_sostenibilidad` varchar(5) NOT NULL,
  `priorizado_objetivos_desarrollo_sostenible` varchar(5) NOT NULL,
  `cuenta_programas_inversion` varchar(5) NOT NULL,
  `cuenta_programas_mejorar_desempeno_ambiental` varchar(5) NOT NULL,
  `cuenta_programas_buen_gobierno_corporativo` varchar(5) NOT NULL,
  `inscrito_iniciativa_fondos_sostenibles` varchar(800) NOT NULL,
  `realiza_reporte_memoria_sostenibilidad` varchar(5) NOT NULL,
  `Id_proveedor_sostenibilidad_ambiental` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `Id_usuario` int(11) NOT NULL,
  `nombre_usuario` varchar(250) DEFAULT NULL,
  `apellidos_usuario` varchar(250) DEFAULT NULL,
  `correo_usuario` varchar(200) NOT NULL,
  `password_usuario` varchar(200) NOT NULL,
  `Id_area_usuario` int(10) DEFAULT NULL,
  `estado_registro` tinyint(1) NOT NULL,
  `is_temporal` tinyint(1) NOT NULL,
  `id_rol_usuarios` int(10) NOT NULL,
  `id_proveedor_usuarios` varchar(300) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vinculacion_proveedor`
--

CREATE TABLE `vinculacion_proveedor` (
  `Id_vinculacion_proveedor` int(11) NOT NULL,
  `aprobado_cumplimiento` tinyint(4) DEFAULT NULL,
  `observaciones_cumplimiento` varchar(500) DEFAULT NULL,
  `aprobado_ambiental` tinyint(4) DEFAULT NULL,
  `observaciones_ambiental` varchar(500) DEFAULT NULL,
  `aprobado_negociacion` tinyint(4) DEFAULT NULL,
  `observaciones_negociacion` varchar(500) DEFAULT NULL,
  `Id_proveedor_vinculacion_proveedor` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `areas`
--
ALTER TABLE `areas`
  ADD PRIMARY KEY (`id_area`);

--
-- Indices de la tabla `costbreakdown`
--
ALTER TABLE `costbreakdown`
  ADD PRIMARY KEY (`id_costbreakdown`),
  ADD KEY `id_proveedor_costbreakdow` (`id_proveedor_costbreakdow`),
  ADD KEY `partnumber_costbreakdown` (`partnumber_costbreakdown`);

--
-- Indices de la tabla `costbreakdown_amortizacion`
--
ALTER TABLE `costbreakdown_amortizacion`
  ADD PRIMARY KEY (`id_amortizacion`),
  ADD KEY `id_costbreakdown_amortizacion` (`id_costbreakdown_amortizacion`);

--
-- Indices de la tabla `costbreakdown_materia_prima`
--
ALTER TABLE `costbreakdown_materia_prima`
  ADD PRIMARY KEY (`id_materia_prima`),
  ADD KEY `id_costbreakdown_materia_prima` (`id_costbreakdown_materia_prima`);

--
-- Indices de la tabla `costbreakdown_proceso_productivo`
--
ALTER TABLE `costbreakdown_proceso_productivo`
  ADD PRIMARY KEY (`id_proceso_productivo`),
  ADD KEY `id_costbreakdown_proceso_productivo` (`id_costbreakdown_proceso_productivo`);

--
-- Indices de la tabla `costbreakdown_simplified`
--
ALTER TABLE `costbreakdown_simplified`
  ADD PRIMARY KEY (`id_costbreakdown_simplified`),
  ADD KEY `id_proveedor_simplified` (`id_proveedor_simplified`),
  ADD KEY `partnumber_costbreakdown_simpl_3` (`partnumber_costbreakdown_simplified`),
  ADD KEY `partnumber_costbreakdown_simplified` (`partnumber_costbreakdown_simplified`);

--
-- Indices de la tabla `costbreakdown_simplified_history`
--
ALTER TABLE `costbreakdown_simplified_history`
  ADD PRIMARY KEY (`id_costbreakdown_simplified_history`);

--
-- Indices de la tabla `gestion_ambiental`
--
ALTER TABLE `gestion_ambiental`
  ADD PRIMARY KEY (`Id_gestion_ambiental`);

--
-- Indices de la tabla `laft`
--
ALTER TABLE `laft`
  ADD PRIMARY KEY (`Id_laft`);

--
-- Indices de la tabla `laft_beneficiarios_finales`
--
ALTER TABLE `laft_beneficiarios_finales`
  ADD PRIMARY KEY (`Id_beneficiarios_finales`),
  ADD KEY `Id_laft_beneficiarios_finales` (`Id_laft_beneficiarios_finales`);

--
-- Indices de la tabla `laft_certificaciones`
--
ALTER TABLE `laft_certificaciones`
  ADD PRIMARY KEY (`Id_certificacion`);

--
-- Indices de la tabla `laft_contacto`
--
ALTER TABLE `laft_contacto`
  ADD PRIMARY KEY (`Id_contacto`),
  ADD KEY `Id_laft_contacto` (`Id_laft_contacto`),
  ADD KEY `Id_tipos_contacto_laft_contacto` (`Id_tipos_contacto_laft_contacto`);

--
-- Indices de la tabla `laft_documentos`
--
ALTER TABLE `laft_documentos`
  ADD PRIMARY KEY (`Id_documento_laft`),
  ADD KEY `Id_laft_documentos` (`Id_laft_documentos`);

--
-- Indices de la tabla `laft_historico`
--
ALTER TABLE `laft_historico`
  ADD PRIMARY KEY (`Id_laft_historico`);

--
-- Indices de la tabla `laft_pep`
--
ALTER TABLE `laft_pep`
  ADD PRIMARY KEY (`Id_pep`),
  ADD KEY `Id_laft_pep` (`Id_laft_pep`);

--
-- Indices de la tabla `laft_pep_infogeneral`
--
ALTER TABLE `laft_pep_infogeneral`
  ADD PRIMARY KEY (`id_pep_infogeneral`),
  ADD KEY `Id_laft_pep_infogeneral` (`Id_laft_pep_infogeneral`);

--
-- Indices de la tabla `laft_persona_juridica`
--
ALTER TABLE `laft_persona_juridica`
  ADD PRIMARY KEY (`Id_persona_juridica`),
  ADD KEY `Id_laft_persona_juridica` (`Id_laft_persona_juridica`);

--
-- Indices de la tabla `laft_persona_natural`
--
ALTER TABLE `laft_persona_natural`
  ADD PRIMARY KEY (`Id_persona_natural`),
  ADD KEY `id_laft_persona_natural` (`id_laft_persona_natural`),
  ADD KEY `Id_pais_persona_natural` (`Id_pais_persona_natural`);

--
-- Indices de la tabla `laft_representante_legal`
--
ALTER TABLE `laft_representante_legal`
  ADD PRIMARY KEY (`Id_representante_legal`),
  ADD KEY `Id_laft_representante_legal` (`Id_laft_representante_legal`);

--
-- Indices de la tabla `laft_tipos_contacto`
--
ALTER TABLE `laft_tipos_contacto`
  ADD PRIMARY KEY (`Id_tipo_contacto`);

--
-- Indices de la tabla `laft_tipo_representante_legal`
--
ALTER TABLE `laft_tipo_representante_legal`
  ADD PRIMARY KEY (`Id_representante_legal`);

--
-- Indices de la tabla `pais`
--
ALTER TABLE `pais`
  ADD PRIMARY KEY (`id_pais`);

--
-- Indices de la tabla `politicas_ambientales`
--
ALTER TABLE `politicas_ambientales`
  ADD PRIMARY KEY (`Id_politica_ambiental`);

--
-- Indices de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  ADD PRIMARY KEY (`Id_proveedor`),
  ADD KEY `Id_categoria` (`Id_categoria`,`Id_sub_categoria`),
  ADD KEY `Id_sub_categoria` (`Id_sub_categoria`);

--
-- Indices de la tabla `proveedor_categorias`
--
ALTER TABLE `proveedor_categorias`
  ADD PRIMARY KEY (`Id_categoria`);

--
-- Indices de la tabla `proveedor_commodities`
--
ALTER TABLE `proveedor_commodities`
  ADD PRIMARY KEY (`Id_commodity`);

--
-- Indices de la tabla `proveedor_partnumbers`
--
ALTER TABLE `proveedor_partnumbers`
  ADD PRIMARY KEY (`partnumber`);

--
-- Indices de la tabla `proveedor_sub_categoria`
--
ALTER TABLE `proveedor_sub_categoria`
  ADD PRIMARY KEY (`Id_sub_categoria`),
  ADD KEY `id_categoria_sub_categoria` (`id_categoria_sub_categoria`);

--
-- Indices de la tabla `proyectos_programas_ambientales`
--
ALTER TABLE `proyectos_programas_ambientales`
  ADD PRIMARY KEY (`Id_proyectos_programas_ambientales`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `sostenibilidad_ambiental`
--
ALTER TABLE `sostenibilidad_ambiental`
  ADD PRIMARY KEY (`Id_sostenibilidad_ambiental`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`Id_usuario`),
  ADD KEY `id_rol_usuarios` (`id_rol_usuarios`),
  ADD KEY `Id_area_usuario_2` (`Id_area_usuario`);

--
-- Indices de la tabla `vinculacion_proveedor`
--
ALTER TABLE `vinculacion_proveedor`
  ADD PRIMARY KEY (`Id_vinculacion_proveedor`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `areas`
--
ALTER TABLE `areas`
  MODIFY `id_area` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `costbreakdown_amortizacion`
--
ALTER TABLE `costbreakdown_amortizacion`
  MODIFY `id_amortizacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=227;

--
-- AUTO_INCREMENT de la tabla `costbreakdown_materia_prima`
--
ALTER TABLE `costbreakdown_materia_prima`
  MODIFY `id_materia_prima` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=391;

--
-- AUTO_INCREMENT de la tabla `costbreakdown_proceso_productivo`
--
ALTER TABLE `costbreakdown_proceso_productivo`
  MODIFY `id_proceso_productivo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=369;

--
-- AUTO_INCREMENT de la tabla `gestion_ambiental`
--
ALTER TABLE `gestion_ambiental`
  MODIFY `Id_gestion_ambiental` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `laft`
--
ALTER TABLE `laft`
  MODIFY `Id_laft` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `laft_beneficiarios_finales`
--
ALTER TABLE `laft_beneficiarios_finales`
  MODIFY `Id_beneficiarios_finales` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `laft_certificaciones`
--
ALTER TABLE `laft_certificaciones`
  MODIFY `Id_certificacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `laft_contacto`
--
ALTER TABLE `laft_contacto`
  MODIFY `Id_contacto` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `laft_documentos`
--
ALTER TABLE `laft_documentos`
  MODIFY `Id_documento_laft` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `laft_historico`
--
ALTER TABLE `laft_historico`
  MODIFY `Id_laft_historico` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `laft_pep`
--
ALTER TABLE `laft_pep`
  MODIFY `Id_pep` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `laft_pep_infogeneral`
--
ALTER TABLE `laft_pep_infogeneral`
  MODIFY `id_pep_infogeneral` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `laft_persona_natural`
--
ALTER TABLE `laft_persona_natural`
  MODIFY `Id_persona_natural` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `laft_representante_legal`
--
ALTER TABLE `laft_representante_legal`
  MODIFY `Id_representante_legal` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `laft_tipos_contacto`
--
ALTER TABLE `laft_tipos_contacto`
  MODIFY `Id_tipo_contacto` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `laft_tipo_representante_legal`
--
ALTER TABLE `laft_tipo_representante_legal`
  MODIFY `Id_representante_legal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `pais`
--
ALTER TABLE `pais`
  MODIFY `id_pais` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=251;

--
-- AUTO_INCREMENT de la tabla `politicas_ambientales`
--
ALTER TABLE `politicas_ambientales`
  MODIFY `Id_politica_ambiental` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `proveedor_categorias`
--
ALTER TABLE `proveedor_categorias`
  MODIFY `Id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `proveedor_commodities`
--
ALTER TABLE `proveedor_commodities`
  MODIFY `Id_commodity` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `proveedor_sub_categoria`
--
ALTER TABLE `proveedor_sub_categoria`
  MODIFY `Id_sub_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `proyectos_programas_ambientales`
--
ALTER TABLE `proyectos_programas_ambientales`
  MODIFY `Id_proyectos_programas_ambientales` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `sostenibilidad_ambiental`
--
ALTER TABLE `sostenibilidad_ambiental`
  MODIFY `Id_sostenibilidad_ambiental` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `Id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT de la tabla `vinculacion_proveedor`
--
ALTER TABLE `vinculacion_proveedor`
  MODIFY `Id_vinculacion_proveedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `costbreakdown`
--
ALTER TABLE `costbreakdown`
  ADD CONSTRAINT `costbreakdown_ibfk_2` FOREIGN KEY (`partnumber_costbreakdown`) REFERENCES `proveedor_partnumbers` (`partnumber`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `costbreakdown_amortizacion`
--
ALTER TABLE `costbreakdown_amortizacion`
  ADD CONSTRAINT `costbreakdown_amortizacion_ibfk_1` FOREIGN KEY (`id_costbreakdown_amortizacion`) REFERENCES `costbreakdown` (`id_costbreakdown`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `costbreakdown_simplified`
--
ALTER TABLE `costbreakdown_simplified`
  ADD CONSTRAINT `costbreakdown_simplified_ibfk_2` FOREIGN KEY (`partnumber_costbreakdown_simplified`) REFERENCES `proveedor_partnumbers` (`partnumber`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `laft_contacto`
--
ALTER TABLE `laft_contacto`
  ADD CONSTRAINT `laft_contacto_ibfk_1` FOREIGN KEY (`Id_tipos_contacto_laft_contacto`) REFERENCES `laft_tipos_contacto` (`Id_tipo_contacto`) ON DELETE CASCADE;

--
-- Filtros para la tabla `laft_persona_natural`
--
ALTER TABLE `laft_persona_natural`
  ADD CONSTRAINT `laft_persona_natural_ibfk_2` FOREIGN KEY (`Id_pais_persona_natural`) REFERENCES `pais` (`id_pais`);

--
-- Filtros para la tabla `proveedores`
--
ALTER TABLE `proveedores`
  ADD CONSTRAINT `proveedores_ibfk_1` FOREIGN KEY (`Id_categoria`) REFERENCES `proveedor_categorias` (`Id_categoria`),
  ADD CONSTRAINT `proveedores_ibfk_2` FOREIGN KEY (`Id_sub_categoria`) REFERENCES `proveedor_sub_categoria` (`Id_sub_categoria`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `proveedor_sub_categoria`
--
ALTER TABLE `proveedor_sub_categoria`
  ADD CONSTRAINT `proveedor_sub_categoria_ibfk_1` FOREIGN KEY (`id_categoria_sub_categoria`) REFERENCES `proveedor_categorias` (`Id_categoria`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`id_rol_usuarios`) REFERENCES `roles` (`id_rol`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
