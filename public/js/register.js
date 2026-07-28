document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('form_proveedor');
    if (!form) return;

    const passwordInput = document.getElementById('inputPassword');
    const confirmPasswordInput = document.getElementById('confirmPassword');
    const passwordToggle = document.getElementById('passwordToggle');
    const confirmPasswordToggle = document.getElementById('confirmPasswordToggle');
    const submitBtn = document.getElementById('btningresar');

    // === Cargar áreas dinámicamente ===
    const selectArea = document.getElementById('id_area_usuario');
    if (selectArea) {
        fetch('../../Handler/registerHandler.php?action=getAreas')
            .then(res => res.json())
            .then(data => {
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
            })
            .catch(err => console.error('Error de red al cargar áreas:', err));
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

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        clearErrors();

        if (typeof mostrarCarga === 'function') mostrarCarga('Registrando usuario...');

        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerText = 'Procesando...';
            submitBtn.classList.add('opacity-70', 'cursor-not-allowed');
        }

        try {
            const formData = new FormData(form);
            let targetUrl = form.getAttribute('action');
            if (targetUrl && !targetUrl.includes('?action=')) {
                targetUrl += '?action=registrarUsuario';
            }

            const response = await fetch(targetUrl, {
                method: 'POST',
                body: formData
            });

            if (!response.ok) throw new Error('Error en la respuesta del servidor');

            const result = await response.json();

            if (result.status === 'success') {
                if (typeof showAlert === 'function') showAlert(result.message || 'Registro exitoso.', 'success', form);
                form.reset();
                setTimeout(() => window.location.href = 'index.php', 2000);
            } else {
                if (result.errors) {
                    for (const [field, message] of Object.entries(result.errors)) {
                        showError(field, message);
                    }
                }
                if (result.message) {
                    if (typeof showAlert === 'function') showAlert(result.message, 'error', form);
                }
            }
        } catch (error) {
            if (typeof showAlert === 'function') showAlert(`No se pudo conectar con el servidor: ${error.message}`, 'error', form);
            console.error('Error:', error);
        } finally {
            if (typeof ocultarCarga === 'function') ocultarCarga();

            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerText = 'Registrarse';
                submitBtn.classList.remove('opacity-70', 'cursor-not-allowed');
            }
        }
    });

    function showError(inputId, message) {
        const inputElement = document.getElementById(inputId);
        if (!inputElement) return;

        inputElement.classList.remove('border-hwi-border');
        inputElement.classList.add('input-error');

        const existingError = document.getElementById('error-' + inputId);
        if (existingError) {
            existingError.innerText = message;
            return;
        }

        const errorElement = document.createElement('p');
        errorElement.id = 'error-' + inputId;
        errorElement.className = 'error-message';
        errorElement.innerText = message;
        inputElement.parentNode.insertAdjacentElement('afterend', errorElement);
    }

    function clearErrors() {
        document.querySelectorAll('.error-message').forEach(el => el.remove());

        document.querySelectorAll('input, select').forEach(input => {
            input.classList.remove('input-error');
            input.classList.add('border-hwi-border');
        });

        if (typeof clearAlert === 'function') {
            clearAlert();
        } else {
            const oldAlert = document.getElementById('global-alert');
            if (oldAlert) oldAlert.remove();
        }
    }
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