$(document).ready(async function () {
    await llenarDatosPartNumberEditar();

    $('#formEditarPartNumber #inputImagenPartNumber').on('change', async function () {
        const file = this.files[0];
        if (file) {
            if (!file.type.match('image.*')) {
                notify('danger', 'Por favor selecciona un archivo de imagen válido (PNG, JPG o WEBP).');
                this.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = async function (e) {
                $('#formEditarPartNumber #partNumberPreview').attr('src', e.target.result).removeClass('d-none');
                $('#formEditarPartNumber #defaultPartNumberIcon').addClass('d-none');
            };
            reader.readAsDataURL(file);
        }
    });

    $('#formEditarPartNumber #inputPorcentajeBom').on('input', async function () {
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

    $('#textareaDescripcion').on('input keyup change', async function () {
        const len = $(this).val().length;
        $('#contadorDescripcion').text(`${len} / 300 caracteres`);
    });

    $('#formEditarPartNumber').on('submit', async function (e) {
        e.preventDefault();

        const btn = document.getElementById('btnEditarPartNumber');
        mostrarCarga();

        try {
            if (btn) btn.disabled = true;

            const form = this;
            const formData = new FormData(form);
            const action = 'editarPartNumber';
            formData.append('action', action);

            const response = await fetch('../../Handler/Administrador/partNumbersHandler.php', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            await ocultarCarga();
            if (btn) btn.disabled = false;

            if (result.success) {
                notify('success', 'Part Number actualizado correctamente.');
                Fancybox.close();
            } else {
                notify('danger', result.message);
            }
        } catch (error) {
            await ocultarCarga();
            if (btn) btn.disabled = false;
            notify('danger', 'Ocurrió un error al procesar la solicitud.');
        }
    });

    async function llenarDatosPartNumberEditar() {
        const raw = sessionStorage.getItem('partNumberEditar');
        if (!raw) return;

        const data = JSON.parse(raw);

        $('#formEditarPartNumber').find('input:not([type="file"]), select, textarea').each(function () {
            const name = this.name;
            const val = data[name] ?? (name === 'imagen_partnumber_actual' ? data.imagen_partnumber : null);
            if (val !== undefined && val !== null) {
                $(this).val(val).trigger('input');
            }
        });

        if (data.imagen_partnumber && data.imagen_partnumber.trim() !== '') {
            let src = data.imagen_partnumber;
            if (!src.startsWith('http') && !src.startsWith('../') && !src.startsWith('/')) {
                src = '../../../../../' + src;
            }
            $('#formEditarPartNumber #partNumberPreview').attr('src', src).removeClass('d-none');
            $('#formEditarPartNumber #defaultPartNumberIcon').addClass('d-none');
        } else {
            $('#formEditarPartNumber #partNumberPreview').addClass('d-none').attr('src', '');
            $('#formEditarPartNumber #defaultPartNumberIcon').removeClass('d-none');
        }
    }
});
