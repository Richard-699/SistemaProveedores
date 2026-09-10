let tablaProveedores;

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
            "url": "../../Handler/Proveedor/proveedoresHandler.php?action=onGetProveedores",
            "dataSrc": "data"
        },
        "autoWidth": false,
        "columns": [
            {
                "data": "numero_acreedor_proveedor",
                "width": "13%",
                "className": "dt-center",
                "render": function (data) {
                    return data ? data : 'Sin asignar';
                }
            },
            {
                "data": "nombre_proveedor",
                "width": "20%",
                "className": "dt-center",
                "render": function (data) {
                    if (!data) return '';
                    return data.toLowerCase().replace(/(?:^|\s)\S/g, function (c) { return c.toUpperCase(); });
                }
            },
            {
                "data": "nombre_tipo",
                "width": "14%",
                "className": "dt-center",
                "render": function (data) {
                    return data ? data : 'Sin tipo';
                }
            },
            {
                "data": "nombre_administrador",
                "width": "27%",
                "className": "dt-center",
                "render": function (data) {
                    if (!data) return 'Sin asignar';
                    return data.toLowerCase().replace(/(?:^|\s)\S/g, function (c) { return c.toUpperCase(); });
                }
            },
            {
                "data": "nombre_estado",
                "width": "14%",
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
                "width": "12%",
                "className": "dt-center",
                "orderable": false,
                "render": function (data, type, row) {
                    return `
                        <button class="btn btn-primary btn-sm me-1" onclick="editar('${data}')" title="Editar">
                            <i class="fa-solid fa-pencil"></i>
                        </button>
                        <button class="btn btn-danger btn-sm" onclick="eliminar('${data}')" title="Eliminar">
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
});

function editar(id) {
    console.log("Editar proveedor:", id);
}

function eliminar(id) {
    console.log("Eliminar proveedor:", id);
}

