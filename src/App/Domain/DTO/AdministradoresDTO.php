<?php

namespace App\Domain\DTO;

class AdministradoresDTO
{
    /**
     * @param PermisosDTO[] $permisosDTO
     */
    public function __construct(
        public string $id_administrador,
        public string $nombre_administrador,
        public string $apellidos_administrador,
        public string $correo_hwi_administrador,
        public ?int $id_area_administrador,
        public string $password_administrador,
        public int $id_estado_administrador,
        public int $password_is_temporal,
        public array $permisosDTO = [],
        public ?bool $is_admin = null,
        public ?string $password_raw = null,
        public ?string $confirm_password = null
    ) {}
}
