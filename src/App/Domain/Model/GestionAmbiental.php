<?php

namespace App\Domain\Model;

class GestionAmbiental
{
    public function __construct(
        public int $id_gestion_ambiental,
        public ?string $cuenta_sistema_gestion_ambiental,
        public ?int $certificado_ISO_14001_gestion_ambiental,
        public ?string $cuenta_departamento_gestion_politica_ambiental,
        public ?string $tiene_identificados_aspectos_impactos_gestion_ambiental,
        public ?string $principales_requisitos_legales_gestion_ambiental,
        public ?string $realiza_registro_anual_autoridades_gestion_ambiental,
        public ?int $ha_obtenido_sancion_gestion_ambiental,
        public ?string $permiso_uso_recursos_naturales,
        public ?string $permisos_cuenta_gestion_ambiental,
        public ?string $plan_manejo_integral_residuos_gestion_ambiental,
        public ?string $genera_residuos_posconsumo_gestion_ambiental,
        public ?string $controles_realiza_gestion_residuos_solidos_peligrosos_gestion_am,
        public ?string $genera_vertimiento_aguas_residuales_industriales_gestion_ambient,
        public ?string $controles_realiza_gestion_vertimientos_gestion_ambiental,
        public ?string $genera_emisiones_atmosfericas_gestion_ambiental,
        public ?string $controles_realiza_gestion_emisiones_gestion_ambiental,
        public ?string $plan_contingencia_manejo_transporte_gestion_ambiental,
        public ?string $controles_realiza_gestion_sustancias_quimicas_gestion_ambiental,
        public ?string $observaciones_gestion_ambiental,
        public string $id_proveedor_gestion_ambiental
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            $data['id_gestion_ambiental'] ?? null,
            $data['cuenta_sistema_gestion_ambiental'] ?? null,
            $data['certificado_ISO_14001_gestion_ambiental'] ?? null,
            $data['cuenta_departamento_gestion_politica_ambiental'] ?? null,
            $data['tiene_identificados_aspectos_impactos_gestion_ambiental'] ?? null,
            $data['principales_requisitos_legales_gestion_ambiental'] ?? null,
            $data['realiza_registro_anual_autoridades_gestion_ambiental'] ?? null,
            $data['ha_obtenido_sancion_gestion_ambiental'] ?? null,
            $data['permiso_uso_recursos_naturales'] ?? null,
            $data['permisos_cuenta_gestion_ambiental'] ?? null,
            $data['plan_manejo_integral_residuos_gestion_ambiental'] ?? null,
            $data['genera_residuos_posconsumo_gestion_ambiental'] ?? null,
            $data['controles_realiza_gestion_residuos_solidos_peligrosos_gestion_am'] ?? null,
            $data['genera_vertimiento_aguas_residuales_industriales_gestion_ambient'] ?? null,
            $data['controles_realiza_gestion_vertimientos_gestion_ambiental'] ?? null,
            $data['genera_emisiones_atmosfericas_gestion_ambiental'] ?? null,
            $data['controles_realiza_gestion_emisiones_gestion_ambiental'] ?? null,
            $data['plan_contingencia_manejo_transporte_gestion_ambiental'] ?? null,
            $data['controles_realiza_gestion_sustancias_quimicas_gestion_ambiental'] ?? null,
            $data['observaciones_gestion_ambiental'] ?? null,
            $data['id_proveedor_gestion_ambiental'] ?? null
        );
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
