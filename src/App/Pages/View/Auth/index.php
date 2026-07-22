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
    <title>Haceb Whirlpool - Iniciar sesión</title>
    <link rel="shortcut icon" href="../../../../../img/LogoBlanco.png" type="image/x-icon">
    <link rel="stylesheet" href="../../../../../public/css/login.css">
</head>

<body>

    <div class="login-container">
        <div class="login-left">
            <img src="../../../../../img/hwiLogo.png" alt="Logo Haceb Whirlpool">
        </div>
        <div class="login-right">
            <h2 class="login-title">Iniciar Sesión</h2>

            <form id="loginForm">
                <div class="form-group">
                    <span class="input-icon" id="icon-user"></span>
                    <input autocomplete="off" type="text" class="custom-input" id="usuario" name="usuario" placeholder="<?php echo $lang['User']; ?>" required>
                </div>
                
                <div class="form-group">
                    <span class="input-icon" id="icon-lock"></span>
                    <input autocomplete="off" type="password" class="custom-input" id="inputPassword" name="password" placeholder="<?php echo $lang['Password']; ?>" required>
                    <button class="password-toggle" id="passwordToggle" type="button" style="display: none;">
                        <span id="icon-eye"></span>
                    </button>
                </div>

                <button type="submit" class="btn-success" id="btningresar">
                    Ingresar
                </button>

                <div class="footer-links">
                    <a href="reestablecerContrasena.php"><?php echo $lang['Forgot_Password']; ?></a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../../../../../public/js/utils/icons.js"></script>
    <script src="../../../../../public/js/utils/Alerts.js"></script>
    <script src="../../../../../public/js/utils/spinner.js"></script>
    <script src="../../../../../public/js/login.js"></script>
</body>

</html>