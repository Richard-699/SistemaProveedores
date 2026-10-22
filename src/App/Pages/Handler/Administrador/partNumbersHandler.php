<?php
require_once __DIR__ . '/../../../../../vendor/autoload.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
ob_start();

use App\Aplication\Service\PartNumbersService;
use App\Aplication\Service\ProveedoresService;
use App\Domain\DTO\PartNumbersDTO;
use App\Shared\Validation\Validator;

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
            case 'onGetPartNumbers':
                onGetPartNumbers();
                break;
            case 'obtenerPartNumberPorId':
                obtenerPartNumberPorId($_GET);
                break;
            case 'obtenerProveedores':
                obtenerProveedores();
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
            case 'crearPartNumber':
                crearPartNumber($formData);
                break;
            case 'editarPartNumber':
                editarPartNumber($formData);
                break;
            case 'eliminarPartNumber':
                eliminarPartNumber($_POST);
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

function onGetPartNumbers()
{
    try {
        $partNumbersService = new PartNumbersService();
        $partNumbers = $partNumbersService->obtenerPartNumbers();
        echo json_encode(['success' => true, 'data' => $partNumbers]);
        exit;
    } catch (\Throwable $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        exit;
    }
}

function obtenerPartNumberPorId(array $data)
{
    try {
        $id = $data['id_partnumber'] ?? '';
        if (empty($id)) {
            throw new Exception("ID del Part Number requerido.");
        }

        $partNumbersService = new PartNumbersService();
        $partNumber = $partNumbersService->obtenerPartNumberPorId((int)$id);

        echo json_encode(['success' => true, 'data' => $partNumber]);
        exit;
    } catch (\Throwable $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        exit;
    }
}


function obtenerProveedores()
{
    try {
        $proveedoresService = new ProveedoresService();
        $proveedores = $proveedoresService->obtenerProveedores();
        echo json_encode(['success' => true, 'data' => $proveedores]);
        exit;
    } catch (\Throwable $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        exit;
    }
}

function crearPartNumber(array $formData)
{
    $_SESSION['imagenes_creadas'] = [];
    try {
        $rawProveedor = trim((string)($formData['id_proveedor_partnumber'] ?? ''));
        $idProveedor  = $rawProveedor !== '' ? $rawProveedor : null;

        $partnumbers      = $formData['partnumber'] ?? [];
        $descripciones    = $formData['descripcion_partnumber'] ?? [];
        $commodities      = $formData['id_commodity_partnumber'] ?? [];
        $boms             = $formData['porcentaje_peso_bom_partnumber'] ?? [];
        $archivosImagenes = $formData['files']['imagen_partnumber'] ?? null;

        $carpetaDestino = __DIR__ . '/../../../../../public/img/partnumbers/';
        if (!is_dir($carpetaDestino)) {
            mkdir($carpetaDestino, 0777, true);
        }

        $partNumbersDTOs = [];

        foreach ($partnumbers as $i => $pn) {
            $archivo = null;

            if (isset($archivosImagenes['name']) && is_array($archivosImagenes['name'])) {
                $archivo = [
                    'name'     => $archivosImagenes['name'][$i] ?? '',
                    'tmp_name' => $archivosImagenes['tmp_name'][$i] ?? '',
                    'error'    => $archivosImagenes['error'][$i] ?? UPLOAD_ERR_NO_FILE
                ];
            } elseif (isset($archivosImagenes['name']) && !is_array($archivosImagenes['name'])) {
                $archivo = $archivosImagenes;
            }

            $ext = null;
            $nombreArchivo = null;
            $rutaRelativa = null;

            if ($archivo && !empty($archivo['name'])) {
                $ext = strtolower(pathinfo(basename($archivo['name']), PATHINFO_EXTENSION));
                $nombreArchivo = "pn_" . time() . "_" . bin2hex(random_bytes(4)) . "_" . $i . "." . $ext;
                $rutaRelativa = 'public/img/partnumbers/' . $nombreArchivo;
            }

            $rawPn = trim((string)($pn ?? ''));
            $partNumberVal = $rawPn !== '' ? $rawPn : null;

            $rawDesc = trim((string)($descripciones[$i] ?? ''));
            $descripcionVal = $rawDesc !== '' ? $rawDesc : null;

            $rawCommodity = $commodities[$i] ?? null;
            $idCommodityVal = (!empty($rawCommodity) && is_numeric($rawCommodity)) ? (int)$rawCommodity : null;

            $rawBom = trim((string)($boms[$i] ?? ''));
            $porcentajeBom = ($rawBom !== '' && is_numeric($rawBom)) ? (float)$rawBom : null;

            $partNumberDTO = new PartNumbersDTO(
                id_partnumber: null,
                partnumber: $partNumberVal,
                descripcion_partnumber: $descripcionVal,
                imagen_partnumber: $rutaRelativa,
                porcentaje_peso_bom_partnumber: $porcentajeBom,
                id_commodity_partnumber: $idCommodityVal,
                id_proveedor_partnumber: $idProveedor,
                extImagenPartNumber: $ext
            );

            Validator::validateCrearPartNumber($partNumberDTO);

            if ($archivo && $nombreArchivo && isset($archivo['tmp_name']) && isset($archivo['error']) && $archivo['error'] === UPLOAD_ERR_OK) {
                $destinoCompleto = $carpetaDestino . $nombreArchivo;
                if (move_uploaded_file($archivo['tmp_name'], $destinoCompleto)) {
                    $_SESSION['imagenes_creadas'][] = $destinoCompleto;
                } else {
                    throw new Exception("Error al guardar la imagen del Part Number.");
                }
            }

            $partNumbersDTOs[] = $partNumberDTO;
        }

        $partNumbersService = new PartNumbersService();
        $partNumbersService->crearPartNumbers($partNumbersDTOs);

        // Limpiar registro de sesión
        unset($_SESSION['imagenes_creadas']);

        echo json_encode(['success' => true]);
        exit;
    } catch (\Throwable $e) {
        // En caso de error, eliminar las imágenes
        if (!empty($_SESSION['imagenes_creadas'])) {
            foreach ($_SESSION['imagenes_creadas'] as $rutaCompleta) {
                if (is_file($rutaCompleta)) {
                    @unlink($rutaCompleta);
                }
            }
            unset($_SESSION['imagenes_creadas']);
        }

        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        exit;
    }
}

