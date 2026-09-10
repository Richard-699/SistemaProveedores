<?php

namespace App\Domain\DTO;

class AdministradoresPermisosDTO
{
    /**
     * @param PermisosDTO[] $permisosDTO
     */
    public function __construct(
        public ?string $id_administrador_permiso = null,
        public ?int $id_permiso_administrador = null,
        public array $permisosDTO = [],
        public ?string $nombre_permiso = null,
        public ?string $descripcion_permiso = null
    ) {}
}
