<?php

namespace App\Domain\DTO;

class AreasDTO
{
    public function __construct(
        public int $id_area,
        public string $nombre_area
    ) {}
}
