
function validateForm(rules) {
    for (const rule of rules) {
        const el = document.querySelector(rule.field);
        if (!el) continue;

        const value = (el.value || '').trim();

        /* Campo obligatorio */
        if (rule.required && !value) {
            notify('error', rule.message || 'Este campo es obligatorio.');
            el.focus();
            return false;
        }

        /* Solo validar reglas adicionales si hay valor */
        if (!value) continue;

        /* Longitud mínima */
        if (rule.minLength && value.length < rule.minLength) {
            notify('error', rule.minLengthMsg || 'El campo debe tener al menos ' + rule.minLength + ' caracteres.');
            el.focus();
            return false;
        }

        /* Patrón (regex) */
        if (rule.pattern && !rule.pattern.test(value)) {
            notify('error', rule.patternMsg || 'El campo no cumple con el formato requerido.');
            el.focus();
            return false;
        }

        /* Formato de email */
        if (rule.email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
            notify('error', rule.emailMsg || 'El correo electrónico no es válido.');
            el.focus();
            return false;
        }

        /* Coincidencia con otro campo */
        if (rule.match) {
            const matchEl = document.querySelector(rule.match);
            if (matchEl && value !== matchEl.value.trim()) {
                notify('error', rule.matchMsg || 'Los campos no coinciden.');
                el.focus();
                return false;
            }
        }
    }

    return true;
}
