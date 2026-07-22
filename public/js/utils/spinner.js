
function mostrarCarga() {

    let overlay = document.getElementById('global-spinner-overlay');
    if (overlay) {
        overlay.style.opacity = '1';
        return;
    }

    if (!document.getElementById('global-spinner-styles')) {
        const style = document.createElement('style');
        style.id = 'global-spinner-styles';
        style.innerHTML = `
            .spinner-overlay {
                position: fixed;
                top: 0; left: 0; right: 0; bottom: 0;
                z-index: 9999;
                display: flex;
                align-items: center;
                justify-content: center;
                background-color: rgba(0, 0, 0, 0.4);
                backdrop-filter: blur(5px);
                -webkit-backdrop-filter: blur(5px);
                opacity: 0;
                transition: opacity 0.3s ease;
            }
            .spinner-box {
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .spinner-svg {
                animation: spin 1s linear infinite;
                height: 3rem;
                width: 3rem;
                color: #1D92B2; /* Color principal de HWI */
            }
            @keyframes spin {
                from { transform: rotate(0deg); }
                to { transform: rotate(360deg); }
            }
        `;
        document.head.appendChild(style);
    }

    overlay = document.createElement('div');
    overlay.id = 'global-spinner-overlay';
    overlay.className = 'spinner-overlay';

    const spinnerBox = document.createElement('div');
    spinnerBox.className = 'spinner-box';

    spinnerBox.innerHTML = `
        <svg class="spinner-svg" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" opacity="0.25"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
    `;

    overlay.appendChild(spinnerBox);
    document.body.appendChild(overlay);

    // Pequeño timeout para asegurar que el transition se aplique
    setTimeout(() => {
        overlay.style.opacity = '1';
    }, 10);
}

function ocultarCarga() {
    // Agregamos un retraso artificial de 600ms para que se alcance a ver el spinner
    setTimeout(() => {
        const overlay = document.getElementById('global-spinner-overlay');
        if (overlay) {
            overlay.style.opacity = '0';
            // Esperar a que termine la transición de opacidad (300ms) para removerlo del DOM
            setTimeout(() => {
                if (overlay && overlay.style.opacity === '0') {
                    overlay.remove();
                }
            }, 300);
        }
    }, 600);
}
