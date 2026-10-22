
document.addEventListener('DOMContentLoaded', () => {
    document.addEventListener('click', async function (e) {
        const logoutLink = e.target.closest('.cerrar-sesion, [data-logout]');
        if (!logoutLink) return;

        e.preventDefault();

        if (typeof mostrarCarga === 'function') mostrarCarga();

        const currentHref = logoutLink.getAttribute('href') || '';
        const basePath = currentHref.replace(/Actions\/Generals\/cerrarsesion\.php.*$/, '');
        const handlerUrl = basePath + 'src/App/Pages/Handler/Auth/logoutHandler.php';

        try {
            const res = await fetch(handlerUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' }
            });
            const data = await res.json();

            if (data.status === 'success') {
                setTimeout(() => {
                    window.location.href = basePath + 'src/App/Pages/View/Auth/login.php';
                }, 2000);
            } else {
                if (typeof ocultarCarga === 'function') {
                    await ocultarCarga();
                }
                notify('error', data.message || 'Error al cerrar sesión');
            }
        } catch (err) {
            if (typeof ocultarCarga === 'function') {
                await ocultarCarga();
            }
            console.error('Error al cerrar sesión:', err);
            window.location.href = currentHref;
        }
    });
});
