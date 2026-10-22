<?php
require_once __DIR__ . '/../../../../../vendor/autoload.php';

use App\Domain\Enum\Commodity;
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Editar Part Number</title>
    <link rel="stylesheet" href="../../../../../public/css/Administrador/editarPartNumber.css">
</head>

<body>
    <div id="content-editar-partnumber">
        <div class="px-3 py-2" style="width: 760px; max-width: 100%;">
            <div class="border-0 pb-3 mb-3 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="m-0 fw-semibold text-dark">
                    <i class="fa-solid fa-pencil me-2 text-dark"></i>Editar Part Number
                </h5>
                <button data-fancybox-close class="btn-close" type="button" aria-label="Close"></button>
            </div>

            <form id="formEditarPartNumber" enctype="multipart/form-data">
                <input type="hidden" id="edit_id_partnumber" name="id_partnumber">
                <input type="hidden" id="edit_id_proveedor" name="id_proveedor_partnumber">
                <input type="hidden" id="edit_imagen_actual" name="imagen_partnumber_actual">

                <div class="row g-3">
                    <!-- Foto del Part Number -->
                    <div class="col-12 text-center mb-2">
                        <label class="form-label d-block text-secondary fw-medium mb-2">Imagen PartNumber:</label>
                        <div class="d-inline-block position-relative">
                            <label for="inputImagenPartNumber" class="partnumber-preview-box d-flex align-items-center justify-content-center mx-auto" id="imagenPreviewContainer" title="Haga clic para seleccionar una imagen">
                                <i class="fa-solid fa-camera fa-2x text-muted" id="defaultPartNumberIcon"></i>
                                <img id="partNumberPreview" src="" alt="Vista previa del Part Number" class="w-100 h-100 object-fit-cover d-none">
                            </label>
                            <input type="file" id="inputImagenPartNumber" name="imagen_partnumber" accept="image/png, image/jpeg, image/webp" class="d-none">
                        </div>
                    </div>

                    <!-- Part Number (tipo texto) -->
                    <div class="col-12">
                        <label for="inputPartNumber" class="form-label">Part Number: <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="inputPartNumber" name="partnumber" placeholder="Ingrese el Part Number" maxlength="20">
                    </div>

                    <!-- Descripción -->
                    <div class="col-12">
                        <label for="textareaDescripcion" class="form-label">Descripción: <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="textareaDescripcion" name="descripcion_partnumber" rows="2" maxlength="300" placeholder="Ingrese la descripción"></textarea>
                        <div class="form-text mt-1 text-muted" id="contadorDescripcion">0 / 300 caracteres</div>
                    </div>

                    <!-- Commodity (select) -->
                    <div class="col-md-6">
                        <label for="selectCommodity" class="form-label">Commodity: <span class="text-danger">*</span></label>
                        <select class="form-select" id="selectCommodity" name="id_commodity_partnumber">
                            <option value="" disabled selected>Seleccione un commodity</option>
                            <?php foreach (Commodity::cases() as $commodity): ?>
                                <option value="<?= $commodity->value ?>"><?= htmlspecialchars($commodity->label()) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- % BOM -->
                    <div class="col-md-6">
                        <label for="inputPorcentajeBom" class="form-label">% BOM: <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="inputPorcentajeBom" name="porcentaje_peso_bom_partnumber" placeholder="0.000">
                            <span class="input-group-text">%</span>
                        </div>
                    </div>
                </div>

                <div class="border-0 pt-4 mt-3 text-end border-top">
                    <button type="button" data-fancybox-close class="btn btn-secondary me-3">Cancelar</button>
                    <button type="submit" class="btn btn-custom-teal" id="btnEditarPartNumber">
                        <i class="fa-solid fa-save me-1"></i> Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="../../../../../public/js/Administrador/editarPartNumber.js"></script>
</body>

</html>