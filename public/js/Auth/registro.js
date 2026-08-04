document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('form_proveedor');
    if (!form) return;

    const passwordInput = document.getElementById('inputPassword');
    const confirmPasswordInput = document.getElementById('confirmPassword');
    const passwordToggle = document.getElementById('passwordToggle');
    const confirmPasswordToggle = document.getElementById('confirmPasswordToggle');
    const submitBtn = document.getElementById('btningresar');

    const selectArea = document.getElementById('id_area_usuario');
    if (selectArea) {
        async function loadAreas() {
            try {
                const res = await fetch('../../Handler/registroHandler.php?action=getAreas');
                const data = await res.json();
                if (data.status === 'success') {
                    data.data.forEach(area => {
                        const option = document.createElement('option');
                        option.value = area.id_area;
                        option.textContent = area.nombre_area;
                        selectArea.appendChild(option);
                    });
                } else {
                    console.error('Error cargando áreas:', data.message);
                }
            } catch (err) {
                console.error('Error de red al cargar áreas:', err);
            }
        }
        loadAreas();
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

        try {
            const btn = document.getElementById('btningresar');
            if (btn) btn.disabled = true;
            mostrarCarga();
            const formData = new FormData(this);

            const targetUrl = '../../Handler/registroHandler.php?action=registrarAdministrador';

            const response = await fetch(targetUrl, {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            ocultarCarga();
            if (btn) btn.disabled = false;

            if (result.status === 'success') {
                notify('success', result.message || 'Registro exitoso. Debe esperar a que su solicitud de registro sea aprobada.');
                form.reset();
                setTimeout(() => window.location.href = 'login.php', 2000);
            } else {
                notify('error', result.message);
            }
        } catch (error) {
            ocultarCarga();
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