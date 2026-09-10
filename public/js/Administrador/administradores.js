let tablaAdministradores;

function ajustarAlturaSelect2() {
    setTimeout(function () {
        let container = $('#selectPermisos').next('.select2-container');
        if (container.length) {
            container.find('.select2-selection--multiple').css({
                'height': 'auto',
                'min-height': '120px'
            });
        }
    }, 50);
}


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
                            <button class="btn btn-danger btn-sm" onclick="eliminar('${data}')">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        `;
                    } else if (row.id_estado_administrador == 5) {
                        return `<span class="badge bg-danger">Rechazado</span>`;
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

async function aprobar(id) {
    mostrarCarga();
    try {
        await obtenerPermisos();

        const responseHtml = await fetch('_aprobarAdministrador.php');
        const html = await responseHtml.text();

        await ocultarCarga();

        Fancybox.show([{
            src: html,
            type: 'html'
        }], {
            closeButton: false,
            dragToClose: false,
            on: {
                done: (fancybox, slide) => {
                    $('#admin_id_gestionar').val(id);
                    if (typeof llenarSelectPermisos === 'function') llenarSelectPermisos();
                    if (typeof initSelect2Permisos === 'function') initSelect2Permisos();
                }
            }
        });
    } catch (error) {
        await ocultarCarga();
        notify('danger', 'Error al abrir la ventana de aprobación: ' + error);
    }
}

async function editar(id) {
    mostrarCarga();
    try {
        await obtenerPermisos();

        const [responseHtml, responsePermisos] = await Promise.all([
            fetch('_editarAdministrador.php').then(res => res.text()),
            $.ajax({
                url: '../../Handler/Administrador/administradoresHandler.php',
                type: 'GET',
                data: {
                    action: 'obtenerPermisosAdmin',
                    id_administrador: id
                }
            })
        ]);

        await ocultarCarga();

        Fancybox.show([{
            src: responseHtml,
            type: 'html'
        }], {
            closeButton: false,
            dragToClose: false,
            on: {
                done: (fancybox, slide) => {
                    $('#admin_id_gestionar').val(id);
                    if (typeof llenarSelectPermisos === 'function') llenarSelectPermisos();
                    if (typeof initSelect2Permisos === 'function') initSelect2Permisos();

                    if (responsePermisos && responsePermisos.success) {
                        $('#selectPermisos').val(responsePermisos.data).trigger('change');
                        ajustarAlturaSelect2();
                    } else if (responsePermisos && !responsePermisos.success) {
                        notify('danger', 'Error al obtener los permisos actuales: ' + responsePermisos.message);
                    }
                }
            }
        });
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

function eliminar(id) {
    Swal.fire({
        title: '¿Eliminar administrador?',
        text: 'Esta acción no se puede deshacer.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
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
                data: { action: 'eliminar', id_administrador: id },
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

