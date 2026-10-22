<?php

namespace App\Domain\Model;

class Proveedores {
    public function __construct(
        public string $id_proveedor,
        public ?string $numero_acreedor_proveedor,
        public string $nombre_proveedor,
        public int $id_tipo_proveedor,
        public int $id_idioma_proveedor,
        public int $id_estado_proveedor,
        public int $maneja_formato_costbreakdown_proveedor,
        public ?string $historia_proveedor = '',
        public ?string $descripcion_proveedor = '',
        public ?float $porcentaje_bom_proveedor = 0.0,
        public ?string $logo_proveedor = null,
        public ?string $id_srm_proveedor = null,
        public ?int $id_categoria_proveedor = null,
        public ?int $id_sub_categoria_proveedor = null,
        public int $formulario_ambiental_proveedor = 0,
        public ?int $permitir_carta_beneficiarios_finales_proveedor = 0,
        public ?string $id_administrador_proveedor = null,
        public ?string $usuario_proveedor = '',
        public ?string $password_proveedor = '',
        public ?int $password_is_temporal_proveedor = 0
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            $data['id_proveedor'] ?? null,
            $data['numero_acreedor_proveedor'] ?? null,
            $data['nombre_proveedor'] ?? null,
            $data['id_tipo_proveedor'] ?? null,
            $data['id_idioma_proveedor'] ?? null,
            $data['id_estado_proveedor'] ?? null,
            $data['maneja_formato_costbreakdown_proveedor'] ?? null,
            $data['historia_proveedor'] ?? '',
            $data['descripcion_proveedor'] ?? '',
            $data['porcentaje_bom_proveedor'] ?? 0.0,
            $data['logo_proveedor'] ?? null,
            $data['id_srm_proveedor'] ?? null,
            $data['id_categoria_proveedor'] ?? null,
            $data['id_sub_categoria_proveedor'] ?? null,
            $data['formulario_ambiental_proveedor'] ?? 0,
            $data['permitir_carta_beneficiarios_finales_proveedor'] ?? 0,
            $data['id_administrador_proveedor'] ?? null,
            $data['usuario_proveedor'] ?? '',
            $data['password_proveedor'] ?? '',
            $data['password_is_temporal_proveedor'] ?? 0
        );
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
