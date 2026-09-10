<?php

namespace App\Aplication\Service;

use App\Aplication\Interface\Service\IProveedoresService;
use App\Infrastructure\Repository\ProveedoresRepository;
use App\Infrastructure\Repository\AdministradoresRepository;
use App\Infrastructure\Repository\TipoProveedorRepository;
use App\Infrastructure\Repository\EstadosRepository;
use App\Infrastructure\Database\Connection;
use App\Shared\Mapper\Mapper;
use Exception;
use Throwable;

class ProveedoresService implements IProveedoresService
{

    private \PDO $db;
    private ProveedoresRepository $proveedoresRepository;
    private AdministradoresRepository $administradoresRepository;
    private TipoProveedorRepository $tipoProveedorRepository;
    private EstadosRepository $estadosRepository;

    public function __construct()
    {
        $this->db = (new Connection())->dbsistemas_proveedores;
        $this->proveedoresRepository = new ProveedoresRepository($this->db);
        $this->administradoresRepository = new AdministradoresRepository($this->db);
        $this->tipoProveedorRepository = new TipoProveedorRepository($this->db);
        $this->estadosRepository = new EstadosRepository($this->db);
    }

    public function obtenerProveedores(): array
    {
        try {
            $proveedoresModels = $this->proveedoresRepository->findAll();
            if (empty($proveedoresModels)) {
                throw new Exception("No se encontraron proveedores.");
            }

            $proveedoresDTOs = Mapper::modelToListProveedoresDTO($proveedoresModels);

            $tiposModels = $this->tipoProveedorRepository->findAll();
            if (empty($tiposModels)) {
                throw new Exception("No se encontraron tipos de proveedor.");
            }
            $tiposDTO = Mapper::modelToListTipoProveedorDTO($tiposModels);

            $tiposMap = [];
            foreach ($tiposDTO as $tipoDTO) {
                $tiposMap[$tipoDTO->id_tipo_proveedor] = $tipoDTO->descripcion_tipo_proveedor;
            }

            $estadosModels = $this->estadosRepository->findAll();
            if (empty($estadosModels)) {
                throw new Exception("No se encontraron estados.");
            }
            $estadosDTO = Mapper::modelToListEstadosDTO($estadosModels);

            $estadosMap = [];
            foreach ($estadosDTO as $estadoDTO) {
                $estadosMap[$estadoDTO->id_estado] = $estadoDTO->descripcion_estado;
            }

            $administradoresModels = $this->administradoresRepository->findAll();
            $administradoresDTO = Mapper::modelToListAdministradoresDTO($administradoresModels);

            $administradoresMap = [];
            foreach ($administradoresDTO as $adminDTO) {
                $administradoresMap[$adminDTO->id_administrador] = $adminDTO->nombre_administrador . ' ' . $adminDTO->apellidos_administrador;
            }

            foreach ($proveedoresDTOs as $proveedorDTO) {
                if ($proveedorDTO->id_tipo_proveedor !== null && isset($tiposMap[$proveedorDTO->id_tipo_proveedor])) {
                    $proveedorDTO->nombre_tipo = $tiposMap[$proveedorDTO->id_tipo_proveedor];
                }

                if ($proveedorDTO->id_estado_proveedor !== null && isset($estadosMap[$proveedorDTO->id_estado_proveedor])) {
                    $proveedorDTO->nombre_estado = $estadosMap[$proveedorDTO->id_estado_proveedor];
                }

                if ($proveedorDTO->id_administrador_proveedor !== null && isset($administradoresMap[$proveedorDTO->id_administrador_proveedor])) {
                    $proveedorDTO->nombre_administrador = $administradoresMap[$proveedorDTO->id_administrador_proveedor];
                } else {
                    $proveedorDTO->nombre_administrador = 'Sin asignar';
                }
            }

            return $proveedoresDTOs;
        } catch (Throwable $e) {
            throw $e;
        }
    }
}

