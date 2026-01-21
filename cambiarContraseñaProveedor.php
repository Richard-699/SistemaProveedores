<?php
//Prueba
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar Contraseña HWI</title>
    <link rel="shortcut icon" href="./img/LogoBlanco.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.6/dist/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="./Estilos/Generals/estilos_cambiarContraseña.css">
</head>

<body>
    <div class="containerChangePassword">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <i class="material-icons" style="margin-top:20px;">vpn_key</i>
                        <p class="titleContraseña">Cambiar Contraseña</p>
                        <p class="subtitleContraseña">Se recomienda usar una contraseña segura que no uses para ningún otro sitio</p>
                    </div>
                    <div class="card-body">
                        <form class="row g-3 mt-4" action="./Actions/Generals/cambiarContrasena.php" method="POST">
                            <div class="col-12 form-group">
                                <input type="password" class="custom-input" id="inputPassword" placeholder=" ">
                                <label for="inputUsername" class="floating-label">Nueva Contraseña</label>
                                <button class="password-toggle" id="passwordToggle" type="button" onclick="togglePassword('inputPassword')">
                                    <i class="material-icons" id="passwordIcon">visibility</i>
                                </button>
                            </div>
                            <div class="col-12 form-group">
                                <input type="password" class="custom-input" id="confirmPassword" placeholder=" " oninput="validarContrasena()" name="new_password">
                                <label for="inputUsername" class="floating-label">Confirmar Contraseña</label>
                                <button class="password-toggle" id="confirmPasswordToggle" type="button" onclick="togglePassword('confirmPassword')">
                                    <i class="material-icons" id="confirmPasswordIcon">visibility</i>
                                </button>
                            </div>
                            <span id="errorConfirmPassword" style="color: red; margin-top:-30px; margin-left: 60px"></span>
                            <div class="col-12 form-group">
                                <button disabled type="submit" class="btn btn-success">Guardar Cambios</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const passwordInput = document.getElementById('inputPassword');
        const confirmPasswordInput = document.getElementById('confirmPassword');
        const passwordToggle = document.getElementById('passwordToggle');
        const confirmPasswordToggle = document.getElementById('confirmPasswordToggle');
        const passwordIcon = document.getElementById('passwordIcon');
        const confirmPasswordIcon = document.getElementById('confirmPasswordIcon');

        // Evento input para el campo de contraseña
        passwordInput.addEventListener('input', togglePasswordIcon);
        confirmPasswordInput.addEventListener('input', togglePasswordIcon);

        function togglePasswordIcon() {
            // Mostrar u ocultar el icono solo si hay contenido en el campo
            passwordToggle.style.display = passwordInput.value.length > 0 ? 'block' : 'none';
            confirmPasswordToggle.style.display = confirmPasswordInput.value.length > 0 ? 'block' : 'none';
        }

        function togglePassword(inputId) {
            const passwordInput = document.getElementById(inputId);
            const type = passwordInput.type === 'password' ? 'text' : 'password';
            passwordInput.type = type;

            // Cambiar el icono entre visibility y visibility_off
            const icon = document.querySelector(`[onclick="togglePassword('${inputId}')"] i`);
            icon.textContent = type === 'password' ? 'visibility' : 'visibility_off';
        }

        function validarContrasena(){
            var inputPassword = $('#inputPassword').val();
            var confirmPassword = $('#confirmPassword').val();

            var errorSpan = document.getElementById("errorConfirmPassword");
            var submitButton = document.querySelector('button[type="submit"]');

            if (inputPassword === confirmPassword) {
                errorSpan.textContent = ""; // Limpiar el mensaje de error
                submitButton.removeAttribute("disabled"); // Habilitar el botón
            } else {
                errorSpan.textContent = "Las contraseñas no coinciden"; // Mostrar mensaje de error
                submitButton.setAttribute("disabled", "disabled"); // Deshabilitar el botón
            }
        }
    </script>

</body>

</html>
