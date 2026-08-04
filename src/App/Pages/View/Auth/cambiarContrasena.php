<?php
session_start();
$isTemporal = isset($_SESSION['is_temporal']) && $_SESSION['is_temporal'] == 1;

// If logged in but NOT temporal, they shouldn't be here (unless they are doing a manual change from profile later, but for now we follow the existing logic).
if (isset($_SESSION['is_admin']) && !$isTemporal) {
    if ($_SESSION['is_admin']) {
        header("Location: ../Admin/index.php");
    } else {
        header("Location: ../Supplier/index.php");
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Haceb Whirlpool - <?php echo $isTemporal ? 'Cambiar' : 'Restablecer'; ?> Contraseña</title>
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
            <?php if (!$isTemporal): ?>
                <!-- Formulario de Restablecer (Pedir Correo) -->
                <h3 class="rec-title">Restablecer Contraseña</h3>

                <form id="reestablecerForm" novalidate>
                    <div class="form-group">
                        <span class="input-icon" id="icon-user"></span>
                        <input autocomplete="off" type="email" class="custom-input" id="correo" name="correo" placeholder="Ingresa tu correo electrónico" required>
                    </div>

                    <button type="submit" class="btn-success" id="btnRecuperar">
                        Continuar
                    </button>

                    <div class="footer-links">
                        <a href="login.php">Iniciar Sesión</a>
                    </div>
                </form>
            <?php else: ?>
                <!-- Formulario de Cambiar (Ingresar Nueva Contraseña) -->
                <h2 class="login-title">Cambiar Contraseña</h2>
                <p style="text-align: center; color: #666; margin-bottom: 20px; font-size: 14px;">
                    Por seguridad, debes cambiar tu contraseña temporal antes de continuar.
                </p>

                <form id="cambiarContrasenaForm" novalidate>
                    <div class="form-group">
                        <span class="input-icon" id="icon-lock1"></span>
                        <input autocomplete="off" type="password" class="custom-input" id="nuevaPassword" name="nuevaPassword" placeholder="Nueva Contraseña" required>
                        <button class="password-toggle" id="passwordToggle1" type="button" style="display: none;" onclick="togglePassword('nuevaPassword', 'icon-eye1')">
                            <span id="icon-eye1"></span>
                        </button>
                    </div>
                    
                    <div class="form-group">
                        <span class="input-icon" id="icon-lock2"></span>
                        <input autocomplete="off" type="password" class="custom-input" id="confirmPassword" name="confirmPassword" placeholder="Confirmar Contraseña" required>
                        <button class="password-toggle" id="passwordToggle2" type="button" style="display: none;" onclick="togglePassword('confirmPassword', 'icon-eye2')">
                            <span id="icon-eye2"></span>
                        </button>
                    </div>

                    <span id="errorConfirmPassword" style="color: red; display: block; text-align: center; margin-top: -15px; margin-bottom: 5px; font-size: 12px; height: 14px;"></span>

                    <button type="submit" class="btn-success" id="btnCambiar" style="margin-top: -10px;" disabled>
                        Cambiar Contraseña
                    </button>
                </form>
            <?php endif; ?>
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
