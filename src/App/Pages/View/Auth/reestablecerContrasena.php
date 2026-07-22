<?php
$idioma = $_GET['idioma'] ?? 'es';
$new_idioma = ucfirst($idioma);
$ruta = '../../../../../IdiomaConfig/' . $new_idioma . '.php';
include($ruta);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Haceb Whirlpool - Reestablecer Contraseña</title>
    <link rel="shortcut icon" href="../../../../../img/LogoBlanco.png" type="image/x-icon">
    <link rel="stylesheet" href="../../../../../public/css/login.css">
</head>

<body>

    <div class="login-container">
        <div class="login-left">
            <img src="../../../../../img/hwiLogo.png" alt="Logo Haceb Whirlpool">
        </div>
        <div class="login-right">
            <h3 class="rec-title">Restablecer Contraseña</h3>

            <form id="reestablecerForm">
                <div class="form-group">
                    <span class="input-icon" id="icon-user"></span>
                    <input autocomplete="off" type="email" class="custom-input" id="correo" name="correo" placeholder="Ingresa tu correo electrónico" required>
                </div>

                <button type="submit" class="btn-success" id="btnRecuperar">
                    Continuar
                </button>

                <div class="footer-links">
                    <a href="index.php">Iniciar Sesión</a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../../../../../public/js/utils/icons.js"></script>
    <script src="../../../../../public/js/utils/Alerts.js"></script>
    <script src="../../../../../public/js/utils/spinner.js"></script>
    <script src="../../../../../public/js/reestablecer.js"></script>
</body>

</html>
