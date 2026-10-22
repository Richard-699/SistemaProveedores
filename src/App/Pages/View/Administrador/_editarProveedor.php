<?php
require_once __DIR__ . '/../../../../../vendor/autoload.php';

use App\Domain\Enum\Idioma;
use App\Domain\Enum\TipoProveedor;
use App\Domain\Enum\Commodity;
use App\Domain\Enum\Categoria;
use App\Domain\Enum\SubCategoria;
use App\Domain\Enum\CategoriaServicioSuministro;
use App\Domain\Enum\SubCategoriaServicioSuministro;
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Editar Proveedor</title>
    <link rel="stylesheet" href="../../../../../public/css/Administrador/editarProveedor.css">
</head>

<body>
    <div id="content-editar-proveedor">
        <div class="px-3 py-2" style="width: 760px; max-width: 100%;">
            <div class="border-0 pb-3 mb-3 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="m-0 fw-semibold text-dark">
                    <i class="fa-solid fa-pencil me-2 text-dark"></i>Editar Proveedor
                </h5>
                <button data-fancybox-close class="btn-close" type="button" aria-label="Close"></button>
            </div>

            <form id="formEditarProveedor" enctype="multipart/form-data">
                <input type="hidden" name="action" value="editarProveedor">
                <input type="hidden" id="edit_id_proveedor" name="id_proveedor">

                <div class="row g-3">
                    <div class="col-12" id="seccionGeneral">
                        <div class="p-3 bg-light rounded-3 border border-light-subtle">
                            <h6 class="fw-semibold text-secondary mb-3 border-bottom pb-2">
                                <i class="fa-solid fa-circle-info me-1"></i> Información General
                            </h6>
                            <div class="row g-3">
                                <div class="col-12 text-center mb-4">
                                    <label class="form-label d-block text-secondary fw-medium mb-2">Logo del proveedor</label>
                                    <div class="avatar-upload-wrapper d-inline-block position-relative mb-2">
                                        <div class="avatar-preview rounded-circle shadow-sm d-flex align-items-center justify-content-center overflow-hidden mx-auto" id="logoPreviewContainer">
                                            <i class="fa-solid fa-building fa-3x text-muted" id="defaultLogoIcon"></i>
                                            <img id="logoPreview" src="" alt="Vista previa del logo" class="w-100 h-100 object-fit-cover d-none">
                                        </div>
                                    </div>
                                    <div>
                                        <label for="inputLogoProveedor" class="btn btn-sm upload-btn-dashed px-3 py-2 cursor-pointer m-0" id="btnSubirLogo">
                                            <i class="fa-solid fa-camera me-1"></i> Cambiar logo
                                        </label>
                                        <input type="file" id="inputLogoProveedor" name="logo_proveedor" accept="image/png, image/jpeg, image/webp" class="d-none">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="inputNumeroAcreedor" class="form-label">Número de Acreedor:</label>
                                    <input type="text" class="form-control" id="inputNumeroAcreedor" name="numero_acreedor_proveedor" placeholder="">
                                </div>

                                <div class="col-md-6">
                                    <label for="inputNombreProveedor" class="form-label">Razón Social: <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="inputNombreProveedor" name="nombre_proveedor" placeholder="">
                                </div>

                                <div class="col-md-6">
                                    <label for="selectIdioma" class="form-label">Formato de Idioma: <span class="text-danger">*</span></label>
                                    <select class="form-select" id="selectIdioma" name="id_idioma_proveedor">
                                        <option value="" disabled selected>Seleccione un idioma</option>
                                        <?php foreach (Idioma::cases() as $idioma): ?>
                                            <option value="<?= $idioma->value ?>"><?= htmlspecialchars($idioma->label()) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="selectTipoProveedor" class="form-label">Tipo de Proveedor: <span class="text-danger">*</span></label>
                                    <select class="form-select" id="selectTipoProveedor" name="id_tipo_proveedor">
                                        <option value="" disabled selected>Seleccione el tipo</option>
                                        <?php foreach (TipoProveedor::cases() as $tipo): ?>
                                            <option value="<?= $tipo->value ?>"><?= $tipo->label() ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-12">
                                    <label for="inputCorreosProveedor" class="form-label d-flex align-items-center">
                                        <span>Correos Electrónicos: <span class="text-danger">*</span></span>
                                        <span class="info-circle-tooltip ms-2 cursor-pointer" title="Puede ingresar uno o varios correos separados por coma (,).">
                                            <i class="fa-solid fa-circle-info text-info"></i>
                                        </span>
                                    </label>
                                    <input type="text" class="form-control" id="inputCorreosProveedor" name="correos_proveedor" placeholder="correo@ejemplo.com, correo@ejemplo.com">
                                    <div class="form-text mt-1 text-muted" id="contadorCorreos">0 correos válidos</div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label d-block">¿Llenar Formulario Ambiental? <span class="text-danger">*</span></label>
                                    <div class="form-check form-switch form-switch-custom mt-2">
                                        <input class="form-check-input" type="checkbox" role="switch" id="switchFormularioAmbiental" name="formulario_ambiental_proveedor" value="1">
                                        <label class="form-check-label fw-medium ms-1" for="switchFormularioAmbiental" id="labelFormularioAmbiental">No</label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label d-block">¿Autoriza Carta para Beneficiarios Finales? <span class="text-danger">*</span></label>
                                    <div class="form-check form-switch form-switch-custom mt-2">
                                        <input class="form-check-input" type="checkbox" role="switch" id="switchCartaBeneficiarios" name="permitir_carta_beneficiarios_finales_proveedor" value="1">
                                        <label class="form-check-label fw-medium ms-1" for="switchCartaBeneficiarios" id="labelCartaBeneficiarios">No</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sección Información para Proveedor Directo -->
                    <div class="col-12" id="seccionDirecto" style="display: none;">
                        <div class="p-3 bg-light rounded-3 border border-light-subtle">
                            <h6 class="fw-semibold text-secondary mb-3 border-bottom pb-2">
                                <i class="fa-solid fa-industry me-1"></i> Información para Proveedor Directo
                            </h6>
                            <div class="row g-3">
                                <div class="col-12" id="grupoAdministradorProveedorDirecto">
                                    <label for="selectAdministradorProveedorDirecto" class="form-label">Negociador Encargado: <span class="text-danger">*</span></label>
                                    <select class="form-select" id="selectAdministradorProveedorDirecto" disabled>
                                        <option value="" disabled selected>Seleccione un negociador</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="inputPorcentajeBom" class="form-label">% Peso de la BOM: <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="inputPorcentajeBom" name="porcentaje_bom_proveedor" placeholder="">
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label d-block">¿Maneja Formato Cost Breakdown? <span class="text-danger">*</span></label>
                                    <div class="form-check form-switch form-switch-custom mt-2">
                                        <input class="form-check-input" type="checkbox" role="switch" id="switchCostBreakdown" name="maneja_formato_costbreakdown_proveedor" value="1">
                                        <label class="form-check-label fw-medium ms-1" for="switchCostBreakdown" id="labelCostBreakdown">No</label>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label for="selectCommodity" class="form-label">Commodities: <span class="text-danger">*</span></label>
                                    <select class="form-select" id="selectCommodity" name="id_commodity[]" multiple="multiple" style="width: 100%;">
                                        <?php foreach (Commodity::cases() as $commodity): ?>
                                            <option value="<?= $commodity->value ?>"><?= $commodity->label() ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-12">
                                    <label for="textareaHistoria" class="form-label">Historia: <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="textareaHistoria" name="historia_proveedor" rows="2" maxlength="2000" placeholder="Ingrese la historia del proveedor"></textarea>
                                    <div class="form-text mt-1 text-muted" id="contadorHistoria">0 / 2000 caracteres</div>
                                </div>

                                <div class="col-12">
                                    <label for="textareaDescripcion" class="form-label">Descripción: <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="textareaDescripcion" name="descripcion_proveedor" rows="2" maxlength="5000" placeholder="Ingrese la descripción del proveedor"></textarea>
                                    <div class="form-text mt-1 text-muted" id="contadorDescripcion">0 / 5000 caracteres</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12" id="seccionIndirecto" style="display: none;">
                        <div class="p-3 bg-light rounded-3 border border-light-subtle">
                            <h6 class="fw-semibold text-secondary mb-3 border-bottom pb-2">
                                <i class="fa-solid fa-handshake me-1"></i> Información para Proveedor Indirecto
                            </h6>
                            <div class="row g-3">
                                <div class="col-12" id="grupoAdministradorProveedorIndirecto">
                                    <label for="selectAdministradorProveedorIndirecto" class="form-label">Negociador Encargado: <span class="text-danger">*</span></label>
                                    <select class="form-select" id="selectAdministradorProveedorIndirecto" disabled>
                                        <option value="" disabled selected>Seleccione un negociador</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="selectCategoria" class="form-label">Categoría: <span class="text-danger">*</span></label>
                                    <select class="form-select" id="selectCategoria" name="id_categoria_proveedor">
                                        <option value="" disabled selected>Seleccione una categoría</option>
                                        <?php foreach (Categoria::cases() as $categoria): ?>
                                            <option value="<?= $categoria->value ?>"><?= htmlspecialchars($categoria->label()) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="selectSubCategoria" class="form-label">Sub-categoría: <span class="text-danger">*</span></label>
                                    <select class="form-select" id="selectSubCategoria" name="id_sub_categoria_proveedor" disabled>
                                        <option value="" disabled selected>Seleccione una categoría primero</option>
                                        <?php foreach (SubCategoria::cases() as $subcategoria): ?>
                                            <option value="<?= $subcategoria->value ?>" data-categoria="<?= $subcategoria->categoriaId() ?>"><?= htmlspecialchars($subcategoria->label()) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12" id="seccionServiciosSuministros" style="display: none;">
                        <div class="p-3 bg-light rounded-3 border border-light-subtle">
                            <h6 class="fw-semibold text-secondary mb-3 border-bottom pb-2">
                                <i class="fa-solid fa-boxes-stacked me-1"></i> Servicios y Suministros
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="selectCategoriaServicioSuministro" class="form-label">Categoría Servicios y Suministros:</label>
                                    <select class="form-select" id="selectCategoriaServicioSuministro" name="id_categoria_servicio_suministro">
                                        <option value="" disabled selected>Seleccione una categoría de servicios suministros</option>
                                        <?php foreach (CategoriaServicioSuministro::cases() as $catServ): ?>
                                            <option value="<?= $catServ->value ?>"><?= htmlspecialchars($catServ->label()) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="selectSubCategoriaServicioSuministro" class="form-label">Sub-categoría Servicios y Suministros:</label>
                                    <select class="form-select" id="selectSubCategoriaServicioSuministro" name="id_sub_categoria_servicio_suministro" disabled>
                                        <option value="" disabled selected>Seleccione una categoría primero</option>
                                        <?php foreach (SubCategoriaServicioSuministro::cases() as $subServ): ?>
                                            <option value="<?= $subServ->value ?>" data-categoria="<?= $subServ->categoriaId() ?>"><?= htmlspecialchars($subServ->label()) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="border-0 pt-4 mt-3 text-end border-top">
                    <button type="button" data-fancybox-close class="btn btn-secondary me-2">Cancelar</button>
                    <button type="submit" class="btn btn-custom-teal" id="btnEditarProveedor">
                        <i class="fa-solid fa-save me-1"></i> Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script src="../../../../../public/js/Administrador/editarProveedor.js"></script>
</body>

</html>