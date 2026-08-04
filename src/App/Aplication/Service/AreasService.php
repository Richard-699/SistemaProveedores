<?php

namespace App\Aplication\Service;

use App\Aplication\Interface\Service\IAreasService;
use App\Infrastructure\Repository\AreasRepository;
use App\Infrastructure\Database\Connection;

class AreasService implements IAreasService
{

    private $db;
    private $areasRepository;

    public function __construct()
    {
        $this->db = (new Connection())->dbsistemas_proveedores;
        $this->areasRepository = new AreasRepository($this->db);
    }

    public function obtenerAreas(): array
    {
        $areas = $this->areasRepository->findAll();
        $areasFiltradas = [];
        foreach ($areas as $area) {
            $areasFiltradas[] = [
                'id_area' => $area->id_area,
                'nombre_area' => $area->nombre_area
            ];
        }
        return $areasFiltradas;
    }
}
