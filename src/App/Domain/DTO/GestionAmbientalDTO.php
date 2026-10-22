<?php

namespace App\Domain\DTO;

class GestionAmbientalDTO
{
    public function __construct(
        public ?int $id_gestion_ambiental = null,
        public ?string $cuenta_sistema_gestion_ambiental = null,
        public ?int $certificado_ISO_14001_gestion_ambiental = null,
        public ?string $cuenta_departamento_gestion_politica_ambiental = null,
        public ?string $tiene_identificados_aspectos_impactos_gestion_ambiental = null,
        public ?string $principales_requisitos_legales_gestion_ambiental = null,
        public ?string $realiza_registro_anual_autoridades_gestion_ambiental = null,
        public ?int $ha_obtenido_sancion_gestion_ambiental = null,
        public ?string $permiso_uso_recursos_naturales = null,
        public ?string $permisos_cuenta_gestion_ambiental = null,
        public ?string $plan_manejo_integral_residuos_gestion_ambiental = null,
        public ?string $genera_residuos_posconsumo_gestion_ambiental = null,
        public ?string $controles_realiza_gestion_residuos_solidos_peligrosos_gestion_am = null,
        public ?string $genera_vertimiento_aguas_residuales_industriales_gestion_ambient = null,
        public ?string $controles_realiza_gestion_vertimientos_gestion_ambiental = null,
        public ?string $genera_emisiones_atmosfericas_gestion_ambiental = null,
        public ?string $controles_realiza_gestion_emisiones_gestion_ambiental = null,
        public ?string $plan_contingencia_manejo_transporte_gestion_ambiental = null,
        public ?string $controles_realiza_gestion_sustancias_quimicas_gestion_ambiental = null,
        public ?string $observaciones_gestion_ambiental = null,
        public ?string $id_proveedor_gestion_ambiental = null
    ) {}
}
