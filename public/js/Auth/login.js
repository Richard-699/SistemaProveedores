document.addEventListener('DOMContentLoaded', () => {

    const iconUser = document.getElementById('icon-user');
    const iconLock = document.getElementById('icon-lock');
    const iconEye = document.getElementById('icon-eye');

    if (iconUser && typeof AppIcons !== 'undefined') iconUser.innerHTML = AppIcons.user;
    if (iconLock && typeof AppIcons !== 'undefined') iconLock.innerHTML = AppIcons.lock;
    if (iconEye && typeof AppIcons !== 'undefined') iconEye.innerHTML = AppIcons.eyeSlash;

    document.getElementById('loginForm').addEventListener('submit', async function (e) {
        e.preventDefault();

        const isValid = validateForm([
            { field: '#usuario',       required: true, message: 'El usuario es obligatorio.' },
            { field: '#inputPassword', required: true, message: 'La contraseña es obligatoria.' },
        ]);
        if (!isValid) return;

        try {
            const btn = document.getElementById('btningresar');
            if (btn) btn.disabled = true;
            mostrarCarga();
            const formData = new FormData(this);

            const res = await fetch('../../Handler/Auth/loginHandler.php', {
                method: 'POST',
                body: formData
            });
            const data = await res.json();

            await ocultarCarga();
            if (btn) btn.disabled = false;

            if (data.status === 'success') {
                const sessionData = data.data;
                if (sessionData.is_temporal == 1) {
                    window.location.href = 'reestablecerContrasena.php';
                } else {
                    window.location.href = sessionData.redirect || (sessionData.is_admin ? '../Administrador/index.php' : '../Supplier/index.php');
                }
            } else {
                notify('error', data.message);
            }
        } catch (err) {
            await ocultarCarga();
            const btn = document.getElementById('btningresar');
            if (btn) btn.disabled = false;
            console.error(err);
            notify('error', 'Ocurrió un error inesperado. Inténtalo de nuevo.');
        }
    });

    const passwordInput = document.getElementById('inputPassword');
    const passwordToggle = document.getElementById('passwordToggle');

    if (passwordInput && passwordToggle) {
        passwordInput.addEventListener('input', togglePasswordIcon);

        passwordToggle.addEventListener('click', () => togglePassword('inputPassword'));

        function togglePasswordIcon() {
            passwordToggle.style.display = passwordInput.value.length > 0 ? 'flex' : 'none';
        }
    }

});

function togglePassword(inputId) {
    const passwordInput = document.getElementById(inputId);
    if (!passwordInput) return;

    const type = passwordInput.type === 'password' ? 'text' : 'password';
    passwordInput.type = type;

    // Toggle icon
    const iconEye = document.getElementById('icon-eye');
    if (iconEye && typeof AppIcons !== 'undefined') {
        iconEye.innerHTML = type === 'password' ? AppIcons.eyeSlash : AppIcons.eye;
    }
}
