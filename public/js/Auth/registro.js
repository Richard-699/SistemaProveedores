document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('form_proveedor');
    if (!form) return;

    const passwordInput = document.getElementById('inputPassword');
    const confirmPasswordInput = document.getElementById('confirmPassword');
    const passwordToggle = document.getElementById('passwordToggle');
    const confirmPasswordToggle = document.getElementById('confirmPasswordToggle');
    const submitBtn = document.getElementById('btningresar');

    const selectArea = document.getElementById('id_area_usuario');
    const grupoTipoNegociador = document.getElementById('grupoTipoNegociador');
    const selectTipoNegociador = document.getElementById('id_tipo_negociador');

    if (selectArea) {
        selectArea.addEventListener('change', function () {
            const selectedText = selectArea.options[selectArea.selectedIndex]?.text?.toLowerCase() || '';
            const esNegociacion = this.value === '1' || selectedText.includes('negociaci');

            if (esNegociacion && grupoTipoNegociador && selectTipoNegociador) {
                grupoTipoNegociador.classList.remove('d-none');
            } else if (grupoTipoNegociador && selectTipoNegociador) {
                grupoTipoNegociador.classList.add('d-none');
                selectTipoNegociador.value = '';
            }
        });
    }

    if (passwordInput && confirmPasswordInput) {
        passwordInput.addEventListener('input', updateToggleIcons);
        confirmPasswordInput.addEventListener('input', updateToggleIcons);
    }

    function updateToggleIcons() {
        if (passwordToggle) {
            passwordToggle.classList.toggle('hidden', passwordInput.value.length === 0);
        }
        if (confirmPasswordToggle) {
            confirmPasswordToggle.classList.toggle('hidden', confirmPasswordInput.value.length === 0);
        }
    }

    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        const selectedAreaText = selectArea?.options[selectArea.selectedIndex]?.text?.toLowerCase() || '';
        const esNegociacion = selectArea?.value === '1' || selectedAreaText.includes('negociaci');
        if (esNegociacion && selectTipoNegociador && !selectTipoNegociador.value) {
            notify('error', 'Por favor selecciona si eres un negociador directo o indirecto.');
            selectTipoNegociador.focus();
            return;
        }

        try {
            const btn = document.getElementById('btningresar');
            if (btn) btn.disabled = true;
            mostrarCarga();
            const formData = new FormData(this);

            const targetUrl = '../../Handler/Auth/registroHandler.php?action=registrarAdministrador';

            const response = await fetch(targetUrl, {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            await ocultarCarga();
            if (btn) btn.disabled = false;

            if (result.status === 'success') {
                notify('success', result.message || 'Registro exitoso. Debe esperar a que su solicitud de registro sea aprobada.');
                form.reset();
                setTimeout(() => window.location.href = 'login.php', 2000);
            } else {
                notify('error', result.message);
            }
        } catch (error) {
            await ocultarCarga();
            const btn = document.getElementById('btningresar');
            if (btn) btn.disabled = false;
            console.error(error);
            notify('error', 'Ocurrió un error inesperado. Inténtalo de nuevo.');
        }
    });
});


function togglePassword(inputId) {
    var passwordInput = document.getElementById(inputId);
    if (!passwordInput) return;

    var type = passwordInput.type === 'password' ? 'text' : 'password';
    passwordInput.type = type;

    var toggleId = inputId === 'inputPassword' ? 'passwordToggle' : inputId + 'Toggle';
    var iconContainer = document.querySelector("#" + toggleId);

    if (iconContainer && typeof AppIcons !== 'undefined') {
        if (type === 'text') {
            iconContainer.innerHTML = AppIcons.eyeSlash;
        } else {
            iconContainer.innerHTML = AppIcons.eye;
        }
    }
}