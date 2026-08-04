
function notify(type, message) {
    /* ── Configuración por tipo ── */
    const config = {
        error: {
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
    };

    const current = config[type] || config.alert;


    /* ── Calcular duración dinámica según longitud del mensaje ── */
    const BASE_MS = 2000;   // Tiempo base mínimo reducido a 2 segundos
    const MS_PER_CHAR = 30;    // ms adicional por cada carácter reducido a 30
    const MAX_MS = 5000;   // Tope máximo reducido a 5 segundos
    const delay = Math.min(Math.max(BASE_MS, BASE_MS + message.length * MS_PER_CHAR), MAX_MS);

    /* ── Mostrar notificación con Bootstrap Notify ── */
    $.notify(
        {
            icon: current.icon,
            message: message,
        },
        {
            type: current.alertType,
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
                'style="max-width: 400px; width: max-content; border-radius: 4px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); font-size: 15px; font-weight: 500; padding: 12px 20px; display: inline-flex; align-items: center; justify-content: flex-start; z-index: 1055; margin: 10px;">' +
                '<span data-notify="icon" style="margin-right: 12px; font-size: 20px;"></span> ' +
                '<span data-notify="message" style="margin-right: 15px;">{2}</span>' +
                '<button type="button" aria-hidden="true" class="close" data-notify="dismiss" ' +
                'style="background: none; border: none; color: inherit; font-size: 20px; opacity: 0.8; cursor: pointer; padding: 0; line-height: 1;">&times;</button>' +
                '</div>',
        }
    );
}
