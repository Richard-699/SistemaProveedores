let tablaProveedores;

function toTitleCase(str) {
    if (!str) return '';
    return str.toLowerCase().replace(/(?:^|\s)\S/g, function (c) { return c.toUpperCase(); });
}

$(document).ready(function () {
    tablaProveedores = $('#tabla-proveedores').DataTable({
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json"
        },
        "lengthMenu": [
            [10, 50, 100, 200, -1],
            [10, 50, 100, 200, "Todos"]
        ],
        "scrollCollapse": true,
        "paging": true,
        "pageLength": 10,
        "ajax": {
            "url": "../../Handler/Administrador/proveedoresHandler.php?action=onGetProveedores",
            "dataSrc": function (json) {
                if (typeof actualizarDatosFiltros === 'function' && json && json.data) {
                    actualizarDatosFiltros(json.data);
                }
                return json.data || [];
            }
        },
        "columns": [
            {
                "data": "numero_acreedor_proveedor",
                "className": "dt-center",
                "render": function (data) {
                    return (!data || data === '0' || data === 0 || String(data).trim() === '') ? 'Sin asignar' : data;
                }
            },
            {
                "data": "nombre_proveedor",
                "className": "dt-center",
                "render": function (data) {
                    return data ? toTitleCase(data) : '';
                }
            },
            {
                "data": "nombre_tipo",
                "className": "dt-center",
                "render": function (data) {
                    return data ? data : 'Sin tipo';
                }
            },
            {
                "data": "nombre_administrador",
                "className": "dt-center",
                "render": function (data) {
                    return data ? toTitleCase(data) : 'Sin asignar';
                }
            },
            {
                "data": "nombre_estado",
                "className": "dt-center",
                "render": function (data, type, row) {
                    let badgeClass = '';
                    switch (row.id_estado_proveedor) {
                        case 1: badgeClass = 'badge-rechazado'; break;
                        case 2: badgeClass = 'badge-aprobado'; break;
                        case 3: badgeClass = 'badge-pendiente'; break;
                        case 4: badgeClass = 'badge-activo'; break;
                        case 5: badgeClass = 'badge-inactivo'; break;
                        default: badgeClass = 'badge-inactivo'; break;
                    }
                    return `<span class="badge ${badgeClass}">${data || 'Desconocido'}</span>`;
                }
            },
            {
                "data": "id_proveedor",
                "className": "dt-center",
                "orderable": false,
                "render": function (data, type, row) {
                    let botonEstado = '';
                    if (row.id_estado_proveedor == 4) {
                        botonEstado = `
                            <button class="btn btn-danger btn-sm" onclick="cambiarEstado('${data}', 5)">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        `;
                    } else {
                        botonEstado = `
                            <button class="btn btn-success btn-sm" onclick="cambiarEstado('${data}', 4)">
                                <i class="fa-solid fa-rotate-right"></i>
                            </button>
                        `;
                    }

                    return `
                        <button class="btn btn-primary btn-sm me-1" onclick="editar('${data}')">
                            <i class="fa-solid fa-pencil"></i>
                        </button>
                        ${botonEstado}
                    `;
                }
            }
        ],
        "responsive": true,
        "ordering": true,
        "info": true,
        "searching": true,
        "initComplete": function (settings, json) {
            if (typeof inicializarFiltrosProveedor === 'function') {
                inicializarFiltrosProveedor(json && json.data ? json.data : []);
            }
        },
        "drawCallback": function () {
            if (typeof ubicarBotonFiltros === 'function') {
                ubicarBotonFiltros();
            }
        }
    });

    $('#btnNuevoProveedor').on('click', async function () {
        mostrarCarga();
        try {
            await ocultarCarga();

            Fancybox.show(
                [
                    {
                        src: '_crearProveedor.php',
                        type: "ajax",
                    },
                ],
                {
                    dragToClose: false,
                    closeButton: false,
                    Panzoom: {
                        touch: false,
                    },
                    on: {
                        reveal: (fancybox, slide) => {
                            ocultarCarga();
                        },
                        destroy: (fancybox) => {
                            if (typeof tablaProveedores !== "undefined" && tablaProveedores) {
                                tablaProveedores.ajax.reload(null, false);
                            }

                            document.body.classList.remove("fancybox-active");
                            document.body.style.cursor = "default";
                        },
                    },

                    trapFocus: false,
                    placeFocusBack: false,
                },
            );
        } catch (error) {
            await ocultarCarga();
            notify('danger', 'Error al abrir la ventana de nuevo proveedor: ' + error);
        }
    });

});

