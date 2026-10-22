<?php
require_once __DIR__ . '/../../../../../vendor/autoload.php';
session_start();
ob_start();

use App\Aplication\Service\ProveedoresService;
use App\Domain\DTO\ProveedoresDTO;
use App\Domain\DTO\ProveedorCommodityDTO;
use App\Domain\DTO\CorreosDTO;
use App\Shared\Validation\Validator;
use App\Shared\Util\Utils;

header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit();
}

$requestMethod = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? ($_POST['action'] ?? '');

switch ($requestMethod) {
    case 'GET':
        switch ($action) {
            case 'onGetProveedores':
                onGetProveedores();
                break;
            case 'obtenerProveedorPorId':
                obtenerProveedorPorId($_GET);
                break;
            case 'obtenerNegociadoresPorTipo':
                obtenerNegociadoresPorTipo($_GET);
                break;
            default:
                echo json_encode(['success' => false, 'message' => 'Acción no permitida']);
                exit;
        }
        break;

    case 'POST':
        $formData = $_POST;
        $formData['files'] = $_FILES;
        switch ($action) {
            case 'crearProveedor':
                crearProveedor($formData);
                break;
            case 'editarProveedor':
                editarProveedor($formData);
                break;
            case 'cambiarEstado':
                cambiarEstadoProveedor($_POST);
                break;
            case 'exportarExcel':
                exportarExcel($formData);
                break;
            default:
                echo json_encode(['success' => false, 'message' => 'Acción no permitida']);
                exit;
        }
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Método o acción no permitida']);
        exit;
}

function onGetProveedores()
{
    try {
        $proveedoresService = new ProveedoresService();
        $proveedores = $proveedoresService->obtenerDatosProveedores();
        echo json_encode(['success' => true, 'data' => $proveedores]);
        exit;
    } catch (\Throwable $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        exit;
    }
}

function obtenerProveedorPorId(array $data)
{
    try {
        $idProveedor = !empty(trim($data['id_proveedor'] ?? '')) ? trim($data['id_proveedor']) : null;
        Validator::validateIdProveedor($idProveedor);

        $proveedoresService = new ProveedoresService();
        $proveedorData = $proveedoresService->obtenerProveedorPorId($idProveedor);

        echo json_encode(['success' => true, 'data' => $proveedorData]);
        exit;
    } catch (\Throwable $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        exit;
    }
}

function obtenerNegociadoresPorTipo(array $data)
{
    try {
        $idTipoNegociador = (int)($data['id_tipo_negociador'] ?? 0);
        $proveedoresService = new ProveedoresService();
        $negociadores = $proveedoresService->obtenerNegociadoresPorTipo($idTipoNegociador);
        echo json_encode(['success' => true, 'data' => $negociadores]);
        exit;
    } catch (\Throwable $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        exit;
    }
}

