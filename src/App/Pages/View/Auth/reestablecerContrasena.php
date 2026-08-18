<?php
session_start();

if (!isset($_SESSION['is_temporal']) || $_SESSION['is_temporal'] != 1) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Haceb Whirlpool - Reestablecer Contraseña</title>
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
            <h2 class="login-title">Reestablecer Contraseña</h2>
            <p style="text-align: center; color: #666; margin-bottom: 20px; font-size: 14px;">
                Por seguridad, debes cambiar tu contraseña temporal antes de continuar.
            </p>

            <form id="cambiarContrasenaForm" novalidate>
                <div class="form-group">
                    <span class="input-icon" id="icon-lock1"></span>
                    <input autocomplete="off" type="password" class="custom-input" id="nuevaPassword" name="nuevaPassword" placeholder="Nueva Contraseña" required>
                    <button class="password-toggle" id="passwordToggle1" type="button" style="display: none;">
                        <span id="icon-eye1"></span>
                    </button>
                </div>

                <div class="form-group">
                    <span class="input-icon" id="icon-lock2"></span>
                    <input autocomplete="off" type="password" class="custom-input" id="confirmPassword" name="confirmPassword" placeholder="Confirmar Contraseña" required>
                    <button class="password-toggle" id="passwordToggle2" type="button" style="display: none;">
                        <span id="icon-eye2"></span>
                    </button>
                </div>

                <span id="errorConfirmPassword" style="color: red; display: block; text-align: center; margin-top: -15px; margin-bottom: 5px; font-size: 12px; height: 14px;"></span>

                <button type="submit" class="btn-success" id="btnCambiar" style="margin-top: -10px;">
                    Cambiar Contraseña
                </button>
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
    <script src="../../../../../public/js/Auth/reestablecerContrasena.js"></script>
</body>

</html>