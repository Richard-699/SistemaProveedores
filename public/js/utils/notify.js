
let _lastNotifyTracker = { message: '', type: '', time: 0 };

function notify(type, message) {
    if (!message) return;

    // Evitar que se muestren notificaciones idénticas repetidas en menos de 1.2 segundos
    const now = Date.now();
    if (_lastNotifyTracker.message === message && _lastNotifyTracker.type === type && (now - _lastNotifyTracker.time) < 1200) {
        return;
    }
    _lastNotifyTracker = { message: message, type: type, time: now };

    /* ── Configuración por tipo ── */
    const config = {
        error: {
            icon: 'fa fa-times-circle',
            alertType: 'danger',
        },
        danger: {
            icon: 'fa fa-times-circle',
            alertType: 'danger',
        },
        success: {
            icon: 'fa fa-check-circle',
            alertType: 'success',
        },
        alert: {
            icon: 'fa fa-exclamation-triangle',
            alertType: 'warning',
        },
        warning: {
            icon: 'fa fa-exclamation-triangle',
            alertType: 'warning',
        },
    };

    const current = config[type] || config.alert;

    /* ── Calcular duración dinámica según longitud del mensaje ── */
    const BASE_MS = 1500;
    const MS_PER_CHAR = 20;
    const MAX_MS = 3500;
    const delay = Math.min(Math.max(BASE_MS, BASE_MS + (message ? message.length : 0) * MS_PER_CHAR), MAX_MS);

    /* ── Mostrar notificación con Bootstrap Notify si está disponible ── */
    if (typeof $ !== 'undefined' && typeof $.notify === 'function') {
        $.notify(
            {
                icon: current.icon,
                message: message,
            },
            {
                type: current.alertType,
                z_index: 99999,
                placement: {
                    from: 'top',
                    align: 'right',
                },
                delay: delay,
                timer: 1000,
                allow_dismiss: true,
                showProgressbar: false,
                animate: {
                    enter: 'animated fadeInDown',
                    exit: 'animated fadeOutUp',
                },
                template:
                    '<div data-notify="container" class="alert text-bg-{0} border-0" role="alert" ' +
                    'style="max-width: 400px; width: max-content; border-radius: 4px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); font-size: 15px; font-weight: 500; padding: 12px 20px; display: inline-flex; align-items: center; justify-content: flex-start; z-index: 99999; margin: 10px;">' +
                    '<span data-notify="icon" style="margin-right: 12px; font-size: 20px;"></span> ' +
                    '<span data-notify="message" style="margin-right: 15px;">{2}</span>' +
                    '<button type="button" aria-hidden="true" class="close" data-notify="dismiss" ' +
                    'style="background: none; border: none; color: inherit; font-size: 20px; opacity: 0.8; cursor: pointer; padding: 0; line-height: 1;">&times;</button>' +
                    '</div>',
            }
        );
        return;
    }

    /* ── Renderizado directo con el mismo diseño de Bootstrap Notify si $.notify no está presente ── */
    let container = document.getElementById('bootstrap-notify-native-wrapper');
    if (!container) {
        container = document.createElement('div');
        container.id = 'bootstrap-notify-native-wrapper';
        container.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 99999; display: flex; flex-direction: column; gap: 10px; pointer-events: none;';
        document.body.appendChild(container);
    }

    const toast = document.createElement('div');
    toast.setAttribute('data-notify', 'container');
    toast.className = `alert text-bg-${current.alertType} border-0 animated fadeInDown`;
    toast.style.cssText = 'max-width: 400px; width: max-content; border-radius: 4px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); font-size: 15px; font-weight: 500; padding: 12px 20px; display: inline-flex; align-items: center; justify-content: flex-start; margin: 0; pointer-events: auto;';
    toast.innerHTML = `
        <span data-notify="icon" style="margin-right: 12px; font-size: 20px;"><i class="${current.icon}"></i></span>
        <span data-notify="message" style="margin-right: 15px;">${message}</span>
        <button type="button" aria-hidden="true" class="close" data-notify="dismiss" style="background: none; border: none; color: inherit; font-size: 20px; opacity: 0.8; cursor: pointer; padding: 0; line-height: 1;">&times;</button>
    `;

    toast.querySelector('.close').onclick = () => {
        toast.classList.remove('fadeInDown');
        toast.classList.add('fadeOutUp');
        setTimeout(() => toast.remove(), 400);
    };

    container.appendChild(toast);

    setTimeout(() => {
        if (toast.parentElement) {
            toast.classList.remove('fadeInDown');
            toast.classList.add('fadeOutUp');
            setTimeout(() => toast.remove(), 400);
        }
    }, delay);
}