function crearProveedor(array $formData)
{
    try {
        $proveedoresService = new ProveedoresService();

        $rawAcreedor = trim($formData['numero_acreedor_proveedor'] ?? '');
        $numeroAcreedor = ($rawAcreedor !== '' && $rawAcreedor !== '0' && strtolower($rawAcreedor) !== 'sin asignar')
            ? $rawAcreedor
            : null;

        if ($numeroAcreedor !== null) {
            $proveedoresService->buscarPorNumeroAcreedor($numeroAcreedor);
        }

        $confirmarSimilitud = !empty($formData['confirmar_similitud']);
        $nombreRaw = trim($formData['nombre_proveedor'] ?? '');
        $nombre = !empty($nombreRaw) ? mb_convert_case($nombreRaw, MB_CASE_TITLE, 'UTF-8') : null;

        if ($nombre !== null && !$confirmarSimilitud) {
            $nombreBase = trim(preg_replace('/\b(s\.?a\.?s?|s\.?a|ltda|limitada|cia|corp|inc)\b\.?/i', '', $nombre));
            $terminoBusqueda = !empty($nombreBase) ? $nombreBase : $nombre;

            $nombreSimilar = $proveedoresService->verificarSimilitudNombre($terminoBusqueda);
            if ($nombreSimilar !== null) {
                echo json_encode([
                    'success' => false,
                    'requiere_confirmacion' => true,
                    'nombre_existente' => $nombreSimilar,
                    'message' => "En este momento tienes este proveedor registrado: \"{$nombreSimilar}\"."
                ]);
                exit;
            }
        }

        $idProveedor = Utils::generarGuid();

        $archivoLogo = $formData['files']['logo_proveedor'] ?? ($formData['logo_proveedor'] ?? null);
        $rutaLogo = null;
        if ($archivoLogo && isset($archivoLogo['error']) && $archivoLogo['error'] === UPLOAD_ERR_OK) {
            $nombreOriginal = basename($archivoLogo['name']);
            $ext = strtolower(pathinfo($nombreOriginal, PATHINFO_EXTENSION));
            $permitidas = ['jpg', 'jpeg', 'png', 'webp'];

            if (!in_array($ext, $permitidas)) {
                throw new Exception("Formato de imagen no permitido. Utilice JPG, PNG o WEBP.");
            }

            $carpetaDestino = __DIR__ . '/../../../../../public/img/proveedores/logos/';
            if (!is_dir($carpetaDestino)) {
                mkdir($carpetaDestino, 0777, true);
            }

            $nombreArchivo = $idProveedor . "_logo." . $ext;
            $destinoCompleto = $carpetaDestino . $nombreArchivo;

            if (move_uploaded_file($archivoLogo['tmp_name'], $destinoCompleto)) {
                $rutaLogo = 'public/img/proveedores/logos/' . $nombreArchivo;
            } else {
                throw new Exception("Error al guardar la imagen del logo.");
            }
        }

        $idIdioma = !empty($formData['id_idioma_proveedor']) ? (int)$formData['id_idioma_proveedor'] : null;
        $idTipo = !empty($formData['id_tipo_proveedor']) ? (int)$formData['id_tipo_proveedor'] : null;
        $costBreakdown = !empty($formData['maneja_formato_costbreakdown_proveedor']) ? 1 : 0;
        $formularioAmbiental = !empty($formData['formulario_ambiental_proveedor']) ? 1 : 0;
        $idCategoria = ($idTipo === 2 && !empty($formData['id_categoria_proveedor'])) ? (int)$formData['id_categoria_proveedor'] : null;
        $idSubCategoria = ($idTipo === 2 && !empty($formData['id_sub_categoria_proveedor'])) ? (int)$formData['id_sub_categoria_proveedor'] : null;
        $historia = ($idTipo === 1 && !empty(trim($formData['historia_proveedor'] ?? ''))) ? trim($formData['historia_proveedor']) : null;
        $descripcion = ($idTipo === 1 && !empty(trim($formData['descripcion_proveedor'] ?? ''))) ? trim($formData['descripcion_proveedor']) : null;
        $idCommodity = ($idTipo === 1 && isset($formData['id_commodity']) && is_array($formData['id_commodity'])) ? $formData['id_commodity'] : [];

        $commodities = [];
        foreach ($idCommodity as $id) {
            $commodities[] = new ProveedorCommodityDTO(
                id_proveedor: $idProveedor,
                id_commodity: (int)$id
            );
        }

        $rawBom = trim((string)($formData['porcentaje_bom_proveedor'] ?? ''));
        $porcentajeBom = ($idTipo === 1)
            ? (($rawBom !== '' && is_numeric($rawBom)) ? (float)$rawBom : -1.0)
            : 0.0;

        $idEstado = 4; // 4 = Activo
        $idAdministrador = !empty(trim($formData['id_administrador_proveedor'] ?? ''))
            ? trim($formData['id_administrador_proveedor'])
            : ($_SESSION['id_usuario'] ?? null);

        $rawCorreos = trim($formData['correos_proveedor'] ?? '');
        $correosArray = array_values(array_filter(array_map('trim', explode(',', $rawCorreos))));
        $correosDTO = array_map(fn($c) => new CorreosDTO(
            id_proveedor_correo: $idProveedor,
            correo: $c
        ), $correosArray);

        $usuarioProveedor = strtolower(str_replace(' ', '', $nombreRaw));

        $tempPassword = Utils::generarContrasenaTemporal();
        $passwordHash = password_hash($tempPassword, PASSWORD_DEFAULT);

        $proveedorDTO = new ProveedoresDTO(
            id_proveedor: $idProveedor,
            numero_acreedor_proveedor: $numeroAcreedor,
            nombre_proveedor: $nombre,
            id_tipo_proveedor: $idTipo,
            id_idioma_proveedor: $idIdioma,
            id_estado_proveedor: $idEstado,
            maneja_formato_costbreakdown_proveedor: $costBreakdown,
            historia_proveedor: $historia,
            descripcion_proveedor: $descripcion,
            porcentaje_bom_proveedor: $porcentajeBom,
            logo_proveedor: $rutaLogo,
            id_categoria_proveedor: $idCategoria,
            id_sub_categoria_proveedor: $idSubCategoria,
            formulario_ambiental_proveedor: $formularioAmbiental,
            id_administrador_proveedor: $idAdministrador,
            usuario_proveedor: $usuarioProveedor,
            password_proveedor: $passwordHash,
            password_is_temporal_proveedor: 1,
            tempPassword: $tempPassword,
            commodities: $commodities,
            correos: $correosDTO
        );

        Validator::validateDTO($proveedorDTO);

        $proveedoresService->crearProveedor($proveedorDTO);

        echo json_encode(['success' => true, 'message' => 'Proveedor registrado exitosamente.']);
        exit;
    } catch (\Throwable $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        exit;
    }
}

