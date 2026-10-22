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

/**
 * Alerta de confirmación con SweetAlert2 basada en el diseño del módulo Administradores.
 *
 * @param {Object|string} options - Objeto de configuración o título de la alerta
 * @param {string} [text=''] - Mensaje descriptivo (cuando options es string)
 * @param {string} [confirmButtonText='Sí, continuar'] - Texto del botón de confirmación
 * @param {string} [cancelButtonText='Cancelar'] - Texto del botón de cancelar
 * @param {string} [icon='warning'] - Tipo de ícono ('warning', 'info', 'question', 'error', 'success')
 * @returns {Promise<SweetAlertResult>}
 */
function alertaConfirmacion(options, text = '', confirmButtonText = 'Sí, continuar', cancelButtonText = 'Cancelar', icon = 'warning') {
    let config = {};

    if (typeof options === 'string') {
        config = {
            title: options,
            text: text,
            confirmButtonText: confirmButtonText,
            cancelButtonText: cancelButtonText,
            icon: icon
        };
    } else if (typeof options === 'object' && options !== null) {
        config = {
            title: options.title || '¿Está seguro?',
            text: options.text || '',
            icon: options.icon || 'warning',
            confirmButtonText: options.confirmButtonText || 'Sí, continuar',
            cancelButtonText: options.cancelButtonText || 'Cancelar',
            onConfirm: options.onConfirm
        };
    } else {
        config = {
            title: '¿Está seguro?',
            text: '',
            icon: 'warning',
            confirmButtonText: 'Sí, continuar',
            cancelButtonText: 'Cancelar'
        };
    }

    if (typeof Swal === 'undefined') {
        console.error('SweetAlert2 no está cargado. No se puede mostrar la alerta de confirmación.');
        const confirmed = confirm(`${config.title}\n${config.text}`);
        if (confirmed && typeof config.onConfirm === 'function') {
            config.onConfirm();
        }
        return Promise.resolve({ isConfirmed: confirmed, isDismissed: !confirmed });
    }

    return Swal.fire({
        title: config.title,
        text: config.text,
        icon: config.icon,
        showCancelButton: true,
        confirmButtonText: config.confirmButtonText,
        cancelButtonText: config.cancelButtonText,
        customClass: {
            confirmButton: 'swal-btn-confirm',
            cancelButton: 'swal-btn-cancel'
        }
    }).then((result) => {
        if (result.isConfirmed && typeof config.onConfirm === 'function') {
            config.onConfirm();
        }
        return result;
    });
}

// Alias para facilitar su reutilización
const confirmAlert = alertaConfirmacion;

