<?php

namespace App\Aplication\Service\Auth;

use App\Aplication\Interface\Repository\Auth\IAreasRepository;
use App\Aplication\Interface\Service\Auth\IAreasService;

class AreasService implements IAreasService {

    private IAreasRepository $areasRepository;

    public function __construct(IAreasRepository $areasRepository) {
        $this->areasRepository = $areasRepository;
    }

    public function obtenerAreasParaRegistro(): array {
        $areas = $this->areasRepository->getAllAreas();
        $areasFiltradas = [];
        foreach ($areas as $area) {
            $areasFiltradas[] = [
                'id_area' => $area['id_area'],
                'nombre_area' => $area['nombre_area']
            ];
        }
        return $areasFiltradas;
    }
}
