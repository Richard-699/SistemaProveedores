/**
 * Módulo de logout global.
 * Intercepta todos los links de cerrar sesión y realiza el logout vía fetch
 * mostrando el spinner de carga mientras se procesa.
 */
document.addEventListener('DOMContentLoaded', () => {
    document.addEventListener('click', function (e) {
        const logoutLink = e.target.closest('.cerrar-sesion, [data-logout]');
        if (!logoutLink) return;

        e.preventDefault();

        if (typeof mostrarCarga === 'function') mostrarCarga();

        // Construir la ruta al handler según la profundidad relativa del link original
        const currentHref = logoutLink.getAttribute('href') || '';
        // Calcular la ruta base relativa al handler
        const basePath = currentHref.replace(/Actions\/Generals\/cerrarsesion\.php.*$/, '');
        const handlerUrl = basePath + 'src/App/Pages/Handler/logoutHandler.php';

        fetch(handlerUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                // Redirigir al login después de 2 segundos para que el spinner sea visible
                setTimeout(() => {
                    window.location.href = basePath + 'src/App/Pages/View/Auth/login.php';
                }, 2000);
            } else {
                if (typeof ocultarCarga === 'function') ocultarCarga();
                if (typeof showAlert === 'function') {
                    showAlert(data.message || 'Error al cerrar sesión', 'error');
                } else {
                    alert(data.message || 'Error al cerrar sesión');
                }
            }
        })
        .catch(err => {
            if (typeof ocultarCarga === 'function') ocultarCarga();
            console.error('Error al cerrar sesión:', err);
            // En caso de error de red, redirigir de forma directa como fallback
            window.location.href = currentHref;
        });
    });
});
