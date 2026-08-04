
(function () {
    if (document.getElementById('notify-animation-styles')) return;

    const style = document.createElement('style');
    style.id = 'notify-animation-styles';
    style.textContent = `
        /* ── Animaciones para Bootstrap Notify ── */
        .animated {
            animation-duration: 0.4s;
            animation-fill-mode: both;
        }
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translate3d(0, -30px, 0);
            }
            to {
                opacity: 1;
                transform: translate3d(0, 0, 0);
            }
        }
        .fadeInDown { animation-name: fadeInDown; }

        @keyframes fadeOutUp {
            from {
                opacity: 1;
                transform: translate3d(0, 0, 0);
            }
            to {
                opacity: 0;
                transform: translate3d(0, -30px, 0);
            }
        }
        .fadeOutUp { animation-name: fadeOutUp; }
    `;
    document.head.appendChild(style);
})();
