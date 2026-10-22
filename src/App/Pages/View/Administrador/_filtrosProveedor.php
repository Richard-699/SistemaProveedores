<?php
require_once __DIR__ . '/../../../../../vendor/autoload.php';

use App\Domain\Enum\Categoria;
use App\Domain\Enum\SubCategoria;
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Filtros Proveedor</title>
    <link rel="stylesheet" href="../../../../../public/css/Administrador/filtrosProveedor.css">
</head>

<body>
    <div id="content-filtros-proveedor">
        <div class="filter-drawer-backdrop" id="filterDrawerBackdrop"></div>

        <aside class="filter-drawer" id="filterDrawer" aria-label="Panel de Filtros">
            <div class="filter-drawer-header">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-sliders text-teal fs-5"></i>
                    <h5 class="m-0 fw-semibold text-dark">Filtros</h5>
                </div>
                <button type="button" class="filter-drawer-close" id="btnCerrarFiltros" title="Cerrar filtros (Esc)">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="filter-drawer-body">

                <div class="filter-accordion" data-filter-group="tipo">
                    <button type="button" class="filter-accordion-header">
                        <span class="filter-accordion-title">Tipo de Proveedor</span>
                        <i class="fa-solid fa-chevron-down filter-chevron"></i>
                    </button>
                    <div class="filter-accordion-content">
                        <div class="filter-options-list" id="filterListTipo">
                            <label class="filter-option-item">
                                <input type="checkbox" class="filter-checkbox" data-group="id_tipo_proveedor" value="1">
                                <span class="custom-check-box"></span>
                                <span class="filter-option-label">Directo</span>
                            </label>
                            <label class="filter-option-item">
                                <input type="checkbox" class="filter-checkbox" data-group="id_tipo_proveedor" value="2">
                                <span class="custom-check-box"></span>
                                <span class="filter-option-label">Indirecto</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="filter-accordion" data-filter-group="acreedor">
                    <button type="button" class="filter-accordion-header">
                        <span class="filter-accordion-title">Número de Acreedor</span>
                        <i class="fa-solid fa-chevron-down filter-chevron"></i>
                    </button>
                    <div class="filter-accordion-content">
                        <div class="filter-options-list">
                            <label class="filter-option-item">
                                <input type="checkbox" class="filter-checkbox" data-group="acreedor" value="asignado">
                                <span class="custom-check-box"></span>
                                <span class="filter-option-label">Con acreedor asignado</span>
                            </label>
                            <label class="filter-option-item">
                                <input type="checkbox" class="filter-checkbox" data-group="acreedor" value="sin_asignar">
                                <span class="custom-check-box"></span>
                                <span class="filter-option-label">Sin asignar</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="filter-accordion" data-filter-group="estado">
                    <button type="button" class="filter-accordion-header">
                        <span class="filter-accordion-title">Estado del Proveedor</span>
                        <i class="fa-solid fa-chevron-down filter-chevron"></i>
                    </button>
                    <div class="filter-accordion-content">
                        <div class="filter-options-list">
                            <label class="filter-option-item">
                                <input type="checkbox" class="filter-checkbox" data-group="id_estado_proveedor" value="4">
                                <span class="custom-check-box"></span>
                                <span class="filter-option-label">Activo</span>
                            </label>
                            <label class="filter-option-item">
                                <input type="checkbox" class="filter-checkbox" data-group="id_estado_proveedor" value="5">
                                <span class="custom-check-box"></span>
                                <span class="filter-option-label">Inactivo</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="filter-accordion" data-filter-group="cumplimiento">
                    <button type="button" class="filter-accordion-header">
                        <span class="filter-accordion-title">Cumplimiento (LAFT)</span>
                        <i class="fa-solid fa-chevron-down filter-chevron"></i>
                    </button>
                    <div class="filter-accordion-content">
                        <div class="filter-options-subgroup-title">¿Diligenció LAFT?</div>
                        <div class="filter-options-list mb-2">
                            <label class="filter-option-item">
                                <input type="checkbox" class="filter-checkbox" data-group="tiene_laft" value="1" id="chkLaftDiligenciado">
                                <span class="custom-check-box"></span>
                                <span class="filter-option-label">Diligenciado</span>
                            </label>
                            <label class="filter-option-item">
                                <input type="checkbox" class="filter-checkbox" data-group="tiene_laft" value="0">
                                <span class="custom-check-box"></span>
                                <span class="filter-option-label">No diligenciado</span>
                            </label>
                        </div>
                        <div id="subgrupoEstadosLaft" class="d-none">
                            <div class="filter-options-subgroup-title">Estado de cumplimiento:</div>
                            <div class="filter-options-list">
                                <label class="filter-option-item">
                                    <input type="checkbox" class="filter-checkbox" data-group="id_estado_cumplimiento" value="2" id="chkLaftAprobado">
                                    <span class="custom-check-box"></span>
                                    <span class="filter-option-label">Aprobado</span>
                                </label>
                                <label class="filter-option-item">
                                    <input type="checkbox" class="filter-checkbox" data-group="id_estado_cumplimiento" value="3">
                                    <span class="custom-check-box"></span>
                                    <span class="filter-option-label">Pendiente</span>
                                </label>
                                <label class="filter-option-item">
                                    <input type="checkbox" class="filter-checkbox" data-group="id_estado_cumplimiento" value="1">
                                    <span class="custom-check-box"></span>
                                    <span class="filter-option-label">Rechazado</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="filter-accordion d-none" id="accordionNegociacion" data-filter-group="negociacion">
                    <button type="button" class="filter-accordion-header">
                        <span class="filter-accordion-title">Negociación</span>
                        <i class="fa-solid fa-chevron-down filter-chevron"></i>
                    </button>
                    <div class="filter-accordion-content">
                        <div class="filter-options-list">
                            <label class="filter-option-item">
                                <input type="checkbox" class="filter-checkbox" data-group="id_estado_negociacion" value="2">
                                <span class="custom-check-box"></span>
                                <span class="filter-option-label">Aprobado</span>
                            </label>
                            <label class="filter-option-item">
                                <input type="checkbox" class="filter-checkbox" data-group="id_estado_negociacion" value="3">
                                <span class="custom-check-box"></span>
                                <span class="filter-option-label">Pendiente</span>
                            </label>
                            <label class="filter-option-item">
                                <input type="checkbox" class="filter-checkbox" data-group="id_estado_negociacion" value="1">
                                <span class="custom-check-box"></span>
                                <span class="filter-option-label">Rechazado</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="filter-accordion d-none" id="accordionAmbiental" data-filter-group="ambiental">
                    <button type="button" class="filter-accordion-header">
                        <span class="filter-accordion-title">Ambiental</span>
                        <i class="fa-solid fa-chevron-down filter-chevron"></i>
                    </button>
                    <div class="filter-accordion-content">
                        <!-- Nivel 1: ¿Debe diligenciarlo? -->
                        <div class="filter-options-subgroup-title">¿Diligencia formulario ambiental?</div>
                        <div class="filter-options-list mb-2">
                            <label class="filter-option-item">
                                <input type="checkbox" class="filter-checkbox" data-group="formulario_ambiental_proveedor" value="1" id="chkAplicaAmbiental">
                                <span class="custom-check-box"></span>
                                <span class="filter-option-label">Sí diligencia ambiental</span>
                            </label>
                            <label class="filter-option-item">
                                <input type="checkbox" class="filter-checkbox" data-group="formulario_ambiental_proveedor" value="0">
                                <span class="custom-check-box"></span>
                                <span class="filter-option-label">No aplica ambiental</span>
                            </label>
                        </div>

                        <!-- Nivel 2: Desplegable si marca Sí diligencia ambiental -->
                        <div id="subgrupoEstadoAmbiental" class="d-none">
                            <div class="filter-options-subgroup-title">¿Diligenció el formulario?</div>
                            <div class="filter-options-list mb-2">
                                <label class="filter-option-item">
                                    <input type="checkbox" class="filter-checkbox" data-group="tiene_gestion_ambiental" value="1" id="chkGestionAmbientalDiligenciado">
                                    <span class="custom-check-box"></span>
                                    <span class="filter-option-label">Diligenciado</span>
                                </label>
                                <label class="filter-option-item">
                                    <input type="checkbox" class="filter-checkbox" data-group="tiene_gestion_ambiental" value="0">
                                    <span class="custom-check-box"></span>
                                    <span class="filter-option-label">Pendiente por llenar</span>
                                </label>
                            </div>

                            <!-- Nivel 3: Desplegable si marca Diligenciado -->
                            <div id="subgrupoAprobacionAmbiental" class="d-none">
                                <div class="filter-options-subgroup-title">Estado de aprobación:</div>
                                <div class="filter-options-list">
                                    <label class="filter-option-item">
                                        <input type="checkbox" class="filter-checkbox" data-group="id_estado_ambiental" value="2">
                                        <span class="custom-check-box"></span>
                                        <span class="filter-option-label">Aprobado</span>
                                    </label>
                                    <label class="filter-option-item">
                                        <input type="checkbox" class="filter-checkbox" data-group="id_estado_ambiental" value="3">
                                        <span class="custom-check-box"></span>
                                        <span class="filter-option-label">Pendiente</span>
                                    </label>
                                    <label class="filter-option-item">
                                        <input type="checkbox" class="filter-checkbox" data-group="id_estado_ambiental" value="1">
                                        <span class="custom-check-box"></span>
                                        <span class="filter-option-label">Rechazado</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="filter-accordion d-none" id="accordionCBD" data-filter-group="form_cbd">
                    <button type="button" class="filter-accordion-header">
                        <span class="filter-accordion-title">Cost Breakdown (CBD)</span>
                        <i class="fa-solid fa-chevron-down filter-chevron"></i>
                    </button>
                    <div class="filter-accordion-content">
                        <div class="filter-options-subgroup-title">¿Maneja formato CBD?</div>
                        <div class="filter-options-list mb-2">
                            <label class="filter-option-item">
                                <input type="checkbox" class="filter-checkbox" data-group="maneja_formato_costbreakdown_proveedor" value="1" id="chkAplicaCBD">
                                <span class="custom-check-box"></span>
                                <span class="filter-option-label">Sí maneja Cost Breakdown</span>
                            </label>
                            <label class="filter-option-item">
                                <input type="checkbox" class="filter-checkbox" data-group="maneja_formato_costbreakdown_proveedor" value="0">
                                <span class="custom-check-box"></span>
                                <span class="filter-option-label">No maneja Cost Breakdown</span>
                            </label>
                        </div>
                        <div id="subgrupoEstadoCBD" class="d-none">
                            <div class="filter-options-subgroup-title">Estado de diligenciamiento:</div>
                            <div class="filter-options-list">
                                <label class="filter-option-item">
                                    <input type="checkbox" class="filter-checkbox" data-group="tiene_costbreakdown" value="1">
                                    <span class="custom-check-box"></span>
                                    <span class="filter-option-label">Con registros diligenciados</span>
                                </label>
                                <label class="filter-option-item">
                                    <input type="checkbox" class="filter-checkbox" data-group="tiene_costbreakdown" value="0">
                                    <span class="custom-check-box"></span>
                                    <span class="filter-option-label">Sin registros / Pendiente</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="filter-accordion d-none" id="accordionCommodity" data-filter-group="commodity">
                    <button type="button" class="filter-accordion-header">
                        <span class="filter-accordion-title">Commodities</span>
                        <i class="fa-solid fa-chevron-down filter-chevron"></i>
                    </button>
                    <div class="filter-accordion-content">
                        <div class="filter-search-box mb-2">
                            <i class="fa-solid fa-magnifying-glass filter-search-icon"></i>
                            <input type="text" class="form-control form-control-sm filter-search-input" placeholder="Buscar commodity..." data-target-list="#filterListCommodity">
                        </div>
                        <div class="filter-options-list filter-options-scrollable" id="filterListCommodity">
                        </div>
                    </div>
                </div>

                <div class="filter-accordion d-none" id="accordionCategoria" data-filter-group="categoria">
                    <button type="button" class="filter-accordion-header">
                        <span class="filter-accordion-title">Categorías</span>
                        <i class="fa-solid fa-chevron-down filter-chevron"></i>
                    </button>
                    <div class="filter-accordion-content">
                        <div class="filter-search-box mb-2">
                            <i class="fa-solid fa-magnifying-glass filter-search-icon"></i>
                            <input type="text" class="form-control form-control-sm filter-search-input" placeholder="Buscar categoría..." data-target-list="#filterListCategoria">
                        </div>
                        <div class="filter-options-list filter-options-scrollable" id="filterListCategoria">
                            <?php foreach (Categoria::cases() as $cat): ?>
                                <label class="filter-option-item">
                                    <input type="checkbox" class="filter-checkbox" data-group="id_categoria_proveedor" value="<?= $cat->value ?>">
                                    <span class="custom-check-box"></span>
                                    <span class="filter-option-label"><?= htmlspecialchars($cat->label()) ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <div class="filter-accordion d-none" id="accordionSubcategoria" data-filter-group="subcategoria">
                    <button type="button" class="filter-accordion-header">
                        <span class="filter-accordion-title">Sub-categorías</span>
                        <i class="fa-solid fa-chevron-down filter-chevron"></i>
                    </button>
                    <div class="filter-accordion-content">
                        <div class="filter-search-box mb-2">
                            <i class="fa-solid fa-magnifying-glass filter-search-icon"></i>
                            <input type="text" class="form-control form-control-sm filter-search-input" placeholder="Buscar sub-categoría..." data-target-list="#filterListSubCategoria">
                        </div>
                        <div class="filter-options-list filter-options-scrollable" id="filterListSubCategoria">
                            <?php foreach (SubCategoria::cases() as $sub): ?>
                                <label class="filter-option-item d-none" data-categoria="<?= $sub->categoriaId() ?>">
                                    <input type="checkbox" class="filter-checkbox" data-group="id_sub_categoria_proveedor" value="<?= $sub->value ?>">
                                    <span class="custom-check-box"></span>
                                    <span class="filter-option-label"><?= htmlspecialchars($sub->label()) ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <div class="filter-accordion" data-filter-group="admin">
                    <button type="button" class="filter-accordion-header">
                        <span class="filter-accordion-title">Negociador Encargado</span>
                        <i class="fa-solid fa-chevron-down filter-chevron"></i>
                    </button>
                    <div class="filter-accordion-content">
                        <div class="filter-options-list filter-options-scrollable" id="filterListAdmin">
                        </div>
                    </div>
                </div>

            </div>

            <div class="filter-drawer-footer">
                <button type="button" class="btn btn-filter-clear" id="btnLimpiarFiltrosDrawer">
                    Limpiar filtros
                </button>
                <button type="button" class="btn btn-filter-apply" id="btnAplicarFiltrosDrawer">
                    Ver <span id="lblConteoResultadosDrawer">0</span> resultados
                </button>
            </div>
        </aside>
    </div>
</body>

</html>