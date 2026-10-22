<?php
require_once __DIR__ . '/../../../../../vendor/autoload.php';

use App\Domain\Enum\Commodity;
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Agregar Part Number</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="../../../../../public/css/Administrador/agregarPartNumber.css">
</head>

<body>
    <div id="content-agregar-partnumber">
        <div class="px-3 py-2" style="width: 760px; max-width: 100%;">
            <div class="border-0 pb-3 mb-3 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="m-0 fw-semibold text-dark">
                    <i class="fa-solid fa-plus me-2 text-dark"></i>Agregar Part Number
                </h5>
                <button data-fancybox-close class="btn-close" type="button" aria-label="Close"></button>
            </div>

            <form id="formAgregarPartNumber" enctype="multipart/form-data">
                <div class="row g-3 mb-3">
                    <div class="col-12">
                        <label for="selectProveedorPartNumber" class="form-label">Proveedor: <span class="text-danger">*</span></label>
                        <select class="form-select" id="selectProveedorPartNumber" name="id_proveedor_partnumber">
                            <option value="">Seleccione un proveedor</option>
                        </select>
                    </div>
                </div>

                <div class="col-12 d-none" id="seccionPartNumbers">
                    <div class="card-aviso-proveedor p-3 rounded-3 d-flex align-items-center mb-3">
                        <i class="fa-solid fa-circle-info me-2 fs-5 aviso-icono"></i>
                        <div class="aviso-texto">
                            Todos los Part Numbers registrados en esta sección serán asignados al proveedor: <strong id="nombreProveedorAsignado" class="fw-bold"></strong>
                        </div>
                    </div>

                    <div id="contenedorPartNumbers">
                        <div class="item-partnumber mb-3" data-index="1">
                            <div class="p-3 bg-light rounded-3 border border-light-subtle">
                                <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                                    <h6 class="fw-semibold text-secondary m-0 item-titulo-seccion d-flex align-items-center">
                                        <i class="material-icons me-2 fs-5 text-secondary">precision_manufacturing</i> Información del Part Number #1
                                    </h6>
                                </div>
                                <div class="row g-3">
                                    <div class="col-12 text-center mb-2">
                                        <label class="form-label d-block text-secondary fw-medium mb-2">Imagen PartNumber:</label>
                                        <div class="d-inline-block position-relative">
                                            <label for="inputImagenPartNumber_1" class="partnumber-preview-box d-flex align-items-center justify-content-center mx-auto imagen-preview-label" title="Haga clic para seleccionar una imagen">
                                                <i class="fa-solid fa-camera fa-2x text-muted default-icon"></i>
                                                <img src="" alt="Vista previa del Part Number" class="w-100 h-100 object-fit-cover d-none img-preview">
                                            </label>
                                            <input type="file" id="inputImagenPartNumber_1" name="imagen_partnumber[]" accept="image/png, image/jpeg, image/webp" class="d-none input-imagen-partnumber">
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label">Part Number: <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control input-partnumber" name="partnumber[]" placeholder="Ingrese el Part Number" maxlength="20">
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label">Descripción: <span class="text-danger">*</span></label>
                                        <textarea class="form-control textarea-descripcion" name="descripcion_partnumber[]" rows="2" maxlength="300" placeholder="Ingrese la descripción"></textarea>
                                        <div class="form-text mt-1 text-muted contador-descripcion">0 / 300 caracteres</div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Commodity: <span class="text-danger">*</span></label>
                                        <select class="form-select select-commodity" name="id_commodity_partnumber[]">
                                            <option value="" disabled selected>Seleccione un commodity</option>
                                            <?php foreach (Commodity::cases() as $commodity): ?>
                                                <option value="<?= $commodity->value ?>"><?= htmlspecialchars($commodity->label()) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">% BOM: <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="text" class="form-control input-porcentaje-bom" name="porcentaje_peso_bom_partnumber[]" placeholder="0.000">
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-end mt-3 mb-4">
                        <button type="button" class="btn btn-sm" id="btnAgregarOtroPartNumber" disabled>
                            <i class="fa-solid fa-plus me-1"></i> Agregar otro Part Number
                        </button>
                    </div>
                </div>

                <div class="border-0 pt-4 mt-4 text-end border-top">
                    <button type="button" data-fancybox-close class="btn btn-secondary me-3">Cancelar</button>
                    <button type="submit" class="btn btn-custom-teal d-none" id="btnGuardarPartNumbers">
                        <i class="fa-solid fa-save me-1"></i> Guardar Part Numbers
                    </button>
                </div>
            </form>
        </div>
    </div>
    <template id="templateItemPartNumber">
        <div class="item-partnumber mb-3" data-index="__INDEX__">
            <div class="p-3 bg-light rounded-3 border border-light-subtle">
                <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                    <h6 class="fw-semibold text-secondary m-0 item-titulo-seccion d-flex align-items-center">
                        <i class="material-icons me-2 fs-5 text-secondary">precision_manufacturing</i> Información del Part Number #__INDEX__
                    </h6>
                    <button type="button" class="btn btn-danger btn-sm btn-eliminar-item">
                        <i class="fa-solid fa-trash me-1"></i> Quitar
                    </button>
                </div>
                <div class="row g-3">
                    <div class="col-12 text-center mb-2">
                        <label class="form-label d-block text-secondary fw-medium mb-2">Imagen PartNumber:</label>
                        <div class="d-inline-block position-relative">
                            <label for="inputImagenPartNumber___INDEX__" class="partnumber-preview-box d-flex align-items-center justify-content-center mx-auto imagen-preview-label" title="Haga clic para seleccionar una imagen">
                                <i class="fa-solid fa-camera fa-2x text-muted default-icon"></i>
                                <img src="" alt="Vista previa del Part Number" class="w-100 h-100 object-fit-cover d-none img-preview">
                            </label>
                            <input type="file" id="inputImagenPartNumber___INDEX__" name="imagen_partnumber[]" accept="image/png, image/jpeg, image/webp" class="d-none input-imagen-partnumber">
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Part Number: <span class="text-danger">*</span></label>
                        <input type="text" class="form-control input-partnumber" name="partnumber[]" placeholder="Ingrese el Part Number" maxlength="20">
                    </div>

                    <div class="col-12">
                        <label class="form-label">Descripción: <span class="text-danger">*</span></label>
                        <textarea class="form-control textarea-descripcion" name="descripcion_partnumber[]" rows="2" maxlength="300" placeholder="Ingrese la descripción"></textarea>
                        <div class="form-text mt-1 text-muted contador-descripcion">0 / 300 caracteres</div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Commodity: <span class="text-danger">*</span></label>
                        <select class="form-select select-commodity" name="id_commodity_partnumber[]">
                            <option value="" disabled selected>Seleccione un commodity</option>
                            <?php foreach (Commodity::cases() as $commodity): ?>
                                <option value="<?= $commodity->value ?>"><?= htmlspecialchars($commodity->label()) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">% BOM: <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="text" class="form-control input-porcentaje-bom" name="porcentaje_peso_bom_partnumber[]" placeholder="0.000">
                            <span class="input-group-text">%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <script src="../../../../../public/js/Administrador/agregarPartNumber.js"></script>
</body>

</html>