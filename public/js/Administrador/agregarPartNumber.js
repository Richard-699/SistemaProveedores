$(document).ready(async function () {
    let choicesProveedor = null;

    await llenarProveedores();
    await initChoicesProveedor();

    await verificarHabilitarBotonAgregarOtro();
    await registrarEventosFormulario();

    $('#selectProveedorPartNumber').on('change', async function () {
        const idProveedor = $(this).val();
        if (idProveedor) {
            $('#nombreProveedorAsignado').text($(this).find(':selected').text());
            $('#seccionPartNumbers').removeClass('d-none');
            $('#btnGuardarPartNumbers').removeClass('d-none');
        } else {
            $('#seccionPartNumbers').addClass('d-none');
            $('#btnGuardarPartNumbers').addClass('d-none');
        }
    });

    $('#btnAgregarOtroPartNumber').on('click', async function () {
        const totalActual = $('#contenedorPartNumbers .item-partnumber').length;
        const nuevoIndex = totalActual + 1;

        const template = $('#templateItemPartNumber').html();
        const nuevoHtml = template.replace(/__INDEX__/g, nuevoIndex);
        const $nuevoItem = $(nuevoHtml);

        $('#contenedorPartNumbers').append($nuevoItem);
        await renumerarItems();
        await actualizarBotonesEliminar();
        await verificarHabilitarBotonAgregarOtro();

        const modalContent = document.getElementById('content-agregar-partnumber')?.closest('.fancybox__content')
            || document.querySelector('.fancybox__content');
        if (modalContent) {
            modalContent.scrollTo({
                top: modalContent.scrollHeight,
                behavior: 'smooth'
            });
        }
    });

    $('#contenedorPartNumbers').on('click', '.btn-eliminar-item', async function () {
        const $item = $(this).closest('.item-partnumber');
        $item.fadeOut(200, async function () {
            $(this).remove();
            await renumerarItems();
            await actualizarBotonesEliminar();
            await verificarHabilitarBotonAgregarOtro();
        });
    });

    $('#contenedorPartNumbers').on('change', '.input-imagen-partnumber', async function () {
        const file = this.files[0];
        const $contenedorItem = $(this).closest('.item-partnumber');
        const $preview = $contenedorItem.find('.img-preview');
        const $iconoDefault = $contenedorItem.find('.default-icon');

        if (file) {
            if (!file.type.match('image.*')) {
                notify('danger', 'Por favor selecciona un archivo de imagen válido (PNG, JPG o WEBP).');
                this.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = async function (e) {
                $preview.attr('src', e.target.result).removeClass('d-none');
                $iconoDefault.addClass('d-none');
            };
            reader.readAsDataURL(file);
        }
    });

    $('#contenedorPartNumbers').on('input', '.input-porcentaje-bom', async function () {
        this.value = this.value.replace(/[^0-9.]/g, '');
        let parts = this.value.split('.');
        if (parts.length > 2) {
            this.value = parts[0] + '.' + parts.slice(1).join('');
            parts = this.value.split('.');
        }
        if (parts.length > 1) {
            this.value = parts[0] + '.' + parts[1].slice(0, 3);
        }
    });

    $('#contenedorPartNumbers').on('input keyup change', '.textarea-descripcion', async function () {
        const len = $(this).val().length;
        const $contador = $(this).closest('.item-partnumber').find('.contador-descripcion');
        $contador.text(`${len} / 300 caracteres`);
    });

    async function registrarEventosFormulario() {
        const $form = $('#formAgregarPartNumber');
        $form.on('change', '#contenedorPartNumbers select', verificarHabilitarBotonAgregarOtro);
        $form.on('input', '#contenedorPartNumbers input:not([type="file"]), #contenedorPartNumbers textarea', verificarHabilitarBotonAgregarOtro);
    }

    async function verificarHabilitarBotonAgregarOtro() {
        const campos = $('#contenedorPartNumbers').find(':input').not('button, [type="file"]').toArray();
        const completo = campos.length > 0 && campos.every(campo => ($(campo).val() || '').trim() !== '');
        $('#btnAgregarOtroPartNumber').prop('disabled', !completo);
    }

    async function llenarProveedores() {
        const select = $('#selectProveedorPartNumber');
        select.empty();
        select.append('<option value="">Seleccione un proveedor</option>');
        const proveedores = JSON.parse(sessionStorage.getItem('proveedoresSistema') || '[]');
        proveedores.forEach(p => {
            const texto = p.numero_acreedor_proveedor
                ? `${p.numero_acreedor_proveedor} - ${p.nombre_proveedor}`
                : p.nombre_proveedor;
            select.append(new Option(texto, p.id_proveedor));
        });
    }

    async function initChoicesProveedor() {
        const selectElem = document.getElementById('selectProveedorPartNumber');
        if (!selectElem) return;

        if (typeof Choices === 'undefined') {
            notify('warning', 'El componente de selección no se cargó correctamente.');
            return;
        }

        if (choicesProveedor) {
            choicesProveedor.destroy();
            choicesProveedor = null;
        }

        choicesProveedor = new Choices(selectElem, {
            searchEnabled: true,
            removeItemButton: false,
            placeholderValue: 'Seleccione un proveedor',
            searchPlaceholderValue: 'Buscar proveedor o acreedor...',
            noResultsText: 'No se encontraron resultados',
            itemSelectText: '',
            shouldSort: false,
            position: 'bottom'
        });

        selectElem.addEventListener('addItem', async function (e) {
            $('#selectProveedorPartNumber').val(e.detail.value).trigger('change');
        });

        selectElem.addEventListener('removeItem', async function () {
            $('#selectProveedorPartNumber').val('').trigger('change');
        });

        $(document).off('click', '#content-agregar-partnumber .choices__list--dropdown .choices__item--choice').on('click', '#content-agregar-partnumber .choices__list--dropdown .choices__item--choice', function () {
            const val = $(this).attr('data-value') || '';
            if (choicesProveedor) {
                if (val) {
                    choicesProveedor.setChoiceByValue(String(val));
                    $('#selectProveedorPartNumber').val(val).trigger('change');
                } else {
                    choicesProveedor.removeActiveItems();
                    $('#selectProveedorPartNumber').val('').trigger('change');
                }
                choicesProveedor.hideDropdown();
            }
        });
    }

    async function renumerarItems() {
        $('#contenedorPartNumbers .item-partnumber').each(function (index) {
            const numero = index + 1;
            $(this).attr('data-index', numero);
            $(this).find('.item-titulo-seccion').html(`<i class="material-icons me-2 fs-5 text-secondary">precision_manufacturing</i> Información del Part Number #${numero}`);

            const $label = $(this).find('.imagen-preview-label');
            const $input = $(this).find('.input-imagen-partnumber');
            const nuevoId = `inputImagenPartNumber_${numero}`;
            $label.attr('for', nuevoId);
            $input.attr('id', nuevoId);
        });
    }

    async function actualizarBotonesEliminar() {
        const total = $('#contenedorPartNumbers .item-partnumber').length;
        if (total > 1) {
            $('#contenedorPartNumbers .btn-eliminar-item').removeClass('d-none');
        } else {
            $('#contenedorPartNumbers .btn-eliminar-item').addClass('d-none');
        }
    }

    $('#formAgregarPartNumber').on('submit', async function (e) {
        e.preventDefault();

        const btn = document.getElementById('btnGuardarPartNumbers');
        if (btn) btn.disabled = true;
        mostrarCarga();

        try {
            const form = this;
            const formData = new FormData(form);
            const action = 'crearPartNumber';
            formData.append('action', action);

            const response = await fetch('../../Handler/Administrador/partNumbersHandler.php', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            await ocultarCarga();
            if (btn) btn.disabled = false;

            if (!result.success) {
                notify('danger', result.message);
                return;
            }

            const total = $('#contenedorPartNumbers .item-partnumber').length;
            const mensaje = total > 1
                ? 'Part Numbers registrados correctamente.'
                : 'Part Number registrado correctamente.';

            notify('success', mensaje);
            Fancybox.close();
        } catch (error) {
            await ocultarCarga();
            if (btn) btn.disabled = false;
            notify('danger', 'Ocurrió un error al procesar la solicitud.');
        }
    });
});