function editarProveedor(array $formData)
{
    try {
        $idProveedor = !empty(trim($formData['id_proveedor'] ?? '')) ? trim($formData['id_proveedor']) : null;
        Validator::validateIdProveedor($idProveedor);

        $proveedoresService = new ProveedoresService();
        $proveedorExistente = $proveedoresService->obtenerProveedorPorId($idProveedor);

        $rawAcreedor = trim($formData['numero_acreedor_proveedor'] ?? '');
        $numeroAcreedor = ($rawAcreedor !== '' && $rawAcreedor !== '0' && strtolower($rawAcreedor) !== 'sin asignar')
            ? $rawAcreedor
            : null;

        if ($numeroAcreedor !== null && $numeroAcreedor !== $proveedorExistente->numero_acreedor_proveedor) {
            $proveedoresService->buscarPorNumeroAcreedor($numeroAcreedor);
        }

        $confirmarSimilitud = !empty($formData['confirmar_similitud']);
        $nombreRaw = trim($formData['nombre_proveedor'] ?? '');
        $nombre = !empty($nombreRaw) ? mb_convert_case($nombreRaw, MB_CASE_TITLE, 'UTF-8') : null;

        if ($nombre !== null && $nombre !== $proveedorExistente->nombre_proveedor && !$confirmarSimilitud) {
            $nombreBase = trim(preg_replace('/\b(s\.?a\.?s?|s\.?a|ltda|limitada|cia|corp|inc)\b\.?/i', '', $nombre));
            $terminoBusqueda = !empty($nombreBase) ? $nombreBase : $nombre;

            $nombreSimilar = $proveedoresService->verificarSimilitudNombre($terminoBusqueda);
            if ($nombreSimilar !== null && strcasecmp($nombreSimilar, $proveedorExistente->nombre_proveedor) !== 0) {
                echo json_encode([
                    'success' => false,
                    'requiere_confirmacion' => true,
                    'nombre_existente' => $nombreSimilar,
                    'message' => "En este momento tienes este proveedor registrado: \"{$nombreSimilar}\"."
                ]);
                exit;
            }
        }

        $archivoLogo = $formData['files']['logo_proveedor'] ?? ($formData['logo_proveedor'] ?? null);
        $rutaLogo = $proveedorExistente->logo_proveedor;
        if ($archivoLogo && isset($archivoLogo['error']) && $archivoLogo['error'] === UPLOAD_ERR_OK) {
            $nombreOriginal = basename($archivoLogo['name']);
            $ext = strtolower(pathinfo($nombreOriginal, PATHINFO_EXTENSION));
            $permitidas = ['jpg', 'jpeg', 'png', 'webp'];

            if (!in_array($ext, $permitidas)) {
                throw new Exception("Formato de imagen no permitido. Utilice JPG, PNG o WEBP.");
            }

            $carpetaDestino = __DIR__ . '/../../../../../public/img/proveedores/logos/';
            if (!is_dir($carpetaDestino)) {
                mkdir($carpetaDestino, 0777, true);
            }

            $nombreArchivo = $idProveedor . "_logo_" . time() . "." . $ext;
            $destinoCompleto = $carpetaDestino . $nombreArchivo;

            if (move_uploaded_file($archivoLogo['tmp_name'], $destinoCompleto)) {
                $rutaLogo = 'public/img/proveedores/logos/' . $nombreArchivo;
            } else {
                throw new Exception("Error al guardar la imagen del logo.");
            }
        }

        $idIdioma = !empty($formData['id_idioma_proveedor']) ? (int)$formData['id_idioma_proveedor'] : null;
        $idTipo = !empty($formData['id_tipo_proveedor']) ? (int)$formData['id_tipo_proveedor'] : null;
        $costBreakdown = !empty($formData['maneja_formato_costbreakdown_proveedor']) ? 1 : 0;
        $formularioAmbiental = !empty($formData['formulario_ambiental_proveedor']) ? 1 : 0;
        $permitirCartaBeneficiarios = !empty($formData['permitir_carta_beneficiarios_finales_proveedor']) ? 1 : 0;
        $idCategoria = ($idTipo === 2 && !empty($formData['id_categoria_proveedor'])) ? (int)$formData['id_categoria_proveedor'] : null;
        $idSubCategoria = ($idTipo === 2 && !empty($formData['id_sub_categoria_proveedor'])) ? (int)$formData['id_sub_categoria_proveedor'] : null;
        $historia = ($idTipo === 1 && !empty(trim($formData['historia_proveedor'] ?? ''))) ? trim($formData['historia_proveedor']) : null;
        $descripcion = ($idTipo === 1 && !empty(trim($formData['descripcion_proveedor'] ?? ''))) ? trim($formData['descripcion_proveedor']) : null;
        $idCommodity = ($idTipo === 1 && isset($formData['id_commodity']) && is_array($formData['id_commodity'])) ? $formData['id_commodity'] : [];

        $commodities = [];
        foreach ($idCommodity as $id) {
            $commodities[] = new ProveedorCommodityDTO(
                id_proveedor: $idProveedor,
                id_commodity: (int)$id
            );
        }

        $rawCorreos = trim($formData['correos_proveedor'] ?? '');
        $correosArray = array_values(array_filter(array_map('trim', explode(',', $rawCorreos))));
        $correosDTO = array_map(fn($c) => new CorreosDTO(
            id_proveedor_correo: $idProveedor,
            correo: $c
        ), $correosArray);

        $rawBom = trim((string)($formData['porcentaje_bom_proveedor'] ?? ''));
        $porcentajeBom = ($idTipo === 1)
            ? (($rawBom !== '' && is_numeric($rawBom)) ? (float)$rawBom : -1.0)
            : 0.0;

        $idEstado = 4; // 4 = Activo

        $proveedorDTO = new ProveedoresDTO(
            id_proveedor: $idProveedor,
            numero_acreedor_proveedor: $numeroAcreedor,
            nombre_proveedor: $nombre,
            id_tipo_proveedor: $idTipo,
            id_idioma_proveedor: $idIdioma,
            id_estado_proveedor: $idEstado,
            maneja_formato_costbreakdown_proveedor: $costBreakdown,
            historia_proveedor: $historia,
            descripcion_proveedor: $descripcion,
            porcentaje_bom_proveedor: $porcentajeBom,
            logo_proveedor: $rutaLogo,
            id_categoria_proveedor: $idCategoria,
            id_sub_categoria_proveedor: $idSubCategoria,
            formulario_ambiental_proveedor: $formularioAmbiental,
            permitir_carta_beneficiarios_finales_proveedor: $permitirCartaBeneficiarios,
            id_administrador_proveedor: !empty(trim($formData['id_administrador_proveedor'] ?? '')) 
                ? trim($formData['id_administrador_proveedor']) 
                : ($proveedorExistente->id_administrador_proveedor ?? ($_SESSION['id_usuario'] ?? null)),
            commodities: $commodities,
            correos: $correosDTO
        );

        Validator::validateEditarProveedor($proveedorDTO);

        $proveedoresService->actualizarProveedor($proveedorDTO);

        echo json_encode(['success' => true, 'message' => 'Proveedor actualizado exitosamente.']);
        exit;
    } catch (\Throwable $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        exit;
    }
}