async function descargarExcel() {
    if (!tablaProveedores) return;

    const btn = document.getElementById('btnDescargarExcel');

    const rows = tablaProveedores.rows({ search: 'applied' }).data().toArray();

    if (!rows || rows.length === 0) {
        notify('warning', 'No hay registros disponibles para exportar.');
        return;
    }

    const ids = rows.map(r => r.id_proveedor).filter(Boolean);

    if (ids.length === 0) {
        notify('warning', 'No se encontraron proveedores válidos para exportar.');
        return;
    }

    btn.disabled = true;
    mostrarCarga();

    try {
        const formData = new FormData();
        formData.append('ids', JSON.stringify(ids));

        const targetUrl = '../../Handler/Administrador/proveedoresHandler.php?action=exportarExcel';
        const response = await fetch(targetUrl, {
            method: 'POST',
            body: formData
        });

        const contentType = response.headers.get('content-type') || '';
        if (contentType.includes('application/json')) {
            const result = await response.json();
            await ocultarCarga();
            notify('danger', result.message || 'Error al exportar a Excel.');
            return;
        }

        const blob = await response.blob();
        descargarBlob(blob, response, 'Proveedores.xlsx');

        await ocultarCarga();
        notify('success', 'Archivo Excel descargado exitosamente.');
    } catch (error) {
        await ocultarCarga();
        notify('danger', 'Error al descargar el archivo Excel.');
    } finally {
        btn.disabled = false;
    }
}

async function editar(id) {
    mostrarCarga();
    try {
        const response = await fetch(`../../Handler/Administrador/proveedoresHandler.php?action=obtenerProveedorPorId&id_proveedor=${id}`);
        const responseProveedor = await response.json();
        sessionStorage.setItem("proveedorEditar", JSON.stringify(responseProveedor));

        await ocultarCarga();

        Fancybox.show(
            [
                {
                    src: '_editarProveedor.php',
                    type: "ajax",
                },
            ],
            {
                dragToClose: false,
                closeButton: false,
                Panzoom: {
                    touch: false,
                },
                on: {
                    reveal: (fancybox, slide) => {
                        ocultarCarga();
                    },
                    destroy: (fancybox) => {
                        sessionStorage.removeItem("proveedorEditar");

                        if (typeof tablaProveedores !== "undefined" && tablaProveedores) {
                            tablaProveedores.ajax.reload(null, false);
                        }

                        document.body.classList.remove("fancybox-active");
                        document.body.style.cursor = "default";
                    },
                },

                trapFocus: false,
                placeFocusBack: false,
            },
        );
    } catch (error) {
        await ocultarCarga();
        notify('danger', 'Error al abrir la ventana de edición: ' + error);
    }
}

async function cambiarEstado(id, nuevoEstado) {
    const isInactivar = (nuevoEstado == 5);
    const accion = isInactivar ? 'inactivar' : 'activar';
    const titulo = isInactivar ? '¿Inactivar proveedor?' : '¿Activar proveedor?';
    const texto = `¿Está seguro que desea ${accion} a este proveedor?`;
    const confirmBtnText = isInactivar ? 'Sí, inactivar' : 'Sí, activar';

    Swal.fire({
        title: titulo,
        text: texto,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: confirmBtnText,
        cancelButtonText: 'Cancelar',
        customClass: {
            confirmButton: 'swal-btn-confirm',
            cancelButton: 'swal-btn-cancel'
        }
    }).then(async (result) => {
        if (result.isConfirmed) {
            mostrarCarga();
            try {
                const formData = new FormData();
                formData.append('id_proveedor', id);
                formData.append('id_estado', nuevoEstado);

                const targetUrl = '../../Handler/Administrador/proveedoresHandler.php?action=cambiarEstado';
                const response = await fetch(targetUrl, {
                    method: 'POST',
                    body: formData
                });

                const res = await response.json();

                await ocultarCarga();
                if (res.success) {
                    notify('success', res.message);
                    tablaProveedores.ajax.reload(null, false);
                } else {
                    notify('danger', res.message);
                }
            } catch (error) {
                await ocultarCarga();
                notify('danger', 'Error al cambiar el estado del proveedor.');
            }
        }
    });
}


$(document).on('click', '#btnDescargarExcel', async function () {
    await descargarExcel();
});

$(document).on('click', '[data-fancybox-close], .btn-close', function (e) {
    if (typeof Fancybox !== 'undefined' && Fancybox.getInstance()) {
        e.preventDefault();
        Fancybox.close();
    }
});
