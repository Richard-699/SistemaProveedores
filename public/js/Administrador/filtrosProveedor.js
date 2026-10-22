let filtrosActivos = {
    id_tipo_proveedor: [],
    acreedor: [],
    id_estado_proveedor: [],
    tiene_laft: [],
    id_estado_cumplimiento: [],
    id_estado_negociacion: [],
    id_estado_ambiental: [],
    formulario_ambiental_proveedor: [],
    tiene_gestion_ambiental: [],
    maneja_formato_costbreakdown_proveedor: [],
    tiene_costbreakdown: [],
    id_commodity: [],
    id_categoria_proveedor: [],
    id_sub_categoria_proveedor: [],
    id_administrador_proveedor: []
};

let todosLosProveedores = [];
let filtrosCatalogosCargados = false;
let cacheSubcategoriasPorCategoria = {};

$.fn.dataTable.ext.search.push(function (settings, data, dataIndex, rowData) {
    if (settings.sTableId !== 'tabla-proveedores' || !rowData) {
        return true;
    }

    // 1. Tipo de proveedor
    if (filtrosActivos.id_tipo_proveedor.length > 0) {
        if (!filtrosActivos.id_tipo_proveedor.includes(String(rowData.id_tipo_proveedor))) {
            return false;
        }
    }

    // 2. Número de acreedor
    if (filtrosActivos.acreedor.length > 0) {
        const num = rowData.numero_acreedor_proveedor ? String(rowData.numero_acreedor_proveedor).trim() : '';
        const tieneAcreedor = (num !== '' && num !== '0' && num !== 'Sin asignar');

        let matchAcreedor = false;
        if (filtrosActivos.acreedor.includes('asignado') && tieneAcreedor) {
            matchAcreedor = true;
        }
        if (filtrosActivos.acreedor.includes('sin_asignar') && !tieneAcreedor) {
            matchAcreedor = true;
        }

        if (!matchAcreedor) {
            return false;
        }
    }

    // 3. Estado del proveedor (Activo: 4, Inactivo: 5)
    if (filtrosActivos.id_estado_proveedor.length > 0) {
        if (!filtrosActivos.id_estado_proveedor.includes(String(rowData.id_estado_proveedor))) {
            return false;
        }
    }

    // 4. Cumplimiento (LAFT): Diligenciado (1) / No diligenciado (0)
    if (filtrosActivos.tiene_laft.length > 0) {
        const tieneLaft = (rowData.tiene_laft !== null && rowData.tiene_laft !== undefined && Number(rowData.tiene_laft) === 1) ? '1' : '0';
        if (!filtrosActivos.tiene_laft.includes(tieneLaft)) {
            return false;
        }
    }

    // 4.1. Cumplimiento (LAFT): Estado (Aprobado: 2, Pendiente: 3, Rechazado: 1)
    if (filtrosActivos.id_estado_cumplimiento.length > 0) {
        const est = (rowData.id_estado_cumplimiento !== null && rowData.id_estado_cumplimiento !== undefined)
            ? String(rowData.id_estado_cumplimiento)
            : '0';
        if (!filtrosActivos.id_estado_cumplimiento.includes(est)) {
            return false;
        }
    }

    // 5. Negociación: Estado (Aprobado: 2, Pendiente: 3, Rechazado: 1)
    if (filtrosActivos.id_estado_negociacion.length > 0) {
        const est = (rowData.id_estado_negociacion !== null && rowData.id_estado_negociacion !== undefined)
            ? String(rowData.id_estado_negociacion)
            : '0';
        if (!filtrosActivos.id_estado_negociacion.includes(est)) {
            return false;
        }
    }

    // 6. Ambiental: Estado (Aprobado: 2, Pendiente: 3, Rechazado: 1)
    if (filtrosActivos.id_estado_ambiental.length > 0) {
        const est = (rowData.id_estado_ambiental !== null && rowData.id_estado_ambiental !== undefined)
            ? String(rowData.id_estado_ambiental)
            : '0';
        if (!filtrosActivos.id_estado_ambiental.includes(est)) {
            return false;
        }
    }

    // 7. Formulario Ambiental: Requiere (1 / 0)
    if (filtrosActivos.formulario_ambiental_proveedor.length > 0) {
        const req = String(rowData.formulario_ambiental_proveedor ?? 0);
        if (!filtrosActivos.formulario_ambiental_proveedor.includes(req)) {
            return false;
        }
    }

    // 7.1. Formulario Ambiental: Diligenciado (1 / 0)
    if (filtrosActivos.tiene_gestion_ambiental.length > 0) {
        const tiene = String(rowData.tiene_gestion_ambiental ?? 0);
        if (!filtrosActivos.tiene_gestion_ambiental.includes(tiene)) {
            return false;
        }
    }

    // 8. Cost Breakdown: Maneja (1 / 0)
    if (filtrosActivos.maneja_formato_costbreakdown_proveedor.length > 0) {
        const man = String(rowData.maneja_formato_costbreakdown_proveedor ?? 0);
        if (!filtrosActivos.maneja_formato_costbreakdown_proveedor.includes(man)) {
            return false;
        }
    }

    // 8.1. Cost Breakdown: Con registros (1 / 0)
    if (filtrosActivos.tiene_costbreakdown.length > 0) {
        const tiene = String(rowData.tiene_costbreakdown ?? 0);
        if (!filtrosActivos.tiene_costbreakdown.includes(tiene)) {
            return false;
        }
    }


    // 10. Commodity
    if (filtrosActivos.id_commodity.length > 0) {
        if (!rowData.id_commodity || !filtrosActivos.id_commodity.includes(String(rowData.id_commodity))) {
            return false;
        }
    }

    // 11. Categoría
    if (filtrosActivos.id_categoria_proveedor.length > 0) {
        if (!rowData.id_categoria_proveedor || !filtrosActivos.id_categoria_proveedor.includes(String(rowData.id_categoria_proveedor))) {
            return false;
        }
    }

    // 11.1. Sub-categoría
    if (filtrosActivos.id_sub_categoria_proveedor.length > 0) {
        if (!rowData.id_sub_categoria_proveedor || !filtrosActivos.id_sub_categoria_proveedor.includes(String(rowData.id_sub_categoria_proveedor))) {
            return false;
        }
    }

    // 12. Administrador
    if (filtrosActivos.id_administrador_proveedor.length > 0) {
        const adminId = rowData.id_administrador_proveedor ? String(rowData.id_administrador_proveedor) : 'sin_asignar';
        if (!filtrosActivos.id_administrador_proveedor.includes(adminId)) {
            return false;
        }
    }

    return true;
});

