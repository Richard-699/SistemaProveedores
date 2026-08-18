document.addEventListener('DOMContentLoaded', () => {

    const reestablecerForm = document.getElementById('reestablecerForm');
    const iconUser = document.getElementById('icon-user');

    if (iconUser && typeof AppIcons !== 'undefined') {
        iconUser.innerHTML = AppIcons.user;
    }

    if (reestablecerForm) {

        reestablecerForm.addEventListener('submit', async function (e) {
            e.preventDefault();

            try {
                const btnRecuperar = document.getElementById('btnRecuperar');
                if (btnRecuperar) btnRecuperar.disabled = true;
                mostrarCarga();

                const formData = new FormData(this);
                formData.append('action', 'sendEmail');

                const res = await fetch('../../Handler/Auth/cambiarContrasenaHandler.php', {
                    method: 'POST',
                    body: formData
                });
                const data = await res.json();

                await ocultarCarga();
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
                await ocultarCarga();
                const btnRecuperar = document.getElementById('btnRecuperar');
                if (btnRecuperar) btnRecuperar.disabled = false;
                console.error(err);
                notify('error', 'Ocurrió un error inesperado. Inténtalo de nuevo.');
            }
        });
    }

});
