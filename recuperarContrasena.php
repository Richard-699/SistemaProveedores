<?php
$idioma = $_GET['idioma'] ?? 'es';
$new_idioma = ucfirst($idioma);
$ruta = 'IdiomaConfig/' . $new_idioma . '.php';

if (!file_exists($ruta)) {
    $ruta = 'IdiomaConfig/Es.php';
}
include($ruta);
/** @var array $lang */
?>
<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($idioma); ?>">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Haceb Whirlpool - Cambiar Contraseña</title>
    <link rel="shortcut icon" href="./img/LogoBlanco.png" type="image/x-icon">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Estilos Personalizados idénticos a SistemaProveedores -->
    <link rel="stylesheet" href="./Estilos/Generals/estilos_recuperarContrasena.css">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

    <div class="login-container">
        <div class="login-left">
            <img src="./img/hwiLogo.png" alt="Logo Haceb Whirlpool">
        </div>
        <div class="login-right">
            <h3 class="rec-title">Cambiar Contraseña</h3>

            <form id="reestablecerForm" novalidate>
                <div class="form-group">
                    <span class="input-icon">
                        <i class="fa-solid fa-user"></i>
                    </span>
                    <input autocomplete="off" type="text" class="custom-input" id="usuario" name="usuario" placeholder="Ingresa tu usuario o correo electrónico" required>
                </div>

                <button type="submit" class="btn-success" id="btnRecuperar">
                    Continuar
                </button>

                <div class="footer-links">
                    <a href="index.php?idioma=<?php echo htmlspecialchars($idioma); ?>">Iniciar Sesión</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('reestablecerForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const usuarioInput = document.getElementById('usuario');
            const usuarioVal = usuarioInput.value.trim();

            if (!usuarioVal) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Campo requerido',
                    text: 'Por favor, ingresa tu usuario o correo electrónico.',
                    confirmButtonColor: '#0093B2'
                });
                usuarioInput.focus();
                return;
            }

            const btn = document.getElementById('btnRecuperar');
            btn.disabled = true;
            btn.textContent = 'Enviando...';

            Swal.fire({
                title: 'Enviando contraseña temporal',
                text: 'Por favor, espera un momento...',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            const formData = new FormData();
            formData.append('usuario', usuarioVal);

            fetch('Actions/Generals/recuperarContrasena.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                btn.disabled = false;
                btn.textContent = 'Continuar';

                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Contraseña Enviada!',
                        text: data.message,
                        confirmButtonText: 'Iniciar Sesión',
                        confirmButtonColor: '#0093B2'
                    }).then(() => {
                        window.location.href = 'index.php?idioma=<?php echo htmlspecialchars($idioma); ?>';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'No se pudo procesar la solicitud.',
                        confirmButtonColor: '#0093B2'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                btn.disabled = false;
                btn.textContent = 'Continuar';

                Swal.fire({
                    icon: 'error',
                    title: 'Error de conexión',
                    text: 'Ocurrió un error al comunicarse con el servidor. Intenta de nuevo.',
                    confirmButtonColor: '#0093B2'
                });
            });
        });
    </script>
</body>

</html>