$(document).on('click', '#btnAbrirFiltros', function (e) {
    e.preventDefault();
    if (!filtrosCatalogosCargados) {
        poblarCatalogosDinamicos(todosLosProveedores);
    }
    abrirDrawerFiltros();
});

$(document).on('click', '#btnCerrarFiltros, #filterDrawerBackdrop, #btnAplicarFiltrosDrawer', function (e) {
    e.preventDefault();
    cerrarDrawerFiltros();
});

$(document).on('keydown', function (e) {
    if (e.key === 'Escape' && $('#filterDrawer').hasClass('show')) {
        cerrarDrawerFiltros();
    }
});

$(document).on('click', '.filter-accordion-header', function () {
    $(this).closest('.filter-accordion').toggleClass('active');
});

$(document).on('input', '.filter-search-input', function () {
    const busqueda = $(this).val().toLowerCase().trim();
    const targetList = $(this).data('target-list');
    $(targetList).find('.filter-option-item').each(function () {
        const texto = $(this).find('.filter-option-label').text().toLowerCase();
        $(this).toggleClass('d-none', texto.indexOf(busqueda) === -1);
    });
});

$(document).on('change', '.filter-checkbox', async function () {
    const binaryGroups = [
        'id_tipo_proveedor',
        'tiene_laft',
        'formulario_ambiental_proveedor',
        'tiene_gestion_ambiental',
        'maneja_formato_costbreakdown_proveedor',
        'tiene_costbreakdown',
        'acreedor'
    ];
    const group = $(this).data('group');
    if (this.checked && binaryGroups.includes(group)) {
        $(`.filter-checkbox[data-group="${group}"]`).not(this).prop('checked', false);
    }

    if (group === 'id_categoria_proveedor') {
        const catsChecked = $('.filter-checkbox[data-group="id_categoria_proveedor"]:checked').map(function () {
            return $(this).val();
        }).get();
        await actualizarSubcategoriasDinamicas(catsChecked);
    }

    actualizarEstadoFiltros();
});

$(document).on('click', '#btnLimpiarFiltrosDrawer, #btnLimpiarTodoChips', function () {
    limpiarTodosLosFiltros();
});

