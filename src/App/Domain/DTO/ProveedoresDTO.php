<?php

namespace App\Domain\DTO;

class ProveedoresDTO {
    public function __construct(
        public string $id_proveedor,
        public ?string $numero_acreedor_proveedor,
        public string $nombre_proveedor,
        public int $id_tipo_proveedor,
        public int $id_idioma_proveedor,
        public int $id_estado_proveedor,
        public int $maneja_formato_costbreakdown_proveedor,
        public string $historia_proveedor,
        public string $descripcion_proveedor,
        public float $porcentaje_bom_proveedor,
        public ?string $logo_proveedor,
        public ?string $id_srm_proveedor,
        public int $id_categoria_proveedor,
        public ?int $id_sub_categoria_proveedor,
        public int $formulario_ambiental_proveedor,
        public int $permitir_carta_beneficiarios_finales_proveedor,
        public ?string $id_administrador_proveedor,
        public string $usuario_proveedor,
        public string $password_proveedor,
        public int $password_is_temporal_proveedor
    ) {}
}
