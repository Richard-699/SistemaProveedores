document.addEventListener('DOMContentLoaded', () => {
    // Inject icons
    const iconLock1 = document.getElementById('icon-lock1');
    const iconLock2 = document.getElementById('icon-lock2');
    const iconEye1 = document.getElementById('icon-eye1');
    const iconEye2 = document.getElementById('icon-eye2');
    
    if (typeof AppIcons !== 'undefined') {
        if (iconLock1) iconLock1.innerHTML = AppIcons.lock;
        if (iconLock2) iconLock2.innerHTML = AppIcons.lock;
        if (iconEye1) iconEye1.innerHTML = AppIcons.eyeSlash;
        if (iconEye2) iconEye2.innerHTML = AppIcons.eyeSlash;
    }

    const nuevaPassword = document.getElementById('nuevaPassword');
    const confirmPassword = document.getElementById('confirmPassword');
    const passwordToggle1 = document.getElementById('passwordToggle1');
    const passwordToggle2 = document.getElementById('passwordToggle2');
    const errorSpan = document.getElementById('errorConfirmPassword');
    const btnCambiar = document.getElementById('btnCambiar');

    if (nuevaPassword && passwordToggle1) {
        nuevaPassword.addEventListener('input', () => {
            passwordToggle1.style.display = nuevaPassword.value.length > 0 ? 'flex' : 'none';
            validarContrasenas();
        });
    }

    if (confirmPassword && passwordToggle2) {
        confirmPassword.addEventListener('input', () => {
            passwordToggle2.style.display = confirmPassword.value.length > 0 ? 'flex' : 'none';
            validarContrasenas();
        });
    }

    function validarContrasenas() {
        if (nuevaPassword.value === confirmPassword.value && nuevaPassword.value.length > 0) {
            errorSpan.textContent = "";
            btnCambiar.removeAttribute("disabled");
        } else if (confirmPassword.value.length > 0) {
            errorSpan.textContent = "Las contraseñas no coinciden";
            btnCambiar.setAttribute("disabled", "disabled");
        } else {
            errorSpan.textContent = "";
            btnCambiar.setAttribute("disabled", "disabled");
        }
    }

    // --- Restablecer Contraseña Form Logic ---
    const reestablecerForm = document.getElementById('reestablecerForm');
    if (reestablecerForm) {
        if (iconUser && typeof AppIcons !== 'undefined') iconUser.innerHTML = AppIcons.user;

        reestablecerForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const isValid = validateForm([
                { field: '#correo', required: true, message: 'El correo electrónico es obligatorio.', email: true, emailMsg: 'El correo electrónico no es válido.' }
            ]);

            if (!isValid) return;

            try {
                const btnRecuperar = document.getElementById('btnRecuperar');
                if (btnRecuperar) btnRecuperar.disabled = true;
                if (typeof mostrarCarga === 'function') mostrarCarga();
                
                const formData = new FormData(this);
                formData.append('action', 'sendEmail');

                const res = await fetch('../../Handler/cambiarContrasenaHandler.php', {
                    method: 'POST',
                    body: formData
                });
                const data = await res.json();

                if (typeof ocultarCarga === 'function') ocultarCarga();
                if (btnRecuperar) btnRecuperar.disabled = false;

                if (data.status === 'success') {
                    notify('success', data.message);
                    setTimeout(() => {
                        window.location.href = 'login.php';
                    }, 2000);
                } else {
                    notify('error', data.message);
                }
            } catch (err) {
                if (typeof ocultarCarga === 'function') ocultarCarga();
                const btnRecuperar = document.getElementById('btnRecuperar');
                if (btnRecuperar) btnRecuperar.disabled = false;
                console.error(err);
                notify('error', 'Ocurrió un error inesperado. Inténtalo de nuevo.');
            }
        });
    }

    // --- Cambiar Contraseña Form Logic ---
    const cambiarContrasenaForm = document.getElementById('cambiarContrasenaForm');
    if (cambiarContrasenaForm) {
        document.getElementById('cambiarContrasenaForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const isValid = validateForm([
                { field: '#nuevaPassword', required: true, message: 'La nueva contraseña es obligatoria.', minLength: 8, minLengthMsg: 'La contraseña debe tener al menos 8 caracteres.' },
                { field: '#confirmPassword', required: true, message: 'Debes confirmar la contraseña.', match: '#nuevaPassword', matchMsg: 'Las contraseñas no coinciden.' }
            ]);

            if (!isValid) return;

            if (btnCambiar.dataset.submitting === 'true') return;
            btnCambiar.dataset.submitting = 'true';

            try {
                if (typeof mostrarCarga === 'function') mostrarCarga();
                btnCambiar.disabled = true;

                const formData = new FormData();
                formData.append('nuevaPassword', nuevaPassword.value);
                formData.append('confirmPassword', confirmPassword.value);
                formData.append('action', 'changePassword');

                const res = await fetch('../../Handler/cambiarContrasenaHandler.php', {
                    method: 'POST',
                    body: formData
                });
                const data = await res.json();

                if (typeof ocultarCarga === 'function') ocultarCarga();

                if (data.status === 'success') {
                    notify('success', data.message);
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 2000);
                } else {
                    notify('error', data.message);
                    btnCambiar.disabled = false;
                    btnCambiar.dataset.submitting = 'false';
                }
            } catch (err) {
                if (typeof ocultarCarga === 'function') ocultarCarga();
                console.error(err);
                notify('error', 'Ocurrió un error en el servidor.');
                btnCambiar.disabled = false;
                btnCambiar.dataset.submitting = 'false';
            }
        });
    }
});

window.togglePassword = function(inputId, iconId) {
    const passwordInput = document.getElementById(inputId);
    if (!passwordInput) return;
    
    const type = passwordInput.type === 'password' ? 'text' : 'password';
    passwordInput.type = type;
    
    const iconEye = document.getElementById(iconId);
    if (iconEye && typeof AppIcons !== 'undefined') {
        iconEye.innerHTML = type === 'password' ? AppIcons.eyeSlash : AppIcons.eye;
    }
}
