let tablaPartNumbers;

function toTitleCase(str) {
    if (!str) return '';
    return str.toLowerCase().replace(/(?:^|\s)\S/g, function (c) { return c.toUpperCase(); });
}

$(document).ready(function () {
    tablaPartNumbers = $('#tabla-partnumbers').DataTable({
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
            "url": "../../Handler/Administrador/partNumbersHandler.php?action=onGetPartNumbers",
            "dataSrc": "data"
        },
        "columns": [
            {
                "data": "nombre_proveedor",
                "className": "dt-center",
                "render": function (data) {
                    return data ? toTitleCase(data) : 'Sin asignar';
                }
            },
            {
                "data": "partnumber",
                "className": "dt-center",
                "render": function (data) {
                    return data ? data.trim() : '';
                }
            },
            {
                "data": "descripcion_partnumber",
                "className": "dt-center",
                "render": function (data) {
                    return data ? data : '';
                }
            },
            {
                "data": "descripcion_commodity",
                "className": "dt-center",
                "render": function (data) {
                    return data ? data : 'Sin commodity';
                }
            },
            {
                "data": "id_partnumber",
                "className": "dt-center",
                "orderable": false,
                "render": function (data, type, row) {
                    return `
                        <button type="button" class="btn btn-primary btn-sm me-1" onclick="editar(${data})">
                            <i class="fa-solid fa-pencil"></i>
                        </button>
                        <button type="button" class="btn btn-danger btn-sm" onclick="eliminar(${data})">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    `;
                }
            }
        ],
        "responsive": true,
        "ordering": true,
        "info": true,
        "searching": true
    });

    $('#btnNuevoPartNumber').on('click', async function () {
        mostrarCarga();
        try {
            await obtenerProveedores();

            Fancybox.show(
                [
                    {
                        src: '_agregarPartNumber.php',
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
                            sessionStorage.removeItem('proveedoresSistema');

                            if (typeof tablaPartNumbers !== "undefined" && tablaPartNumbers) {
                                tablaPartNumbers.ajax.reload(null, false);
                            }

                            document.body.classList.remove("fancybox-active");
                            document.body.style.cursor = "default";
                        },
                    },
                    trapFocus: false,
                    placeFocusBack: false,
                }
            );
        } catch (error) {
            await ocultarCarga();
            notify('danger', 'Error al abrir la ventana de registro: ' + error);
        }
    });
});

async function obtenerProveedores() {
    try {
        let proveedores = sessionStorage.getItem('proveedoresSistema');
        if (!proveedores) {
            const response = await fetch('../../Handler/Administrador/partNumbersHandler.php?action=obtenerProveedores');
            const result = await response.json();
            if (result.success && result.data) {
                sessionStorage.setItem('proveedoresSistema', JSON.stringify(result.data));
            } else {
                notify('danger', 'Error al obtener proveedores: ' + (result.message || ''));
            }
        }
    } catch (e) {
        notify('danger', 'Excepción al cargar proveedores. Intente recargar la página.');
    }
}

async function editar(id) {
    mostrarCarga();
    try {
        const response = await fetch(`../../Handler/Administrador/partNumbersHandler.php?action=obtenerPartNumberPorId&id_partnumber=${id}`);
        const result = await response.json();

        if (!result.success) {
            await ocultarCarga();
            notify('danger', result.message || 'Error al obtener datos del Part Number.');
            return;
        }

        sessionStorage.setItem('partNumberEditar', JSON.stringify(result.data));

        await ocultarCarga();

        Fancybox.show(
            [
                {
                    src: '_editarPartNumber.php',
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
                        sessionStorage.removeItem("partNumberEditar");

                        if (typeof tablaPartNumbers !== "undefined" && tablaPartNumbers) {
                            tablaPartNumbers.ajax.reload(null, false);
                        }

                        document.body.classList.remove("fancybox-active");
                        document.body.style.cursor = "default";
                    },
                },
                trapFocus: false,
                placeFocusBack: false,
            }
        );
    } catch (error) {
        await ocultarCarga();
        notify('danger', 'Error al abrir la ventana de edición: ' + error);
    }
}

async function eliminar(id) {
    Swal.fire({
        title: '¿Eliminar Part Number?',
        text: '¿Está seguro de que desea eliminar este Part Number? Esta acción no se puede revertir.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
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
                const action = 'eliminarPartNumber';
                formData.append('action', action);
                formData.append('id_partnumber', id);

                const response = await fetch('../../Handler/Administrador/partNumbersHandler.php', {
                    method: 'POST',
                    body: formData
                });

                const res = await response.json();

                await ocultarCarga();
                if (res.success) {
                    notify('success', 'Part Number eliminado correctamente.');
                    tablaPartNumbers.ajax.reload(null, false);
                } else {
                    notify('danger', res.message);
                }
            } catch (error) {
                await ocultarCarga();
                notify('danger', 'Error al eliminar el Part Number.');
            }
        }
    });
}

$(document).on('click', '[data-fancybox-close], .btn-close', function (e) {
    if (typeof Fancybox !== 'undefined' && Fancybox.getInstance()) {
        e.preventDefault();
        Fancybox.close();
    }
});
