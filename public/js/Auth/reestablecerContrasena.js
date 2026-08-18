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

    const cambiarContrasenaForm = document.getElementById('cambiarContrasenaForm');
    if (cambiarContrasenaForm) {
        document.getElementById('cambiarContrasenaForm').addEventListener('submit', async function (e) {
            e.preventDefault();

            if (btnCambiar.dataset.submitting === 'true') return;
            btnCambiar.dataset.submitting = 'true';

            try {
                mostrarCarga();
                btnCambiar.disabled = true;

                const formData = new FormData();
                formData.append('nuevaPassword', nuevaPassword.value);
                formData.append('confirmPassword', confirmPassword.value);

                const res = await fetch('../../Handler/Auth/reestablecerContrasenaHandler.php', {
                    method: 'POST',
                    body: formData
                });
                const data = await res.json();

                await ocultarCarga();

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
                await ocultarCarga();
                console.error(err);
                notify('error', 'Ocurrió un error en el servidor.');
                btnCambiar.disabled = false;
                btnCambiar.dataset.submitting = 'false';
            }
        });
    }

    if (nuevaPassword && passwordToggle1) {
        nuevaPassword.addEventListener('input', () => {
            passwordToggle1.style.display = nuevaPassword.value.length > 0 ? 'flex' : 'none';
        });
        passwordToggle1.addEventListener('click', () => togglePassword('nuevaPassword', 'icon-eye1'));
    }

    if (confirmPassword && passwordToggle2) {
        confirmPassword.addEventListener('input', () => {
            passwordToggle2.style.display = confirmPassword.value.length > 0 ? 'flex' : 'none';
        });
        passwordToggle2.addEventListener('click', () => togglePassword('confirmPassword', 'icon-eye2'));
    }
});

function togglePassword(inputId, iconId) {
    const passwordInput = document.getElementById(inputId);
    if (!passwordInput) return;

    const type = passwordInput.type === 'password' ? 'text' : 'password';
    passwordInput.type = type;

    // Toggle icon
    const iconEye = document.getElementById(iconId);
    if (iconEye && typeof AppIcons !== 'undefined') {
        iconEye.innerHTML = type === 'password' ? AppIcons.eyeSlash : AppIcons.eye;
    }
}
