$(document).ready(async function () {
    await llenarSelectPermisos();
    await initChoicesPermisos();

    $('#formEditarAdministrador').on('submit', async function (e) {
        e.preventDefault();

        const form = this;
        const btn = document.getElementById('btnEditarAdministrador');

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

    function initChoicesPermisos() {
        const selectElem = document.getElementById('selectPermisos');
        if (!selectElem) return;

        const choicesPermisos = new Choices(selectElem, {
            removeItemButton: true,
            placeholderValue: 'Seleccionar permisos',
            noChoicesText: 'No hay más permisos disponibles',
            noResultsText: 'No se encontraron resultados',
            closeDropdownOnSelect: false
        });

        const permisosActuales = JSON.parse(sessionStorage.getItem('permisosAdminActual') || '[]');
        if (permisosActuales.length > 0) {
            const ids = permisosActuales.map(val => String(val));
            choicesPermisos.setChoiceByValue(ids);
        }
    }

    function llenarSelectPermisos() {
        $('#admin_id_gestionar').val(sessionStorage.getItem('admin_id_gestionar'));
        let select = $('#selectPermisos');
        select.empty();
        let permisos = sessionStorage.getItem('permisosAdministradores');
        if (permisos) {
            permisos = JSON.parse(permisos);
            permisos.forEach(p => {
                select.append(new Option(p.nombre_permiso, p.id_permiso));
            });
        }
    }
});