function cambiarEstadoProveedor(array $formData)
{
    try {
        $idProveedor = !empty(trim($formData['id_proveedor'] ?? '')) ? trim($formData['id_proveedor']) : null;
        $idEstado = (int)($formData['id_estado'] ?? 0);

        Validator::validateCambiarEstadoProveedor($idProveedor, $idEstado);

        $proveedoresService = new ProveedoresService();
        $proveedoresService->cambiarEstadoProveedor($idProveedor, $idEstado);

        $mensaje = ($idEstado === 4) ? 'Proveedor activado exitosamente.' : 'Proveedor inactivado exitosamente.';
        echo json_encode(['success' => true, 'message' => $mensaje]);
        exit;
    } catch (\Throwable $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        exit;
    }
}

function exportarExcel(array $formData)
{
    try {
        $rawIds = $formData['ids'] ?? '';
        $ids = is_array($rawIds) ? $rawIds : json_decode($rawIds, true);

        Validator::validateIdsExportarExcel($ids);

        if (ob_get_length()) {
            ob_clean();
        }

        header_remove('Content-Type');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Proveedores_' . date('Ymd_His') . '.xlsx"');
        header('Cache-Control: max-age=0');
        header('Pragma: public');

        $proveedoresService = new ProveedoresService();
        $proveedoresService->exportarProveedoresAExcel($ids);
        exit;
    } catch (\Throwable $e) {
        if (ob_get_length()) {
            ob_clean();
        }
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        exit;
    }
}
