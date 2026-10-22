$(document).ready(async function () {
    let choicesCommodities = null;

    await llenarSelectsDatosProveedor();
    await llenarDatosProveedorEditar();

    $('#formEditarProveedor #inputLogoProveedor').on('change', async function () {
        const file = this.files[0];
        if (file) {
            if (!file.type.match('image.*')) {
                notify('danger', 'Por favor selecciona un archivo de imagen válido (PNG, JPG o WEBP).');
                this.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = async function (e) {
                $('#formEditarProveedor #logoPreview').attr('src', e.target.result).removeClass('d-none');
                $('#formEditarProveedor #defaultLogoIcon').addClass('d-none');
            };
            reader.readAsDataURL(file);
        }
    });

    $('#formEditarProveedor #switchFormularioAmbiental').on('change', async function () {
        $('#formEditarProveedor #labelFormularioAmbiental').text(this.checked ? 'Sí' : 'No');
    });

    $('#formEditarProveedor #switchCartaBeneficiarios').on('change', async function () {
        $('#formEditarProveedor #labelCartaBeneficiarios').text(this.checked ? 'Sí' : 'No');
    });

    $('#formEditarProveedor #switchCostBreakdown').on('change', async function () {
        $('#formEditarProveedor #labelCostBreakdown').text(this.checked ? 'Sí' : 'No');
    });

    $('#formEditarProveedor #inputNumeroAcreedor').on('input', async function () {
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    $('#formEditarProveedor #inputPorcentajeBom').on('input', async function () {
        this.value = this.value.replace(/[^0-9.]/g, '');
        let parts = this.value.split('.');
        if (parts.length > 2) {
            this.value = parts[0] + '.' + parts.slice(1).join('');
        }
    });

    $('#formEditarProveedor #textareaHistoria').on('input keyup paste', async function () {
        $('#formEditarProveedor #contadorHistoria').text(`${this.value.length} / 2000 caracteres`);
    });

    $('#formEditarProveedor #textareaDescripcion').on('input keyup paste', async function () {
        $('#formEditarProveedor #contadorDescripcion').text(`${this.value.length} / 5000 caracteres`);
    });

    $('#formEditarProveedor #inputCorreosProveedor').on('input', async function () {
        await actualizarContadorCorreosEditar();
    });

    $('#formEditarProveedor #selectTipoProveedor').on('change', async function () {
        const tipo = $(this).val();

        await cargarNegociadoresPorTipoEditar(tipo);

        if (tipo == '1') {
            $('#formEditarProveedor #seccionIndirecto, #formEditarProveedor #seccionServiciosSuministros').slideUp(200);
            $('#formEditarProveedor #seccionDirecto').slideDown(250, async function () {
                await initChoicesCommodities();
            });
        } else if (tipo == '2') {
            $('#formEditarProveedor #seccionDirecto').slideUp(200);
            $('#formEditarProveedor #seccionIndirecto, #formEditarProveedor #seccionServiciosSuministros').slideDown(250);
        } else {
            $('#formEditarProveedor #seccionDirecto, #formEditarProveedor #seccionIndirecto, #formEditarProveedor #seccionServiciosSuministros').slideUp(200);
        }
    });

    const $selectSubCatEditar = $('#formEditarProveedor #selectSubCategoria');
    const todasLasSubcategoriasEditar = $selectSubCatEditar.find('option:not(:first)').clone();
    $selectSubCatEditar.find('option:not(:first)').remove();

    $('#formEditarProveedor #selectCategoria').on('change', function () {
        cargarSubcategoriasEditar($(this).val());
    });

    const $selectSubCatServEditar = $('#formEditarProveedor #selectSubCategoriaServicioSuministro');
    const todasLasSubcategoriasServEditar = $selectSubCatServEditar.find('option:not(:first)').clone();
    $selectSubCatServEditar.find('option:not(:first)').remove();

    $('#formEditarProveedor #selectCategoriaServicioSuministro').on('change', function () {
        cargarSubcategoriasServicioSuministroEditar($(this).val());
    });

    $('#formEditarProveedor').on('submit', async function (e) {
        e.preventDefault();

        const form = this;
        const btn = document.getElementById('btnEditarProveedor');
        const targetUrl = '../../Handler/Administrador/proveedoresHandler.php?action=editarProveedor';
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
                            text: `En este momento tienes este proveedor registrado: "${result.nombre_existente}". ¿Desea continuar con la actualización de todas formas?`,
                            icon: 'warning',
                            confirmButtonText: 'Sí, continuar',
                            cancelButtonText: 'Cancelar'
                        })
                        : await Swal.fire({
                            title: 'Proveedor Similar Encontrado',
                            text: `En este momento tienes este proveedor registrado: "${result.nombre_existente}". ¿Desea continuar con la actualización de todas formas?`,
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
        const selectElem = document.querySelector('#formEditarProveedor #selectCommodity');
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


    async function actualizarContadoresTextoEditar() {
        const historia = $('#formEditarProveedor #textareaHistoria').val() || '';
        $('#formEditarProveedor #contadorHistoria').text(`${historia.length} / 2000 caracteres`);

        const descripcion = $('#formEditarProveedor #textareaDescripcion').val() || '';
        $('#formEditarProveedor #contadorDescripcion').text(`${descripcion.length} / 5000 caracteres`);
    }

    async function contarCorreosValidosEditar(texto) {
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

    async function actualizarContadorCorreosEditar() {
        const input = $('#formEditarProveedor #inputCorreosProveedor');
        const contador = $('#formEditarProveedor #contadorCorreos');
        if (!input.length || !contador.length) return;

        const cantidad = await contarCorreosValidosEditar(input.val());
        const texto = cantidad === 1 ? '1 correo válido' : `${cantidad} correos válidos`;
        contador.text(texto);
        if (cantidad > 0) {
            contador.removeClass('text-muted').addClass('text-success');
        } else {
            contador.removeClass('text-success').addClass('text-muted');
        }
    }

    async function cargarNegociadoresPorTipoEditar(tipo, negociadorSeleccionado = null) {
        const selectDirecto = $('#formEditarProveedor #selectAdministradorProveedorDirecto');
        const selectIndirecto = $('#formEditarProveedor #selectAdministradorProveedorIndirecto');

        selectDirecto.empty().prop('disabled', true).removeAttr('name');
        selectIndirecto.empty().prop('disabled', true).removeAttr('name');

        if (!tipo) {
            return;
        }

        const selectActivo = (tipo == '1') ? selectDirecto : selectIndirecto;
        selectActivo.attr('name', 'id_administrador_proveedor');
        selectActivo.append(new Option('Cargando...', '')).prop('disabled', true);

        try {
            const response = await fetch(`../../Handler/Administrador/proveedoresHandler.php?action=obtenerNegociadoresPorTipo&id_tipo_negociador=${tipo}`);
            const res = await response.json();

            selectActivo.empty();
            if (res.success && res.data && res.data.length > 0) {
                selectActivo.append(new Option('Seleccione un negociador', '', true, true));
                selectActivo.find('option:first').prop('disabled', true);
                res.data.forEach(n => {
                    const nombreCompleto = `${n.nombre_administrador} ${n.apellidos_administrador || ''}`.trim();
                    selectActivo.append(new Option(nombreCompleto, n.id_administrador));
                });
                selectActivo.prop('disabled', false);
                if (negociadorSeleccionado) {
                    selectActivo.val(negociadorSeleccionado);
                }
            } else {
                selectActivo.append(new Option('No hay negociadores disponibles', '')).prop('disabled', true);
            }
        } catch (error) {
            selectActivo.empty().append(new Option('Error al cargar negociadores', '')).prop('disabled', true);
        }
    }

    function cargarSubcategoriasEditar(catId, subCatSeleccionada = null) {
        $selectSubCatEditar.empty();

        if (!catId) {
            $selectSubCatEditar.append(new Option('Seleccione una categoría primero', '', true, true)).prop('disabled', true);
            return;
        }

        $selectSubCatEditar.append(new Option('Seleccione una sub-categoría', '', true, true));
        $selectSubCatEditar.find('option:first').prop('disabled', true);

        const filtradas = todasLasSubcategoriasEditar.filter(`[data-categoria="${catId}"]`);
        if (filtradas.length > 0) {
            $selectSubCatEditar.append(filtradas.clone());
            $selectSubCatEditar.prop('disabled', false);
            if (subCatSeleccionada) {
                $selectSubCatEditar.val(subCatSeleccionada);
            }
        } else {
            $selectSubCatEditar.append(new Option('No hay sub-categorías disponibles', '', true, true)).prop('disabled', true);
        }
    }

    function cargarSubcategoriasServicioSuministroEditar(catId, subCatSeleccionada = null) {
        $selectSubCatServEditar.empty();

        if (!catId) {
            $selectSubCatServEditar.append(new Option('Seleccione una categoría primero', '', true, true)).prop('disabled', true);
            return;
        }

        $selectSubCatServEditar.append(new Option('Seleccione una sub-categoría', '', true, true));
        $selectSubCatServEditar.find('option:first').prop('disabled', true);

        const filtradas = todasLasSubcategoriasServEditar.filter(`[data-categoria="${catId}"]`);
        if (filtradas.length > 0) {
            $selectSubCatServEditar.append(filtradas.clone());
            $selectSubCatServEditar.prop('disabled', false);
            if (subCatSeleccionada) {
                $selectSubCatServEditar.val(subCatSeleccionada);
            }
        } else {
            $selectSubCatServEditar.append(new Option('No hay sub-categorías disponibles', '', true, true)).prop('disabled', true);
        }
    }

    async function llenarSelectsDatosProveedor() {
        let selectDirecto = $('#formEditarProveedor #selectAdministradorProveedorDirecto');
        let selectIndirecto = $('#formEditarProveedor #selectAdministradorProveedorIndirecto');
        selectDirecto.empty().prop('disabled', true).removeAttr('name');
        selectIndirecto.empty().prop('disabled', true).removeAttr('name');
        await actualizarContadorCorreosEditar();
    }

    async function llenarDatosProveedorEditar() {
        const responseProveedor = JSON.parse(sessionStorage.getItem("proveedorEditar") || "{}");

        if (!responseProveedor || !responseProveedor.success) {
            notify('danger', 'Error al obtener los datos del proveedor: ' + (responseProveedor?.message || ''));
            return;
        }

        const proveedor = responseProveedor.data;

        Object.entries(proveedor).forEach(([key, value]) => {
            const $campo = $(`#formEditarProveedor [name="${key}"]`);
            if ($campo.length) {
                if ($campo.is(':checkbox')) {
                    $campo.prop('checked', value == 1).trigger('change');
                } else if (!$campo.is(':file')) {
                    $campo.val(value ?? '').trigger('input');
                }
            }
        });

        if (proveedor.correos && Array.isArray(proveedor.correos)) {
            const listaCorreos = proveedor.correos
                .map(c => (typeof c === 'object' && c !== null ? c.correo : c))
                .filter(Boolean)
                .join(', ');
            $('#formEditarProveedor #inputCorreosProveedor').val(listaCorreos);
        }
        await actualizarContadorCorreosEditar();

        if (proveedor.logo_proveedor) {
            $('#formEditarProveedor #logoPreview').attr('src', '../../../../../' + proveedor.logo_proveedor).removeClass('d-none');
            $('#formEditarProveedor #defaultLogoIcon').addClass('d-none');
        } else {
            $('#formEditarProveedor #logoPreview').addClass('d-none').attr('src', '');
            $('#formEditarProveedor #defaultLogoIcon').removeClass('d-none');
        }

        if (proveedor.id_tipo_proveedor) {
            await cargarNegociadoresPorTipoEditar(proveedor.id_tipo_proveedor, proveedor.id_administrador_proveedor);
        }
        if (proveedor.id_tipo_proveedor == 1) {
            $('#formEditarProveedor #seccionIndirecto, #formEditarProveedor #seccionServiciosSuministros').hide();
            $('#formEditarProveedor #seccionDirecto').show();

            await initChoicesCommodities();
            const commoditiesList = proveedor.commodities || [];
            if (choicesCommodities && commoditiesList.length > 0) {
                const ids = commoditiesList.map(val => (typeof val === 'object' && val !== null ? val.id_commodity : val)).filter(Boolean).map(String);
                choicesCommodities.setChoiceByValue(ids);
            }
        } else if (proveedor.id_tipo_proveedor == 2) {
            $('#formEditarProveedor #seccionDirecto').hide();
            $('#formEditarProveedor #seccionIndirecto, #formEditarProveedor #seccionServiciosSuministros').show();

            if (proveedor.id_categoria_proveedor) {
                $('#formEditarProveedor #selectCategoria').val(proveedor.id_categoria_proveedor);
                await cargarSubcategoriasEditar(proveedor.id_categoria_proveedor, proveedor.id_sub_categoria_proveedor);
            }

            if (proveedor.id_categoria_servicio_suministro) {
                $('#formEditarProveedor #selectCategoriaServicioSuministro').val(proveedor.id_categoria_servicio_suministro);
                cargarSubcategoriasServicioSuministroEditar(proveedor.id_categoria_servicio_suministro, proveedor.id_sub_categoria_servicio_suministro);
            }
        }

        await actualizarContadoresTextoEditar();
    }
});
