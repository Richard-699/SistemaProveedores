$(document).ready(async function () {
    let choicesInstance = null;

    await llenarSelectAdministradores();
    await initChoicesNuevoAdministrador();

    $('#formEliminarAdministrador').on('submit', async function (e) {
        e.preventDefault();

        const form = this;
        const btn = document.getElementById('btnEliminarAdministrador');
        const nuevoAdminVal = $('#selectNuevoAdministrador').val();

        if (!nuevoAdminVal) {
            notify('warning', 'Debe seleccionar el administrador al cual se transferirán los proveedores.');
            return;
        }

        try {
            const formData = new FormData(form);

            if (btn) btn.disabled = true;
            mostrarCarga();

            const targetUrl = '../../Handler/Administrador/administradoresHandler.php';

            const response = await fetch(targetUrl, {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            await ocultarCarga();
            if (btn) btn.disabled = false;

            if (result.success) {
                Fancybox.close();
                notify('success', result.message);

                if (result.refresh_required) {
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    if (typeof tablaAdministradores !== 'undefined') {
                        tablaAdministradores.ajax.reload(null, false);
                    }
                }
            } else {
                notify('danger', result.message);
            }
        } catch (error) {
            await ocultarCarga();
            if (btn) btn.disabled = false;
            console.error(error);
            notify('danger', 'Ocurrió un error al procesar la solicitud.');
        }
    });

    function initChoicesNuevoAdministrador() {
        const selectElem = document.getElementById('selectNuevoAdministrador');
        if (!selectElem) return;

        if (choicesInstance) {
            choicesInstance.destroy();
            choicesInstance = null;
        }

        choicesInstance = new Choices(selectElem, {
            removeItemButton: false,
            placeholderValue: 'Seleccionar administrador',
            searchPlaceholderValue: 'Buscar administrador...',
            noResultsText: 'No se encontraron resultados',
            itemSelectText: '',
            shouldSort: false,
            position: 'bottom'
        });
    }

    function llenarSelectAdministradores() {
        const currentAdminId = sessionStorage.getItem('admin_id_eliminar');
        const currentTipoNegociador = sessionStorage.getItem('admin_tipo_negociador_eliminar');

        $('#admin_id_eliminar').val(currentAdminId);
        let select = $('#selectNuevoAdministrador');
        select.empty();
        select.append(new Option('Seleccione un administrador...', ''));

        if (currentTipoNegociador == '1') {
            $('#labelNuevoAdministrador').html('Seleccione el negociador directo que quedará a cargo de los proveedores: <span class="text-danger">*</span>');
        } else if (currentTipoNegociador == '2') {
            $('#labelNuevoAdministrador').html('Seleccione el negociador indirecto que quedará a cargo de los proveedores: <span class="text-danger">*</span>');
        }

        let administradores = sessionStorage.getItem('administradoresSistema');
        let disponiblesCount = 0;

        if (administradores) {
            administradores = JSON.parse(administradores);
            administradores.forEach(admin => {
                // Debe estar activo (4), pertenecer a Negociación (1), y no ser el administrador a inactivar
                if (admin.id_administrador !== currentAdminId && admin.id_estado_administrador == 4 && admin.id_area_administrador == 1) {
                    let coincideTipo = true;
                    if (currentTipoNegociador) {
                        coincideTipo = (admin.id_tipo_negociador == currentTipoNegociador);
                    }

                    if (coincideTipo) {
                        select.append(new Option(`${admin.nombre_administrador} ${admin.apellidos_administrador}`, admin.id_administrador));
                        disponiblesCount++;
                    }
                }
            });
        }

        if (disponiblesCount === 0) {
            const tipoTexto = currentTipoNegociador == '1' ? 'directos' : (currentTipoNegociador == '2' ? 'indirectos' : '');
            const mensajeAviso = tipoTexto
                ? `No hay otros negociadores ${tipoTexto} activos disponibles para transferir los proveedores.`
                : 'No hay otros negociadores activos disponibles para transferir los proveedores.';

            $('#alertaTransferencia').removeClass('alert-warning').addClass('alert-danger');
            $('#textoAlertaTransferencia').text(mensajeAviso);
            $('#btnEliminarAdministrador').prop('disabled', true);
        }
    }
});
