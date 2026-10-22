<?php

namespace App\Aplication\Service;

use App\Aplication\Interface\Service\IProveedoresService;
use App\Infrastructure\Repository\ProveedoresRepository;
use App\Infrastructure\Repository\AdministradoresRepository;
use App\Infrastructure\Repository\ProveedorCommodityRepository;
use App\Infrastructure\Repository\VinculacionRepository;
use App\Infrastructure\Repository\LaftRepository;
use App\Infrastructure\Repository\GestionAmbientalRepository;
use App\Infrastructure\Repository\CostbreakdownRepository;
use App\Infrastructure\Repository\CorreosRepository;
use App\Infrastructure\Database\Connection;
use App\Domain\DTO\ProveedoresDTO;
use App\Shared\Mapper\Mapper;
use App\Shared\Util\Utils;
use Exception;
use Throwable;

class ProveedoresService implements IProveedoresService
{

    private \PDO $db;
    private ProveedoresRepository $proveedoresRepository;
    private AdministradoresRepository $administradoresRepository;
    private ProveedorCommodityRepository $proveedorCommodityRepository;
    private VinculacionRepository $vinculacionRepository;
    private LaftRepository $laftRepository;
    private GestionAmbientalRepository $gestionAmbientalRepository;
    private CostbreakdownRepository $costbreakdownRepository;
    private CorreosRepository $correosRepository;

    public function __construct()
    {
        $this->db = (new Connection())->dbsistemas_proveedores;
        $this->proveedoresRepository = new ProveedoresRepository($this->db);
        $this->administradoresRepository = new AdministradoresRepository($this->db);
        $this->proveedorCommodityRepository = new ProveedorCommodityRepository($this->db);
        $this->vinculacionRepository = new VinculacionRepository($this->db);
        $this->laftRepository = new LaftRepository($this->db);
        $this->gestionAmbientalRepository = new GestionAmbientalRepository($this->db);
        $this->costbreakdownRepository = new CostbreakdownRepository($this->db);
        $this->correosRepository = new CorreosRepository($this->db);
    }

    public function obtenerProveedores(): array
    {
        try {
            $proveedoresModels = $this->proveedoresRepository->findAll();
            if (empty($proveedoresModels)) {
                throw new Exception("No se encontraron proveedores.");
            }
            return Mapper::modelToListProveedoresDTO($proveedoresModels);
        } catch (Throwable $e) {
            throw $e;
        }
    }

    public function obtenerDatosProveedores(): array
    {
        try {
            $proveedoresDTOs = $this->obtenerProveedores();

            $administradoresModels = $this->administradoresRepository->findAll();
            $administradoresDTO = Mapper::modelToListAdministradoresDTO($administradoresModels);

            $administradoresMap = [];
            foreach ($administradoresDTO as $adminDTO) {
                $administradoresMap[$adminDTO->id_administrador] = $adminDTO->nombre_administrador . ' ' . $adminDTO->apellidos_administrador;
            }

            $vinculacionesModels = $this->vinculacionRepository->findAll();
            $vinculacionesDTO = Mapper::modelToListVinculacionDTO($vinculacionesModels);
            $vinculacionesMap = [];
            foreach ($vinculacionesDTO as $v) {
                $vinculacionesMap[$v->id_proveedor_vinculacion_proveedor] = $v;
            }

            $laftModels = $this->laftRepository->findAll();
            $laftDTO = Mapper::modelToListLaftDTO($laftModels);
            $laftMap = [];
            foreach ($laftDTO as $l) {
                $laftMap[$l->id_proveedor_laft] = $l->oficial_cumplimiento__laft;
            }

            $provCommoditiesModels = $this->proveedorCommodityRepository->findAll();
            $provCommoditiesDTO = Mapper::modelToListProveedorCommodityDTO($provCommoditiesModels);
            $proveedorCommodityMap = [];
            foreach ($provCommoditiesDTO as $pc) {
                if (!isset($proveedorCommodityMap[$pc->id_proveedor])) {
                    $proveedorCommodityMap[$pc->id_proveedor] = $pc;
                }
            }

            $gaModels = $this->gestionAmbientalRepository->findAll();
            $gaDTO = Mapper::modelToListGestionAmbientalDTO($gaModels);
            $gestionAmbientalMap = [];
            foreach ($gaDTO as $ga) {
                $gestionAmbientalMap[$ga->id_proveedor_gestion_ambiental] = true;
            }

            $cbdModels = $this->costbreakdownRepository->findAll();
            $cbdDTO = Mapper::modelToListCostbreakdownDTO($cbdModels);
            $costbreakdownMap = [];
            foreach ($cbdDTO as $cbd) {
                $costbreakdownMap[$cbd->id_proveedor_costbreakdow] = true;
            }

            foreach ($proveedoresDTOs as $proveedorDTO) {
                $idProv = $proveedorDTO->id_proveedor;

                if ($proveedorDTO->id_administrador_proveedor !== null && isset($administradoresMap[$proveedorDTO->id_administrador_proveedor])) {
                    $proveedorDTO->nombre_administrador = $administradoresMap[$proveedorDTO->id_administrador_proveedor];
                } else {
                    $proveedorDTO->nombre_administrador = 'Sin asignar';
                }

                if (isset($vinculacionesMap[$idProv])) {
                    $vinc = $vinculacionesMap[$idProv];
                    $proveedorDTO->id_estado_cumplimiento = $vinc->id_estado_cumplimiento_vinculacion;
                    $proveedorDTO->nombre_estado_cumplimiento = $vinc->nombre_estado_cumplimiento;

                    $proveedorDTO->id_estado_negociacion = $vinc->id_estado_negociacion_vinculacion;
                    $proveedorDTO->nombre_estado_negociacion = $vinc->nombre_estado_negociacion;

                    $proveedorDTO->id_estado_ambiental = $vinc->id_estado_ambiental_vinculacion;
                    $proveedorDTO->nombre_estado_ambiental = $vinc->nombre_estado_ambiental;
                }

                if (isset($proveedorCommodityMap[$idProv])) {
                    $pc = $proveedorCommodityMap[$idProv];
                    $proveedorDTO->id_commodity = (int)$pc->id_commodity;
                    $proveedorDTO->nombre_commodity = $pc->descripcion_commodity;
                }

                if (isset($laftMap[$idProv])) {
                    $proveedorDTO->oficial_cumplimiento__laft = $laftMap[$idProv];
                }
                $proveedorDTO->tiene_laft = isset($laftMap[$idProv]) ? 1 : 0;

                $proveedorDTO->tiene_gestion_ambiental = isset($gestionAmbientalMap[$idProv]) ? 1 : 0;
                $proveedorDTO->tiene_costbreakdown = isset($costbreakdownMap[$idProv]) ? 1 : 0;
            }

            return $proveedoresDTOs;
        } catch (Throwable $e) {
            throw $e;
        }
    }