$(document).on('click', '.filter-chip', function () {
    const grupo = $(this).data('group');
    const valor = String($(this).data('val'));
    $(`.filter-checkbox[data-group="${grupo}"][value="${valor}"]`).prop('checked', false).trigger('change');
});


function ubicarBotonFiltros() {
    const $filterBox = $('#tabla-proveedores_filter');
    if ($filterBox.length && !$('#btnAbrirFiltros').length) {
        $filterBox.append(`
            <button type="button" class="btn btn-filter-pill" id="btnAbrirFiltros" title="Filtrar proveedores">
                <i class="fa-solid fa-sliders"></i>
                <span>Filtros</span>
                <span class="filter-count-badge d-none" id="badgeTotalFiltros">0</span>
            </button>
        `);
    }
}


function inicializarFiltrosProveedor(data) {
    ubicarBotonFiltros();
    actualizarVisibilidadDependenciasFiltros();
    actualizarDatosFiltros(data);
}


function actualizarDatosFiltros(data) {
    if (!data || !data.length) {
        return;
    }

    todosLosProveedores = data;

    if (!filtrosCatalogosCargados) {
        poblarCatalogosDinamicos(todosLosProveedores);
    }
}

function poblarCatalogosDinamicos(data) {
    const commoditiesMap = {};
    const adminsMap = {};

    if (data && Array.isArray(data)) {
        data.forEach(function (row) {
            if (row.id_commodity && row.nombre_commodity && !commoditiesMap[row.id_commodity]) {
                commoditiesMap[row.id_commodity] = row.nombre_commodity;
            }
            if (row.id_administrador_proveedor && row.nombre_administrador) {
                adminsMap[row.id_administrador_proveedor] = row.nombre_administrador;
            } else if (!row.id_administrador_proveedor) {
                adminsMap['sin_asignar'] = 'Sin asignar';
            }
        });
    }

    // Renderizar Commodities
    const $listCommodity = $('#filterListCommodity').empty();
    Object.keys(commoditiesMap).sort(function (a, b) {
        return commoditiesMap[a].localeCompare(commoditiesMap[b]);
    }).forEach(function (id) {
        $listCommodity.append(`
            <label class="filter-option-item">
                <input type="checkbox" class="filter-checkbox" data-group="id_commodity" value="${id}">
                <span class="custom-check-box"></span>
                <span class="filter-option-label">${commoditiesMap[id]}</span>
            </label>
        `);
    });

    // Renderizar Administradores
    const $listAdmin = $('#filterListAdmin').empty();
    Object.keys(adminsMap).sort(function (a, b) {
        if (a === 'sin_asignar') return 1;
        if (b === 'sin_asignar') return -1;
        return adminsMap[a].localeCompare(adminsMap[b]);
    }).forEach(function (id) {
        $listAdmin.append(`
            <label class="filter-option-item">
                <input type="checkbox" class="filter-checkbox" data-group="id_administrador_proveedor" value="${id}">
                <span class="custom-check-box"></span>
                <span class="filter-option-label">${adminsMap[id]}</span>
            </label>
        `);
    });

    if (data && data.length > 0) {
        filtrosCatalogosCargados = true;
    }
}


