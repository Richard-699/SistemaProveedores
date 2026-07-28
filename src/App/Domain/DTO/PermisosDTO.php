<?php

namespace App\Domain\DTO;

class PermisosDTO {
    public function __construct(
        public int $id_permiso,
        public string $nombre_permiso,
        public string $descripcion_permiso
    ) {}
}
