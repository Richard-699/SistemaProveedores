/**
 * Módulo de alertas global y recursivo
 * Utiliza SweetAlert2 para renderizar alertas estilo Notify (Toast) en la parte superior derecha.
 */

function showAlert(message, type = 'info', containerElement = null) {
    if (typeof Swal === 'undefined') {
        console.error('SweetAlert2 no está cargado. No se puede mostrar la alerta:', message);
        alert(message);
        return;
    }

    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

    Toast.fire({
        icon: type,
        title: message
    });
}

function clearAlert() {
    // Si hay alguna alerta de SweetAlert visible, la cerramos
    if (typeof Swal !== 'undefined') {
        Swal.close();
    }
}