function actualizarVisibilidadDependenciasFiltros() {
    // 0. Tipo de proveedor: Directo (1) vs Indirecto (2)
    const esDirecto = $('.filter-checkbox[data-group="id_tipo_proveedor"][value="1"]').is(':checked');
    const esIndirecto = $('.filter-checkbox[data-group="id_tipo_proveedor"][value="2"]').is(':checked');

    // Cost Breakdown y Commodities: solo cuando Directos esté seleccionado
    if (esDirecto) {
        $('#accordionCBD').removeClass('d-none');
        $('#accordionCommodity').removeClass('d-none');
    } else {
        $('#accordionCBD').addClass('d-none').removeClass('active');
        $('#accordionCommodity').addClass('d-none').removeClass('active');
        $('#accordionCBD .filter-checkbox').prop('checked', false);
        $('#accordionCommodity .filter-checkbox').prop('checked', false);
        $('#subgrupoEstadoCBD').addClass('d-none');
        $('#subgrupoEstadoCBD .filter-checkbox').prop('checked', false);
        $('#filterListCommodity .filter-option-item').removeClass('d-none');
        $('#accordionCommodity .filter-search-input').val('');
    }

    // Categorías y Sub-categorías: solo cuando Indirectos esté seleccionado
    if (esIndirecto) {
        $('#accordionCategoria').removeClass('d-none');
        const catsChecked = $('.filter-checkbox[data-group="id_categoria_proveedor"]:checked').map(function () {
            return $(this).val();
        }).get();
        if (catsChecked.length === 0) {
            $('#accordionSubcategoria').addClass('d-none').removeClass('active');
            $('#accordionSubcategoria .filter-checkbox').prop('checked', false);
        } else {
            $('#accordionSubcategoria').removeClass('d-none');
        }
    } else {
        $('#accordionCategoria').addClass('d-none').removeClass('active');
        $('#accordionSubcategoria').addClass('d-none').removeClass('active');
        $('#accordionCategoria .filter-checkbox').prop('checked', false);
        $('#accordionSubcategoria .filter-checkbox').prop('checked', false);
        $('#filterListCategoria .filter-option-item').removeClass('d-none');
        $('#accordionCategoria .filter-search-input').val('');
        $('#filterListSubCategoria').empty();
        filtrosActivos.id_categoria_proveedor = [];
        filtrosActivos.id_sub_categoria_proveedor = [];
    }

    // 1. LAFT: Desplegar estados solo si está marcado "Diligenciado"
    const laftDiligenciado = $('#chkLaftDiligenciado').is(':checked');
    if (laftDiligenciado) {
        $('#subgrupoEstadosLaft').removeClass('d-none');
    } else {
        $('#subgrupoEstadosLaft').addClass('d-none');
        $('#subgrupoEstadosLaft .filter-checkbox').prop('checked', false);
    }

    // 2. LAFT Aprobado: Solo si está aprobado en LAFT se despliegan Negociación y Ambiental
    const laftAprobado = $('#chkLaftAprobado').is(':checked');
    if (laftAprobado) {
        $('#accordionNegociacion').removeClass('d-none');
        $('#accordionAmbiental').removeClass('d-none');
    } else {
        $('#accordionNegociacion').addClass('d-none');
        $('#accordionAmbiental').addClass('d-none');
        $('#accordionNegociacion .filter-checkbox').prop('checked', false);
        $('#accordionAmbiental .filter-checkbox').prop('checked', false);
        $('#accordionNegociacion').removeClass('active');
        $('#accordionAmbiental').removeClass('active');
    }

    // 3. Ambiental (Dentro de los desplegables de LAFT):
    // Nivel 1 -> Nivel 2: Si marca "Sí diligencia ambiental" se despliega ¿Diligenció el formulario?
    const aplicaAmbiental = $('#chkAplicaAmbiental').is(':checked');
    if (laftAprobado && aplicaAmbiental) {
        $('#subgrupoEstadoAmbiental').removeClass('d-none');
    } else {
        $('#subgrupoEstadoAmbiental').addClass('d-none');
        $('#subgrupoEstadoAmbiental .filter-checkbox').prop('checked', false);
    }

    // Nivel 2 -> Nivel 3: Si marca "Diligenciado" se despliega el Estado de aprobación
    const gestionDiligenciado = $('#chkGestionAmbientalDiligenciado').is(':checked');
    if (laftAprobado && aplicaAmbiental && gestionDiligenciado) {
        $('#subgrupoAprobacionAmbiental').removeClass('d-none');
    } else {
        $('#subgrupoAprobacionAmbiental').addClass('d-none');
        $('#subgrupoAprobacionAmbiental .filter-checkbox').prop('checked', false);
    }

    // 4. Cost Breakdown (CBD): Si maneja formato CBD, desplegar estados
    const aplicaCBD = $('#chkAplicaCBD').is(':checked');
    if (esDirecto && aplicaCBD) {
        $('#subgrupoEstadoCBD').removeClass('d-none');
    } else {
        $('#subgrupoEstadoCBD').addClass('d-none');
        $('#subgrupoEstadoCBD .filter-checkbox').prop('checked', false);
    }
}

function actualizarSubcategoriasDinamicas(catsChecked) {
    if (!catsChecked || catsChecked.length === 0) {
        $('#accordionSubcategoria').addClass('d-none').removeClass('active');
        $('#filterListSubCategoria .filter-option-item').addClass('d-none').find('.filter-checkbox').prop('checked', false);
        filtrosActivos.id_sub_categoria_proveedor = [];
        return;
    }

    $('#accordionSubcategoria').removeClass('d-none').addClass('active');

    $('#filterListSubCategoria .filter-option-item').each(function () {
        const catId = String($(this).data('categoria'));
        if (catsChecked.includes(catId)) {
            $(this).removeClass('d-none');
        } else {
            $(this).addClass('d-none').find('.filter-checkbox').prop('checked', false);
        }
    });
}