    public function obtenerNegociadoresPorTipo(int $idTipoNegociador): array
    {
        try {
            $administradoresModels = $this->administradoresRepository->findByTipoNegociador($idTipoNegociador);
            return Mapper::modelToListAdministradoresDTO($administradoresModels);
        } catch (Throwable $e) {
            throw $e;
        }
    }

    public function crearProveedor(ProveedoresDTO $proveedorDTO): bool
    {
        try {
            $this->db->beginTransaction();

            $proveedorModel = Mapper::proveedoresDTOToModel($proveedorDTO);
            $guardarProveedor = $this->proveedoresRepository->save($proveedorModel);

            if (!$guardarProveedor) {
                throw new Exception("Error al guardar el proveedor.");
            }

            if (!empty($proveedorDTO->commodities)) {
                foreach ($proveedorDTO->commodities as $commodityDTO) {
                    $proveedorCommodity = Mapper::proveedorCommodityDTOToModel($commodityDTO);
                    $guardarCommodity = $this->proveedorCommodityRepository->save($proveedorCommodity);
                    if (!$guardarCommodity) {
                        throw new Exception("Error al asociar el commodity al proveedor.");
                    }
                }
            }

            foreach ($proveedorDTO->correos as $correoDTO) {
                if (empty($correoDTO->id_proveedor_correo)) {
                    $correoDTO->id_proveedor_correo = $proveedorDTO->id_proveedor;
                }
                $correoModel = Mapper::correosDTOToModel($correoDTO);
                $guardarCorreo = $this->correosRepository->save($correoModel);
                if (!$guardarCorreo) {
                    throw new Exception("Error al asociar el correo al proveedor.");
                }
            }

            $destinatarios = array_map(fn($c) => is_string($c) ? $c : $c->correo, $proveedorDTO->correos);
            $asunto = "Bienvenido al Sistema de Proveedores - Haceb Whirlpool Industrial";
            $titulo = "Registro Exitoso en el Sistema de Proveedores";
            $contenidoHtml = "Hola <strong>" . htmlspecialchars($proveedorDTO->nombre_proveedor) . "</strong>,<br><br>"
                . "Te damos la bienvenida al <strong>Sistema de Proveedores de Haceb Whirlpool Industrial S.A.S.</strong><br><br>"
                . "Se ha creado tu cuenta de acceso con las siguientes credenciales:<br><br>"
                . "<strong>Usuario:</strong> <span style='font-family: monospace; font-size: 15px; background: #eef2f5; padding: 4px 8px; border-radius: 4px;'>" . htmlspecialchars($proveedorDTO->usuario_proveedor ?? '') . "</span><br><br>"
                . "<strong>Contraseña temporal:</strong> <span style='font-family: monospace; font-size: 15px; background: #eef2f5; padding: 4px 8px; border-radius: 4px;'>" . htmlspecialchars($proveedorDTO->tempPassword ?? '') . "</span><br><br>"
                . "<em>Por motivos de seguridad, el sistema te solicitará cambiar esta contraseña en tu primer inicio de sesión.</em><br>";

            $enviado = Utils::enviarCorreo($destinatarios, $asunto, $titulo, $contenidoHtml);
            if (!$enviado) {
                throw new Exception("Error al enviar el correo de notificación al proveedor.");
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

    public function obtenerProveedorPorId(string $idProveedor): ProveedoresDTO
    {
        try {
            $proveedor = $this->proveedoresRepository->findById($idProveedor);
            if (!$proveedor) {
                throw new Exception("Proveedor no encontrado.");
            }

            $proveedorDTO = Mapper::modelToProveedoresDTO($proveedor);

            $commodities = $this->proveedorCommodityRepository->findByIdProveedor($idProveedor);
            $proveedorDTO->commodities = Mapper::modelToListProveedorCommodityDTO($commodities);

            $correos = $this->correosRepository->findByProveedorId($idProveedor);
            $proveedorDTO->correos = Mapper::listModelToCorreosDTO($correos);

            return $proveedorDTO;
        } catch (Throwable $e) {
            throw $e;
        }
    }

    public function actualizarProveedor(ProveedoresDTO $proveedorDTO): bool
    {
        try {
            $this->db->beginTransaction();

            $proveedorExistente = $this->proveedoresRepository->findById($proveedorDTO->id_proveedor);
            if (!$proveedorExistente) {
                throw new Exception("Proveedor no encontrado.");
            }

            $proveedorModel = Mapper::proveedoresDTOToModel($proveedorDTO);
            $actualizarProveedor = $this->proveedoresRepository->update($proveedorModel);

            if (!$actualizarProveedor) {
                throw new Exception("Error al actualizar los datos del proveedor.");
            }

            $eliminarCommodities = $this->proveedorCommodityRepository->delete($proveedorDTO->id_proveedor);
            if (!$eliminarCommodities) {
                throw new Exception("Error al actualizar los commodities del proveedor.");
            }

            if (!empty($proveedorDTO->commodities)) {
                foreach ($proveedorDTO->commodities as $commodityDTO) {
                    $proveedorCommodity = Mapper::proveedorCommodityDTOToModel($commodityDTO);
                    $guardarCommodity = $this->proveedorCommodityRepository->save($proveedorCommodity);
                    if (!$guardarCommodity) {
                        throw new Exception("Error al asociar el commodity al proveedor.");
                    }
                }
            }

            $eliminarCorreos = $this->correosRepository->deleteByProveedorId($proveedorDTO->id_proveedor);
            if (!$eliminarCorreos) {
                throw new Exception("Error al actualizar los correos del proveedor.");
            }

            foreach ($proveedorDTO->correos as $correoDTO) {
                if (empty($correoDTO->id_proveedor_correo)) {
                    $correoDTO->id_proveedor_correo = $proveedorDTO->id_proveedor;
                }
                $correoModel = Mapper::correosDTOToModel($correoDTO);
                $guardarCorreo = $this->correosRepository->save($correoModel);
                if (!$guardarCorreo) {
                    throw new Exception("Error al asociar el correo al proveedor.");
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

    public function cambiarEstadoProveedor(string $idProveedor, int $idEstado): bool
    {
        try {
            $proveedorExistente = $this->proveedoresRepository->findById($idProveedor);
            if (!$proveedorExistente) {
                throw new Exception("Proveedor no encontrado.");
            }

            $estadoActualizado = $this->proveedoresRepository->updateEstado($idProveedor, $idEstado);
            if (!$estadoActualizado) {
                throw new Exception("Error al actualizar el estado del proveedor.");
            }
            return true;
        } catch (Throwable $e) {
            throw $e;
        }
    }

    public function verificarSimilitudNombre(string $nombre): ?string
    {
        try {
            $proveedorExistente = $this->proveedoresRepository->findByNombreSimilar($nombre);
            return $proveedorExistente ? $proveedorExistente->nombre_proveedor : null;
        } catch (Throwable $e) {
            throw $e;
        }
    }

    public function buscarPorNumeroAcreedor(string $numeroAcreedor): bool
    {
        try {
            $existeNumeroAcreedor = $this->proveedoresRepository->findByNumeroAcreedor($numeroAcreedor);
            if ($existeNumeroAcreedor) {
                throw new Exception("En este momento tienes este proveedor registrado con el número de acreedor \"{$numeroAcreedor}\".");
            }
            return true;
        } catch (Throwable $e) {
            throw $e;
        }
    }

    public function exportarProveedoresAExcel(array $ids): void
    {
        try {
            $proveedores = $this->obtenerDatosProveedores();

            $proveedoresPorId = [];
            foreach ($proveedores as $provDTO) {
                $proveedoresPorId[$provDTO->id_proveedor] = $provDTO;
            }

            $proveedoresFiltrados = [];
            foreach ($ids as $id) {
                if (!isset($proveedoresPorId[$id])) {
                    throw new Exception("Uno o más proveedores seleccionados no fueron encontrados.");
                }
                $proveedoresFiltrados[] = $proveedoresPorId[$id];
            }

            $datosExportar = Mapper::proveedoresDTOListToExcelData($proveedoresFiltrados);
            Utils::generarExcelProveedores($datosExportar);
        } catch (Throwable $e) {
            throw $e;
        }
    }
}

