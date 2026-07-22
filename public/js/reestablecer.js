document.addEventListener('DOMContentLoaded', () => {
    // Inject icons
    const iconUser = document.getElementById('icon-user');
    if (iconUser && typeof AppIcons !== 'undefined') iconUser.innerHTML = AppIcons.user;

    document.getElementById('reestablecerForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const correo = document.getElementById('correo').value;
        const btn = document.getElementById('btnRecuperar');
        if (typeof mostrarCarga === 'function') mostrarCarga();
        btn.disabled = true;

        const formData = new FormData();
        formData.append('correo', correo);
        formData.append('btnRecuperar', 'Recuperar');

        fetch('../../Handler/Auth/reestablecerHandler.php', {
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
                    window.location.href = 'index.php';
                }, 2000);
            } else {
                if (typeof showAlert === 'function') {
                    showAlert(data.message, 'error');
                }
                btn.disabled = false;
            }
        })
        .catch(err => {
            if (typeof ocultarCarga === 'function') ocultarCarga();
            console.error(err);
            if (typeof showAlert === 'function') {
                showAlert('Ocurrió un error en el servidor.', 'error');
            }
            btn.disabled = false;
        });
    });
});
