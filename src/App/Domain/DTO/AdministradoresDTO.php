<?php

namespace App\Domain\DTO;

class AdministradoresDTO
{
    /**
     * @param PermisosDTO[] $permisosDTO
     */
    public function __construct(
        public ?string $id_administrador = null,
        public ?string $nombre_administrador = null,
        public ?string $apellidos_administrador = null,
        public ?string $correo_hwi_administrador = null,
        public ?int $id_area_administrador = null,
        public ?string $password_administrador = null,
        public ?int $id_estado_administrador = null,
        public ?int $password_is_temporal = null,
        public array $permisosDTO = [],
        public ?bool $is_admin = null,
        public ?string $password_raw = null,
        public ?string $confirm_password = null,
        public ?string $nombre_area = null,
        public array $permisos = []
    ) {}
}
