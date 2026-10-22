<?php

namespace App\Aplication\Service;

use App\Aplication\Interface\Service\IPartNumbersService;
use App\Infrastructure\Repository\PartNumbersRepository;
use App\Infrastructure\Repository\ProveedoresRepository;
use App\Infrastructure\Database\Connection;
use App\Domain\DTO\PartNumbersDTO;
use App\Shared\Mapper\Mapper;
use PDO;
use Exception;
use Throwable;

class PartNumbersService implements IPartNumbersService
{
    private PDO $db;
    private PartNumbersRepository $partNumbersRepository;
    private ProveedoresRepository $proveedoresRepository;

    public function __construct()
    {
        $this->db = (new Connection())->dbsistemas_proveedores;
        $this->partNumbersRepository = new PartNumbersRepository($this->db);
        $this->proveedoresRepository = new ProveedoresRepository($this->db);
    }

    public function obtenerPartNumbers(): array
    {
        try {
            $partNumbersModels = $this->partNumbersRepository->findAll();
            if (empty($partNumbersModels)) {
                throw new Exception("No se encontraron part numbers.");
            }

            $partNumbersDTOs = Mapper::modelToListPartNumbersDTO($partNumbersModels);

            $proveedoresModels = $this->proveedoresRepository->findAll();
            $proveedoresDTOs = Mapper::modelToListProveedoresDTO($proveedoresModels);
            $proveedoresMap = [];
            foreach ($proveedoresDTOs as $provDTO) {
                $proveedoresMap[$provDTO->id_proveedor] = $provDTO->nombre_proveedor;
            }

            foreach ($partNumbersDTOs as $partNumberDTO) {
                if ($partNumberDTO->id_proveedor_partnumber !== null && isset($proveedoresMap[$partNumberDTO->id_proveedor_partnumber])) {
                    $partNumberDTO->nombre_proveedor = $proveedoresMap[$partNumberDTO->id_proveedor_partnumber];
                } else {
                    $partNumberDTO->nombre_proveedor = 'Sin asignar';
                }
            }

            return $partNumbersDTOs;
        } catch (Throwable $e) {
            throw $e;
        }
    }

    public function obtenerPartNumberPorId(int $id): PartNumbersDTO
    {
        try {
            $partNumberModel = $this->partNumbersRepository->findById($id);
            if (!$partNumberModel) {
                throw new Exception("Part Number no encontrado.");
            }
            return Mapper::modelToPartNumbersDTO($partNumberModel);
        } catch (Throwable $e) {
            throw $e;
        }
    }

    public function partNumberExistente(string $partnumber, ?int $idActual = null): bool
    {
        try {
            $partNumberModel = $this->partNumbersRepository->findByPartNumber($partnumber);
            if (!$partNumberModel) {
                return false;
            }

            if ($idActual !== null && (int)$partNumberModel->id_partnumber === (int)$idActual) {
                return false;
            }

            return true;
        } catch (Throwable $e) {
            throw $e;
        }
    }

    public function crearPartNumbers(array $partNumbersDTOs): bool
    {
        try {
            $this->db->beginTransaction();

            foreach ($partNumbersDTOs as $partNumberDTO) {
                if ($this->partNumberExistente($partNumberDTO->partnumber)) {
                    throw new Exception("El Part Number '{$partNumberDTO->partnumber}' ya se encuentra registrado.");
                }

                $partNumberModel = Mapper::partNumbersDTOToModel($partNumberDTO);
                $resultado = $this->partNumbersRepository->save($partNumberModel);
                if (!$resultado) {
                    throw new Exception("Error al intentar registrar el Part Number.");
                }
            }

            $this->db->commit();
            return true;
        } catch (Throwable $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            throw $e;
        }
    }

    public function editarPartNumbers(PartNumbersDTO $partNumberDTO): bool
    {
        try {

            if ($this->partNumberExistente($partNumberDTO->partnumber, $partNumberDTO->id_partnumber)) {
                throw new Exception("El Part Number '{$partNumberDTO->partnumber}' ya se encuentra registrado.");
            }

            $partNumberModel = Mapper::partNumbersDTOToModel($partNumberDTO);
            $resultado = $this->partNumbersRepository->updateById($partNumberModel);
            if (!$resultado) {
                throw new Exception("Error al intentar actualizar el Part Number.");
            }
            return true;
        } catch (Throwable $e) {
            throw $e;
        }
    }

    public function eliminarPartNumbers(int $id): bool
    {
        try {
            $resultado = $this->partNumbersRepository->deleteById($id);
            if (!$resultado) {
                throw new Exception("Error al intentar eliminar el Part Number.");
            }
            return true;
        } catch (Throwable $e) {
            throw $e;
        }
    }
}
