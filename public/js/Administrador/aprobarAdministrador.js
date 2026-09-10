$(document).on('submit', '#formAprobarAdministrador', async function (e) {
    e.preventDefault();

    const form = this;
    const btn = document.getElementById('btnAprobarAdministrador');

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

function initSelect2Permisos() {
    $('#selectPermisos').select2({
        theme: 'bootstrap-5',
        placeholder: 'Seleccionar permisos',
        allowClear: true,
        closeOnSelect: false
    }).on('change', function () {
        let searchField = $(this).next('.select2-container').find('.select2-search__field');
        searchField.attr('placeholder', 'Seleccionar permisos');
        if (typeof ajustarAlturaSelect2 === 'function') ajustarAlturaSelect2();
    }).on('select2:open', function () {
        let dropdown = $('.select2-dropdown');
        if (!dropdown.find('.select2-close-btn-wrapper').length) {
            dropdown.append(
                `<div class="select2-close-btn-wrapper p-2 border-top bg-light text-center">
                    <button type="button" class="btn btn-sm btn-custom-teal w-100" onclick="$('#selectPermisos').select2('close');">
                        Aceptar
                    </button>
                </div>`
            );
        }
    });

    if (typeof ajustarAlturaSelect2 === 'function') ajustarAlturaSelect2();
}

function llenarSelectPermisos() {
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

$(document).ready(function() {
    if (typeof llenarSelectPermisos === 'function') {
        llenarSelectPermisos();
    }
    if (typeof initSelect2Permisos === 'function') {
        initSelect2Permisos();
    }
});
