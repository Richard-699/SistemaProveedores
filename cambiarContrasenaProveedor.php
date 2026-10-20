<?php
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
    <link rel="stylesheet" href="./Estilos/Generals/estilos_cambiarContrasena.css">
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
                                <label for="inputPassword" class="floating-label">Nueva Contraseña</label>
                                <button class="password-toggle" id="passwordToggle" type="button" onclick="togglePassword('inputPassword')" aria-label="Mostrar contraseña">
                                    <span id="passwordIcon" class="d-inline-flex align-items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#0093B2" viewBox="0 0 16 16">
                                            <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z"/>
                                            <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/>
                                        </svg>
                                    </span>
                                </button>
                            </div>
                            <div class="col-12 form-group">
                                <input type="password" class="custom-input" id="confirmPassword" placeholder=" " oninput="validarContrasena()" name="new_password">
                                <label for="confirmPassword" class="floating-label">Confirmar Contraseña</label>
                                <button class="password-toggle" id="confirmPasswordToggle" type="button" onclick="togglePassword('confirmPassword')" aria-label="Mostrar contraseña">
                                    <span id="confirmPasswordIcon" class="d-inline-flex align-items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#0093B2" viewBox="0 0 16 16">
                                            <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z"/>
                                            <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/>
                                        </svg>
                                    </span>
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

        const svgEye = `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#0093B2" viewBox="0 0 16 16"><path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z"/><path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/></svg>`;
        const svgEyeSlash = `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#0093B2" viewBox="0 0 16 16"><path d="M13.359 11.238C15.06 9.72 16 8 16 8s-3-5.5-8-5.5a7 7 0 0 0-2.79.588l.77.771A6 6 0 0 1 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755q-.247.248-.517.486z"/><path d="M11.297 9.124a3.5 3.5 0 0 0-4.42-4.42l1.096 1.096a1.5 1.5 0 0 1 1.656 1.656z"/><path d="M10.73 12.733A8 8 0 0 1 8 13c-5 0-8-5-8-5a13 13 0 0 1 2.072-2.877l1.408 1.408A6 6 0 0 0 8 11.5c.782 0 1.52-.162 2.18-.45z"/><path d="M13.646 14.354l-12-12 .708-.708 12 12z"/></svg>`;

        function togglePassword(inputId) {
            const passwordInput = document.getElementById(inputId);
            const type = passwordInput.type === 'password' ? 'text' : 'password';
            passwordInput.type = type;

            const iconContainer = document.querySelector(`[onclick="togglePassword('${inputId}')"] span`);
            if (iconContainer) {
                iconContainer.innerHTML = type === 'password' ? svgEye : svgEyeSlash;
            }
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
