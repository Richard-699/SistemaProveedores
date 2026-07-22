document.addEventListener('DOMContentLoaded', () => {
    // Inject icons
    const iconUser = document.getElementById('icon-user');
    const iconLock = document.getElementById('icon-lock');
    const iconEye = document.getElementById('icon-eye');
    
    if (iconUser && typeof AppIcons !== 'undefined') iconUser.innerHTML = AppIcons.user;
    if (iconLock && typeof AppIcons !== 'undefined') iconLock.innerHTML = AppIcons.lock;
    if (iconEye && typeof AppIcons !== 'undefined') iconEye.innerHTML = AppIcons.eyeSlash;

    document.getElementById('loginForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        formData.append('btningresar', 'Ingresar');
        
        if (typeof mostrarCarga === 'function') mostrarCarga();
        const btn = document.getElementById('btningresar');
        if (btn) btn.disabled = true;
        
        fetch('../../Handler/Auth/loginHandler.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (typeof ocultarCarga === 'function') ocultarCarga();
            if (btn) btn.disabled = false;
            
            if (data.status === 'success') {
                window.location.href = data.redirect;
            } else {
                if (typeof showAlert === 'function') {
                    showAlert(data.message, 'error');
                } else {
                    Swal.fire({icon: 'error', title: 'Oops...', text: data.message});
                }
            }
        })
        .catch(err => {
            if (typeof ocultarCarga === 'function') ocultarCarga();
            if (btn) btn.disabled = false;
            console.error(err);
        });
    });

    const passwordInput = document.getElementById('inputPassword');
    const passwordToggle = document.getElementById('passwordToggle');

    if (passwordInput && passwordToggle) {
        passwordInput.addEventListener('input', togglePasswordIcon);
        
        // Add click listener since the onclick attribute might not be fully reliable with new SVGs
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
