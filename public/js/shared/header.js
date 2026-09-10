document.addEventListener("DOMContentLoaded", function () {

    document.querySelectorAll("a.nav-link").forEach(link => {
        link.addEventListener("click", function () {
            if (this.hostname === window.location.hostname) {
                mostrarCarga();
            }
        });
    });

    let isLoggingOut = false;
    document.querySelectorAll('#BtnCerrarSesion, #BtnCerrarSesionMenu').forEach(btn => {
        btn.addEventListener('click', async function (e) {
            e.preventDefault();

            if (isLoggingOut) return;
            isLoggingOut = true;

            mostrarCarga();

            try {
                const response = await fetch('../../Handler/Auth/logoutHandler.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.status === 'success') {
                    window.location.href = data.redirect;
                } else {
                    ocultarCarga();
                    console.error('Error al cerrar sesión:', data.message);
                    isLoggingOut = false;
                }
            } catch (error) {
                ocultarCarga();
                console.error('Error en la petición:', error);
                isLoggingOut = false;
            }
        });
    });

});

window.toggleSidebar = function () {
    var sidebar = document.getElementById('sidebar');
    var content = document.querySelector('.content');
    if (sidebar && content) {
        sidebar.classList.toggle('collapsed');
        content.classList.toggle('expanded');
    }
};
