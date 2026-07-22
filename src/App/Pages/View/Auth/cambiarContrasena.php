<?php
session_start();

if (!isset($_SESSION['is_temporal']) || $_SESSION['is_temporal'] != 1) {
    header("Location: ../../../../../index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Haceb Whirlpool - Cambiar Contraseña</title>
    <link rel="shortcut icon" href="../../../../../img/LogoBlanco.png" type="image/x-icon">
    <link rel="stylesheet" href="../../../../../public/css/login.css">
</head>

<body>

    <div class="login-container">
        <div class="login-left">
            <img src="../../../../../img/hwiLogo.png" alt="Logo Haceb Whirlpool">
        </div>
        <div class="login-right">
            <h2 class="login-title">Cambiar Contraseña</h2>
            <p style="text-align: center; color: #666; margin-bottom: 20px; font-size: 14px;">
                Por seguridad, debes cambiar tu contraseña temporal antes de continuar.
            </p>

            <form id="cambiarContrasenaForm">
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

                <span id="errorConfirmPassword" style="color: red; display: block; text-align: center; margin-top: -10px; margin-bottom: 10px; font-size: 12px; height: 14px;"></span>

                <button type="submit" class="btn-success" id="btnCambiar" disabled>
                    Guardar Cambios
                </button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../../../../../public/js/utils/icons.js"></script>
    <script src="../../../../../public/js/utils/Alerts.js"></script>
    <script src="../../../../../public/js/utils/spinner.js"></script>
    <script src="../../../../../public/js/cambiarContrasena.js"></script>
</body>

</html>
