<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - HWI</title>
    <!-- Favicon -->
    <link rel="shortcut icon" href="../../../../../public/img/LogoBlanco.png" type="image/x-icon">
    <!-- Iconos Materiales (para el ojo de la contraseña) -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <!-- Dependencias de estilos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Estilos Personalizados -->
    <link rel="stylesheet" href="../../../../../public/css/Auth/registro.css">
</head>
<body>

    <div class="registro-container">
        
        <div class="registro-header">
            <div class="registro-title">
                <h2>REGISTRO</h2>
                <h2>ADMINISTRADORES</h2>
            </div>
            <img src="../../../../../public/img/LogoBlanco.png" alt="Logo HWI">
        </div>
        <p class="registro-aviso">Este formulario de registro es solo para usuarios, si eres un proveedor debes solicitar el registro al negociador.</p>

            <form id="form_proveedor" action="../../Handler/registroHandler.php" method="POST" class="registro-form" autocomplete="off" novalidate>
                
                <div class="form-group">
                    <label class="form-label">Nombre:</label>
                    <div class="input-wrapper">
                        <input type="text" id="inputNombre" name="nombre_usuario" placeholder="Nombres" required class="form-input">
                    </div>
                    
                    <div class="input-wrapper pt-2">
                        <input type="text" id="inputApellidos" name="apellidos_usuario" placeholder="Apellido" required class="form-input">
                    </div>
                </div>

                <div class="form-group pt-1">
                    <label class="form-label">Email Haceb Whirlpool:</label>
                    <div class="input-wrapper" style="display: flex; gap: 0.5rem;">
                        <input type="text" id="inputCorreo" name="usuario_prefijo" placeholder="Usuario" required class="form-input" style="flex: 1;">
                        <select id="inputDominio" name="usuario_dominio" required class="form-select" style="flex: 1;">
                            <option value="@hacebwhirlpool.com">@hacebwhirlpool.com</option>
                            <option value="@whirlpool.com">@whirlpool.com</option>
                        </select>
                    </div>
                </div>

                <div class="form-group pt-1">
                    <label class="form-label">Área:</label>
                    <div class="input-wrapper">
                        <select id="id_area_usuario" name="id_area_usuario" required class="form-select">
                            <option value="" disabled selected>Seleccione el área</option>
                        </select>
                    </div>
                </div>

                <div class="form-group pt-3">
                    <label class="form-label">Contraseña:</label>
                    <div class="input-wrapper">
                        <input type="password" id="inputPassword" name="password" placeholder="Crear contraseña" required class="form-input">
                        <button type="button" id="passwordToggle" onclick="togglePassword('inputPassword')" class="password-toggle hidden">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="24" height="24" style="transition: color 0.2s;"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        </button>
                    </div>
                    
                    <div class="input-wrapper pt-2">
                        <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirmar contraseña" required class="form-input">
                        <button type="button" id="confirmPasswordToggle" onclick="togglePassword('confirmPassword')" class="password-toggle hidden">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="24" height="24" style="transition: color 0.2s;"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        </button>
                    </div>
                </div>

                <div class="submit-container">
                    <button type="submit" id="btningresar" class="btn-submit">
                        Registrarse
                    </button>
                </div>
                
                <p class="footer-text">¿Ya tienes una cuenta? <a href="login.php" class="footer-link">Inicia Sesión</a></p>

            </form>
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
    <script src="../../../../../public/js/Auth/registro.js"></script>
</body>
</html>