function editarPartNumber(array $formData)
{
    $rutaFisicaImagenNew = null;
    try {
        $rawIdPartNumber = $formData['id_partnumber'] ?? null;
        $idPartNumber = (!empty($rawIdPartNumber) && is_numeric($rawIdPartNumber)) ? (int)$rawIdPartNumber : null;

        $rawProveedor = trim((string)($formData['id_proveedor_partnumber'] ?? ''));
        $idProveedor  = $rawProveedor !== '' ? $rawProveedor : null;

        $rawImagenOld = trim((string)($formData['imagen_partnumber_actual'] ?? ''));
        $imagenPartNumberOld = $rawImagenOld !== '' ? $rawImagenOld : null;

        $archivoImagen = $formData['files']['imagen_partnumber'] ?? null;
        $cambioImagen = $archivoImagen && isset($archivoImagen['error']) && $archivoImagen['error'] === UPLOAD_ERR_OK;

        $carpetaDestino = __DIR__ . '/../../../../../public/img/partnumbers/';
        $ext = null;
        $nombreArchivoNew = null;
        $imagenPartNumberNew = null;

        if ($cambioImagen) {
            $ext = strtolower(pathinfo(basename($archivoImagen['name']), PATHINFO_EXTENSION));
            $nombreArchivoNew = $idPartNumber . "_pn_" . time() . "." . $ext;
            $imagenPartNumberNew = 'public/img/partnumbers/' . $nombreArchivoNew;
        }

        $eliminarImagenOld = $cambioImagen && $imagenPartNumberOld !== null;

        $rawPn = trim((string)($formData['partnumber'] ?? ''));
        $partnumber = $rawPn !== '' ? $rawPn : null;

        $rawDesc = trim((string)($formData['descripcion_partnumber'] ?? ''));
        $descripcion = $rawDesc !== '' ? $rawDesc : null;

        $rawCommodity = $formData['id_commodity_partnumber'] ?? null;
        $idCommodity = (!empty($rawCommodity) && is_numeric($rawCommodity)) ? (int)$rawCommodity : null;

        $rawBom = trim((string)($formData['porcentaje_peso_bom_partnumber'] ?? ''));
        $porcentajeBom = ($rawBom !== '' && is_numeric($rawBom)) ? (float)$rawBom : null;

        $partNumberDTO = new PartNumbersDTO(
            id_partnumber: $idPartNumber,
            partnumber: $partnumber,
            descripcion_partnumber: $descripcion,
            imagen_partnumber: $cambioImagen ? $imagenPartNumberNew : $imagenPartNumberOld,
            porcentaje_peso_bom_partnumber: $porcentajeBom,
            id_commodity_partnumber: $idCommodity,
            id_proveedor_partnumber: $idProveedor,
            extImagenPartNumber: $ext
        );

        Validator::validateEditarPartNumber($partNumberDTO);

        if ($cambioImagen) {
            if (!is_dir($carpetaDestino)) {
                mkdir($carpetaDestino, 0777, true);
            }

            $destinoCompleto = $carpetaDestino . $nombreArchivoNew;
            if (!move_uploaded_file($archivoImagen['tmp_name'], $destinoCompleto)) {
                throw new Exception("Error al guardar la imagen del Part Number.");
            }
            $rutaFisicaImagenNew = $destinoCompleto;
        }

        $partNumbersService = new PartNumbersService();
        $partNumbersService->editarPartNumbers($partNumberDTO);

        if ($eliminarImagenOld) {
            $rutaFisicaImagenOld = __DIR__ . '/../../../../../' . $imagenPartNumberOld;
            if (is_file($rutaFisicaImagenOld)) {
                @unlink($rutaFisicaImagenOld);
            }
        }

        echo json_encode(['success' => true]);
        exit;
    } catch (\Throwable $e) {
        if ($rutaFisicaImagenNew && is_file($rutaFisicaImagenNew)) {
            @unlink($rutaFisicaImagenNew);
        }

        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        exit;
    }
}

function eliminarPartNumber(array $data)
{
    try {
        if (empty($data['id_partnumber'])) {
            throw new Exception("ID del Part Number requerido.");
        }

        $idPartNumber = (int)$data['id_partnumber'];

        $partNumbersService = new PartNumbersService();
        $partNumber = $partNumbersService->obtenerPartNumberPorId($idPartNumber);

        $partNumbersService->eliminarPartNumbers($idPartNumber);

        if ($partNumber && !empty($partNumber->imagen_partnumber)) {
            $rutaFisica = __DIR__ . '/../../../../../' . ltrim($partNumber->imagen_partnumber, '/\\');
            if (is_file($rutaFisica)) {
                @unlink($rutaFisica);
            }
        }

        echo json_encode(['success' => true]);
        exit;
    } catch (\Throwable $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        exit;
    }
}
