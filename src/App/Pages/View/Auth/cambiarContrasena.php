<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Haceb Whirlpool - Cambiar Contraseña</title>
    <link rel="shortcut icon" href="../../../../../public/img/LogoBlanco.png" type="image/x-icon">
    <!-- Dependencias de estilos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Estilos Personalizados -->
    <link rel="stylesheet" href="../../../../../public/css/Auth/login.css">
</head>

<body>

    <div class="login-container">
        <div class="login-left">
            <img src="../../../../../public/img/hwiLogo.png" alt="Logo Haceb Whirlpool">
        </div>
        <div class="login-right">
            <!-- Formulario de Restablecer (Pedir Correo) -->
            <h3 class="rec-title">Cambiar Contraseña</h3>

            <form id="reestablecerForm" novalidate>
                <div class="form-group">
                    <span class="input-icon" id="icon-user"></span>
                    <input autocomplete="off" type="text" class="custom-input" id="usuario" name="usuario" placeholder="Ingresa tu usuario o correo electrónico" required>
                </div>

                <button type="submit" class="btn-success" id="btnRecuperar">
                    Continuar
                </button>

                <div class="footer-links">
                    <a href="login.php">Iniciar Sesión</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Dependencias -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Utilidades -->
    <script src="../../../../../public/js/utils/icons.js"></script>
    <script src="../../../../../public/js/utils/spinner.js"></script>
    <script src="../../../../../public/js/utils/bootstrap-notify.min.js"></script>
    <script src="../../../../../public/js/utils/notify-animations.js"></script>
    <script src="../../../../../public/js/utils/notify.js"></script>
    <script src="../../../../../public/js/utils/validateForm.js"></script>
    <script src="../../../../../public/js/Auth/cambiarContrasena.js"></script>
</body>

</html>
