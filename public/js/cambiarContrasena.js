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

    document.getElementById('cambiarContrasenaForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (nuevaPassword.value !== confirmPassword.value) {
            return;
        }

        if (nuevaPassword.value.length < 8) {
            Swal.fire({icon: 'error', title: 'Error', text: 'La contraseña debe tener al menos 8 caracteres.'});
            return;
        }

        if (typeof mostrarCarga === 'function') mostrarCarga();
        btnCambiar.disabled = true;

        const formData = new FormData();
        formData.append('nuevaPassword', nuevaPassword.value);
        formData.append('btnCambiar', 'Guardar');

        fetch('../../Handler/Auth/changePasswordHandler.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (typeof ocultarCarga === 'function') ocultarCarga();
            if (data.status === 'success') {
                if (typeof showAlert === 'function') {
                    showAlert(data.message, 'success');
                }
                setTimeout(() => {
                    window.location.href = data.redirect;
                }, 2000);
            } else {
                if (typeof showAlert === 'function') {
                    showAlert(data.message, 'error');
                }
                btnCambiar.disabled = false;
            }
        })
        .catch(err => {
            if (typeof ocultarCarga === 'function') ocultarCarga();
            console.error(err);
            if (typeof showAlert === 'function') {
                showAlert('Ocurrió un error en el servidor.', 'error');
            }
            btnCambiar.disabled = false;
        });
    });
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
