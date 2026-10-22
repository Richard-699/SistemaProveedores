let tablaAdministradores;


$(document).ready(function () {
    tablaAdministradores = $('#tabla-administradores').DataTable({
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
            "url": "../../Handler/Administrador/administradoresHandler.php?action=onGetAdministradores",
            "dataSrc": "data"
        },
        "columns": [
            {
                "data": "id_administrador",
                "className": "dt-center",
                "render": function (data) {
                    return data ? data.substring(0, 8) + '...' : '';
                }
            },
            {
                "data": null,
                "className": "dt-center",
                "render": function (data, type, row) {
                    function toTitleCase(str) {
                        return str.toLowerCase().replace(/(?:^|\s)\S/g, function (c) { return c.toUpperCase(); });
                    }
                    let nombre = toTitleCase(row.nombre_administrador || '');
                    let apellido = toTitleCase(row.apellidos_administrador || '');
                    return `${nombre} ${apellido}`;
                }
            },
            { "data": "correo_hwi_administrador", "className": "dt-center" },
            {
                "data": "nombre_area",
                "className": "dt-center",
                "render": function (data) {
                    return data ? data : 'Sin área';
                }
            },
            {
                "data": "id_administrador",
                "className": "dt-center",
                "render": function (data, type, row) {
                    if (row.id_estado_administrador == 4) {
                        return `
                            <button class="btn btn-primary btn-sm me-1" onclick="editar('${data}')">
                                <i class="fa-solid fa-pencil"></i>
                            </button>
                            <button class="btn btn-danger btn-sm" onclick="eliminar('${data}', ${row.id_area_administrador}, ${row.id_tipo_negociador || null})">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        `;
                    } else if (row.id_estado_administrador == 5) {
                        return `<span class="badge bg-danger">Inactivo</span>`;
                    } else {
                        return `
                            <button class="btn btn-success btn-sm me-1" onclick="aprobar('${data}')">
                                <i class="fa-solid fa-check"></i>
                            </button>
                            <button class="btn btn-danger btn-sm" onclick="rechazar('${data}')">
                                <i class="fa-solid fa-times"></i>
                            </button>
                        `;
                    }
                }
            }
        ],
        "responsive": true,
        "ordering": true,
        "info": true,
        "searching": true
    });


});

async function obtenerPermisos() {
    try {
        let permisos = sessionStorage.getItem('permisosAdministradores');
        if (!permisos) {
            const response = await fetch('../../Handler/Administrador/administradoresHandler.php?action=obtenerPermisos');
            const result = await response.json();
            if (result.success) {
                sessionStorage.setItem('permisosAdministradores', JSON.stringify(result.data));
            } else {
                notify('danger', 'Error al obtener permisos: ' + result.message);
            }
        }
    } catch (error) {
        notify('danger', 'Excepción al cargar permisos. Intente recargar la página.');
    }
}

async function obtenerAdministradores() {
    try {
        const response = await fetch('../../Handler/Administrador/administradoresHandler.php?action=onGetAdministradores');
        const result = await response.json();
        if (result.success) {
            sessionStorage.setItem('administradoresSistema', JSON.stringify(result.data));
        } else {
            notify('danger', 'Error al obtener administradores: ' + result.message);
        }
    } catch (error) {
        notify('danger', 'Excepción al cargar administradores. Intente recargar la página.');
    }
}

async function aprobar(id) {
    mostrarCarga();
    try {
        await obtenerPermisos();
        sessionStorage.setItem('admin_id_gestionar', id);

        await ocultarCarga();

        Fancybox.show(
            [
                {
                    src: '_aprobarAdministrador.php',
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
                        sessionStorage.removeItem("admin_id_gestionar");

                        if (typeof tablaAdministradores !== "undefined" && tablaAdministradores) {
                            tablaAdministradores.ajax.reload(null, false);
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
        notify('danger', 'Error al abrir la ventana de aprobación: ' + error);
    }
}

async function editar(id) {
    mostrarCarga();
    try {
        await obtenerPermisos();

        const response = await fetch(`../../Handler/Administrador/administradoresHandler.php?action=obtenerPermisosAdmin&id_administrador=${id}`);
        const responsePermisos = await response.json();

        sessionStorage.setItem('admin_id_gestionar', id);
        sessionStorage.setItem('permisosAdminActual', JSON.stringify(responsePermisos.data || []));

        await ocultarCarga();

        Fancybox.show(
            [
                {
                    src: '_editarAdministrador.php',
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
                        sessionStorage.removeItem("admin_id_gestionar");
                        sessionStorage.removeItem("permisosAdminActual");

                        if (typeof tablaAdministradores !== "undefined" && tablaAdministradores) {
                            tablaAdministradores.ajax.reload(null, false);
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

function rechazar(id) {
    Swal.fire({
        title: '¿Rechazar administrador?',
        text: '¿Está seguro que desea rechazar a este administrador?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, rechazar',
        cancelButtonText: 'Cancelar',
        customClass: {
            confirmButton: 'swal-btn-confirm',
            cancelButton: 'swal-btn-cancel'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            mostrarCarga();
            $.ajax({
                url: '../../Handler/Administrador/administradoresHandler.php',
                type: 'POST',
                data: { action: 'rechazar', id_administrador: id },
                success: async function (response) {
                    await ocultarCarga();
                    if (response.success) {
                        notify('success', response.message);
                        tablaAdministradores.ajax.reload();
                    } else {
                        notify('danger', response.message);
                    }
                },
                error: async function () {
                    await ocultarCarga();
                    notify('danger', 'Error de red o de servidor.');
                }
            });
        }
    });
}

async function eliminar(id, idArea, idTipoNegociador) {
    if (idArea === 1) {
        mostrarCarga();
        try {
            await obtenerAdministradores();
            sessionStorage.setItem('admin_id_eliminar', id);
            sessionStorage.setItem('admin_tipo_negociador_eliminar', idTipoNegociador || '');

            await ocultarCarga();

            Fancybox.show(
                [
                    {
                        src: '_eliminarAdministrador.php',
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
                            sessionStorage.removeItem("admin_id_eliminar");
                            sessionStorage.removeItem("admin_tipo_negociador_eliminar");

                            if (typeof tablaAdministradores !== "undefined" && tablaAdministradores) {
                                tablaAdministradores.ajax.reload(null, false);
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
            notify('danger', 'Error al abrir la ventana de eliminación: ' + error);
        }
    } else {
        Swal.fire({
            title: '¿Inactivar administrador?',
            text: '¿Está seguro de que desea inactivar este administrador?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, inactivar',
            cancelButtonText: 'Cancelar',
            customClass: {
                confirmButton: 'swal-btn-confirm',
                cancelButton: 'swal-btn-cancel'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                mostrarCarga();
                $.ajax({
                    url: '../../Handler/Administrador/administradoresHandler.php',
                    type: 'POST',
                    data: { action: 'eliminarAdministrador', id_administrador: id },
                    success: async function (response) {
                        await ocultarCarga();
                        if (response.success) {
                            notify('success', response.message);
                            tablaAdministradores.ajax.reload(null, false);
                        } else {
                            notify('danger', response.message);
                        }
                    },
                    error: async function () {
                        await ocultarCarga();
                        notify('danger', 'Error de red o de servidor.');
                    }
                });
            }
        });
    }
}

$(document).on('click', '[data-fancybox-close], .btn-close', function (e) {
    if (typeof Fancybox !== 'undefined' && Fancybox.getInstance()) {
        e.preventDefault();
        Fancybox.close();
    }
});

