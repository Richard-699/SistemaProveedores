$(document).ready(async function () {
    let choicesCommodities = null;

    await llenarSelectsDatosProveedor();

    $('#formCrearProveedor #inputLogoProveedor').on('change', async function () {
        const file = this.files[0];
        if (file) {
            if (!file.type.match('image.*')) {
                notify('danger', 'Por favor selecciona un archivo de imagen válido (PNG, JPG o WEBP).');
                this.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = async function (e) {
                $('#formCrearProveedor #logoPreview').attr('src', e.target.result).removeClass('d-none');
                $('#formCrearProveedor #defaultLogoIcon').addClass('d-none');
            };
            reader.readAsDataURL(file);
        }
    });

    $('#formCrearProveedor #switchFormularioAmbiental').on('change', async function () {
        $('#formCrearProveedor #labelFormularioAmbiental').text(this.checked ? 'Sí' : 'No');
    });

    $('#formCrearProveedor #switchCostBreakdown').on('change', async function () {
        $('#formCrearProveedor #labelCostBreakdown').text(this.checked ? 'Sí' : 'No');
    });

    $('#formCrearProveedor #inputNumeroAcreedor').on('input', async function () {
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    $('#formCrearProveedor #inputPorcentajeBom').on('input', async function () {
        this.value = this.value.replace(/[^0-9.]/g, '');
        let parts = this.value.split('.');
        if (parts.length > 2) {
            this.value = parts[0] + '.' + parts.slice(1).join('');
        }
    });

    $('#formCrearProveedor #textareaHistoria').on('input', async function () {
        $('#formCrearProveedor #contadorHistoria').text(`${this.value.length} / 2000 caracteres`);
    });

    $('#formCrearProveedor #textareaDescripcion').on('input', async function () {
        $('#formCrearProveedor #contadorDescripcion').text(`${this.value.length} / 5000 caracteres`);
    });

    $('#formCrearProveedor #inputCorreosProveedor').on('input', async function () {
        await actualizarContadorCorreos();
    });

    $('#formCrearProveedor #selectTipoProveedor').on('change', async function () {
        const tipo = $(this).val();
        const selectDirecto = $('#formCrearProveedor #selectAdministradorProveedorDirecto');
        const selectIndirecto = $('#formCrearProveedor #selectAdministradorProveedorIndirecto');

        selectDirecto.empty().prop('disabled', true).removeAttr('name');
        selectIndirecto.empty().prop('disabled', true).removeAttr('name');

        if (!tipo) {
            $('#formCrearProveedor #seccionDirecto, #formCrearProveedor #seccionIndirecto').slideUp(200);
            return;
        }

        const selectActivo = (tipo == '1') ? selectDirecto : selectIndirecto;
        selectActivo.attr('name', 'id_administrador_proveedor');
        selectActivo.append(new Option('Cargando...', '')).prop('disabled', true);

        try {
            const response = await fetch(`../../Handler/Administrador/proveedoresHandler.php?action=obtenerNegociadoresPorTipo&id_tipo_negociador=${tipo}`);
            const result = await response.json();

            selectActivo.empty();
            if (result.success && result.data && result.data.length > 0) {
                selectActivo.append(new Option('Seleccione un negociador', '', true, true));
                selectActivo.find('option:first').prop('disabled', true);
                result.data.forEach(n => {
                    const nombreCompleto = `${n.nombre_administrador} ${n.apellidos_administrador || ''}`.trim();
                    selectActivo.append(new Option(nombreCompleto, n.id_administrador));
                });
                selectActivo.prop('disabled', false);
            } else {
                selectActivo.append(new Option('No hay negociadores disponibles', '')).prop('disabled', true);
            }
        } catch (error) {
            selectActivo.empty().append(new Option('Error al cargar negociadores', '')).prop('disabled', true);
        }

        if (tipo == '1') {
            $('#formCrearProveedor #seccionIndirecto, #formCrearProveedor #seccionServiciosSuministros').slideUp(200);
            $('#formCrearProveedor #seccionDirecto').slideDown(250, async function () {
                await initChoicesCommodities();
            });
        } else if (tipo == '2') {
            $('#formCrearProveedor #seccionDirecto').slideUp(200);
            $('#formCrearProveedor #seccionIndirecto, #formCrearProveedor #seccionServiciosSuministros').slideDown(250);
        } else {
            $('#formCrearProveedor #seccionDirecto, #formCrearProveedor #seccionIndirecto, #formCrearProveedor #seccionServiciosSuministros').slideUp(200);
        }
    });

    const $selectSubCatCrear = $('#formCrearProveedor #selectSubCategoria');
    const todasLasSubcategoriasCrear = $selectSubCatCrear.find('option:not(:first)').clone();
    $selectSubCatCrear.find('option:not(:first)').remove();

    $('#formCrearProveedor #selectCategoria').on('change', function () {
        const catId = $(this).val();
        $selectSubCatCrear.empty();

        if (!catId) {
            $selectSubCatCrear.append(new Option('Seleccione una categoría primero', '', true, true)).prop('disabled', true);
            return;
        }

        $selectSubCatCrear.append(new Option('Seleccione una sub-categoría', '', true, true));
        $selectSubCatCrear.find('option:first').prop('disabled', true);

        const filtradas = todasLasSubcategoriasCrear.filter(`[data-categoria="${catId}"]`);
        if (filtradas.length > 0) {
            $selectSubCatCrear.append(filtradas.clone());
            $selectSubCatCrear.prop('disabled', false);
        } else {
            $selectSubCatCrear.append(new Option('No hay sub-categorías disponibles', '', true, true)).prop('disabled', true);
        }
    });

    const $selectSubCatServCrear = $('#formCrearProveedor #selectSubCategoriaServicioSuministro');
    const todasLasSubcategoriasServCrear = $selectSubCatServCrear.find('option:not(:first)').clone();
    $selectSubCatServCrear.find('option:not(:first)').remove();

    $('#formCrearProveedor #selectCategoriaServicioSuministro').on('change', function () {
        const catId = $(this).val();
        $selectSubCatServCrear.empty();

        if (!catId) {
            $selectSubCatServCrear.append(new Option('Seleccione una categoría primero', '', true, true)).prop('disabled', true);
            return;
        }

        $selectSubCatServCrear.append(new Option('Seleccione una sub-categoría', '', true, true));
        $selectSubCatServCrear.find('option:first').prop('disabled', true);

        const filtradas = todasLasSubcategoriasServCrear.filter(`[data-categoria="${catId}"]`);
        if (filtradas.length > 0) {
            $selectSubCatServCrear.append(filtradas.clone());
            $selectSubCatServCrear.prop('disabled', false);
        } else {
            $selectSubCatServCrear.append(new Option('No hay sub-categorías disponibles', '', true, true)).prop('disabled', true);
        }
    });

    $('#formCrearProveedor').on('submit', async function (e) {
        e.preventDefault();

        const form = this;
        const btn = document.getElementById('btnGuardarProveedor');
        const targetUrl = '../../Handler/Administrador/proveedoresHandler.php?action=crearProveedor';
        const formData = new FormData(form);

        async function guardar(data) {
            if (btn) btn.disabled = true;
            mostrarCarga();

            try {
                const response = await fetch(targetUrl, {
                    method: 'POST',
                    body: data
                });
                const result = await response.json();

                await ocultarCarga();
                if (btn) btn.disabled = false;

                if (result.success) {
                    Fancybox.close();
                    notify('success', result.message);

                    if (typeof tablaProveedores !== 'undefined') {
                        tablaProveedores.ajax.reload(null, false);
                    }
                } else if (result.requiere_confirmacion) {
                    const confirmacion = (typeof alertaConfirmacion === 'function')
                        ? await alertaConfirmacion({
                            title: 'Proveedor Similar Encontrado',
                            text: `En este momento tienes este proveedor registrado: "${result.nombre_existente}". ¿Desea continuar con el registro de todas formas?`,
                            icon: 'warning',
                            confirmButtonText: 'Sí, continuar',
                            cancelButtonText: 'Cancelar'
                        })
                        : await Swal.fire({
                            title: 'Proveedor Similar Encontrado',
                            text: `En este momento tienes este proveedor registrado: "${result.nombre_existente}". ¿Desea continuar con el registro de todas formas?`,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Sí, continuar',
                            cancelButtonText: 'Cancelar',
                            customClass: {
                                confirmButton: 'swal-btn-confirm',
                                cancelButton: 'swal-btn-cancel'
                            }
                        });

                    if (confirmacion && confirmacion.isConfirmed) {
                        data.set('confirmar_similitud', '1');
                        await guardar(data);
                    }
                } else {
                    notify('warning', result.message);
                }
            } catch (error) {
                await ocultarCarga();
                if (btn) btn.disabled = false;
                console.error(error);
                notify('danger', 'Ocurrió un error al procesar la solicitud.');
            }
        }

        await guardar(formData);
    });

    async function initChoicesCommodities() {
        const selectElem = document.querySelector('#formCrearProveedor #selectCommodity');
        if (!selectElem) return;

        if (choicesCommodities) {
            choicesCommodities.destroy();
            choicesCommodities = null;
        }

        choicesCommodities = new Choices(selectElem, {
            removeItemButton: true,
            placeholderValue: 'Seleccionar commodities',
            noChoicesText: 'No hay más opciones disponibles',
            noResultsText: 'No se encontraron resultados',
            closeDropdownOnSelect: false
        });
    }


    async function contarCorreosValidos(texto) {
        if (!texto || !texto.trim()) return 0;
        const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        const partes = texto.split(',');
        let validos = 0;
        partes.forEach(p => {
            if (emailRegex.test(p.trim())) {
                validos++;
            }
        });
        return validos;
    }

    async function actualizarContadorCorreos() {
        const input = $('#formCrearProveedor #inputCorreosProveedor');
        const contador = $('#formCrearProveedor #contadorCorreos');
        if (!input.length || !contador.length) return;

        const cantidad = await contarCorreosValidos(input.val());
        const texto = cantidad === 1 ? '1 correo válido' : `${cantidad} correos válidos`;
        contador.text(texto);
        if (cantidad > 0) {
            contador.removeClass('text-muted').addClass('text-success');
        } else {
            contador.removeClass('text-success').addClass('text-muted');
        }
    }

    async function llenarSelectsDatosProveedor() {
        let selectDirecto = $('#formCrearProveedor #selectAdministradorProveedorDirecto');
        let selectIndirecto = $('#formCrearProveedor #selectAdministradorProveedorIndirecto');
        selectDirecto.empty().prop('disabled', true).removeAttr('name');
        selectIndirecto.empty().prop('disabled', true).removeAttr('name');
        await actualizarContadorCorreos();
    }
});