function actualizarEstadoFiltros() {
    actualizarVisibilidadDependenciasFiltros();

    Object.keys(filtrosActivos).forEach(function (key) {
        filtrosActivos[key] = [];
    });

    const activeChipsData = [];

    $('.filter-checkbox:checked').each(function () {
        const group = $(this).data('group');
        const val = $(this).val();
        let label = $(this).siblings('.filter-option-label').text();

        if (filtrosActivos[group]) {
            filtrosActivos[group].push(val);

            // Prefijar etiqueta de chips para desambiguar grupos similares
            if (group === 'id_estado_cumplimiento') label = `LAFT: ${label}`;
            else if (group === 'id_estado_negociacion') label = `Negociación: ${label}`;
            else if (group === 'id_estado_ambiental') label = `Ambiental (Aprobación): ${label}`;
            else if (group === 'tiene_laft') label = `LAFT: ${label}`;
            else if (group === 'formulario_ambiental_proveedor') label = `Ambiental: ${label}`;
            else if (group === 'tiene_gestion_ambiental') label = `Ambiental (Formulario): ${label}`;
            else if (group === 'maneja_formato_costbreakdown_proveedor') label = `CBD: ${label}`;
            else if (group === 'tiene_costbreakdown') label = `Cost Breakdown: ${label}`;
            else if (group === 'id_categoria_proveedor') label = `Categoría Servicio Suministros: ${label}`;
            else if (group === 'id_sub_categoria_proveedor') label = `Sub-categoría: ${label}`;
            else if (group === 'id_tipo_proveedor') label = `Tipo: ${label}`;
            else if (group === 'id_commodity') label = `Commodity: ${label}`;

            activeChipsData.push({
                group: group,
                val: val,
                label: label
            });
        }
    });

    if (typeof tablaProveedores !== 'undefined') {
        tablaProveedores.draw();
        const info = tablaProveedores.page.info();
        $('#lblConteoResultadosDrawer').text(info.recordsDisplay);
    }

    const totalFiltros = activeChipsData.length;
    const $badgeTotal = $('#badgeTotalFiltros');
    const $btnAbrir = $('#btnAbrirFiltros');

    if (totalFiltros > 0) {
        $badgeTotal.text(totalFiltros).removeClass('d-none');
        $btnAbrir.addClass('has-filters');
    } else {
        $badgeTotal.text(0).addClass('d-none');
        $btnAbrir.removeClass('has-filters');
    }

    renderizarChipsFiltros(activeChipsData);
}


function renderizarChipsFiltros(chips) {
    const $wrapper = $('#activeFiltersWrapper');
    const $list = $('#activeFiltersList').empty();

    if (!chips.length) {
        $wrapper.addClass('d-none');
        return;
    }

    chips.forEach(function (item) {
        $list.append(`
            <span class="filter-chip" data-group="${item.group}" data-val="${item.val}" title="Quitar este filtro">
                <span>${item.label}</span>
                <i class="fa-solid fa-xmark filter-chip-close"></i>
            </span>
        `);
    });

    $wrapper.removeClass('d-none');
}

function limpiarTodosLosFiltros() {
    $('.filter-checkbox').prop('checked', false);
    $('.filter-search-input').val('');
    $('#filterListCategoria .filter-option-item').removeClass('d-none');
    $('#filterListCommodity .filter-option-item').removeClass('d-none');
    $('#filterListAdmin .filter-option-item').removeClass('d-none');
    $('#accordionSubcategoria').addClass('d-none').removeClass('active');
    $('#filterListSubCategoria .filter-option-item').addClass('d-none');
    actualizarEstadoFiltros();
}

function abrirDrawerFiltros() {
    $('#filterDrawerBackdrop').addClass('show');
    $('#filterDrawer').addClass('show');
    $('body').css('overflow', 'hidden');
}

function cerrarDrawerFiltros() {
    $('#filterDrawerBackdrop').removeClass('show');
    $('#filterDrawer').removeClass('show');
    $('body').css('overflow', '');
}
