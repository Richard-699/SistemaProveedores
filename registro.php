<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro Haceb Whirlpool</title>
    <link rel="shortcut icon" href="./img/LogoBlanco.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="./Estilos/Generals/estilos_registro.css" type="text/css">
</head>

<body>

    <div class="form-container">
        <img src="./img/hwiLogo.png" alt="HWI Logo" class="logo">
        <h2 class="title">Registro</h2><br>
        <br>
        <h3 class="subtitle">Este formulario de registro es solo para usuarios, si eres un proveedor debes solicitar el registro al negociador.</h3>
        <br>
        <form class="row g-3 mt-4 needs-validation" novalidate id="form_proveedor" action="Actions/Generals/registrarUsuario.php" method="POST" autocomplete="off">
            <div class="col-12 form-group">
                <input type="text" class="custom-input" id="inputNombre" placeholder=" " name="nombre_usuario" required>
                <label for="inputNombre" class="floating-label">Nombres: *</label>
                <span class="error-message" id="error-inputNombre" style="display: none; color: red;">Requerido.</span>
            </div>
            <div class="col-12 form-group">
                <input type="text" class="custom-input" id="inputApellidos" placeholder=" " name="apellidos_usuario" required>
                <label for="inputApellidos" class="floating-label">Apellidos: *</label>
                <span class="error-message" id="error-inputApellidos" style="display: none; color: red;">Requerido.</span>
            </div>
            <div class="col-12 form-group">
                <input type="text" class="custom-input" id="inputCorreo" placeholder=" " name="usuario" oninput="validarCorreo()" required>
                <label for="inputCorreo" class="floating-label">Correo Haceb Whirlpool: *</label>
                <span class="error-message" id="error-inputCorreo" style="display: none; color: red;">Requerido.</span>
            </div>

            <div class="col-12 form-group">
                <label class="label_format2">Área: *</label>
                <select class="form-select" name="id_area_usuario" required>
                    <option value="" disabled selected>Seleccione el Área</option>
                    <?php
                    include('./ConexionBD/conexion.php');

                    $consultarAreas = mysqli_query($conexion, "SELECT * FROM areas ORDER BY nombre_area ASC");

                    if (mysqli_num_rows($consultarAreas) > 0) {
                        while ($MostrarAreas = mysqli_fetch_array($consultarAreas)) {
                    ?>
                            <option value="<?php echo $MostrarAreas['id_area']; ?>"><?php echo $MostrarAreas['nombre_area'] ?></option>
                    <?php
                        }
                    }
                    ?>
                </select>
                <span class="error-message" id="error-id_area_usuario" style="display: none; color: red;">Requerido.</span>
            </div>

            <div class="col-12 form-group mt-4">
                <input type="password" class="custom-input" id="inputPassword" placeholder=" " oninput="validarContrasena()" required>
                <label for="inputPassword" class="floating-label">Crear contraseña: *</label>
                <button class="password-toggle" id="passwordToggle" type="button" onclick="togglePassword('inputPassword')">
                    <i class="material-icons" id="passwordIcon">visibility</i>
                </button>
                <span class="error-message" id="error-inputPassword" style="display: none; color: red;">Requerido.</span>
            </div>
            <div class="col-12 form-group">
                <input type="password" class="custom-input" id="confirmPassword" oninput="validarContrasena()" placeholder=" " name="password" required>
                <label for="confirmPassword" class="floating-label">Confirmar contraseña: *</label>
                <button class="password-toggle" id="confirmPasswordToggle" type="button" onclick="togglePassword('confirmPassword')">
                    <i class="material-icons" id="confirmPasswordIcon">visibility</i>
                </button>
                <span class="error-message" id="error-confirmPassword" style="display: none; color: red;">Requerido.</span>
            </div>
            <div class="col-12 form-group">
                <button type="submit" value="" name="btningresar" class="btn btn-success align-items-center" id="btningresar">Registrarse</button>
            </div>
            <p style="text-align: center; margin: 0 auto;">¿Ya tienes una cuenta? <a href="index.php">Inicia Sesión</a></p>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (function() {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')

            Array.prototype.slice.call(forms)
                .forEach(function(form) {
                    form.addEventListener('submit', function(event) {
                        var errorMessages = document.querySelectorAll('.error-message');
                        errorMessages.forEach(function(errorMessage) {
                            errorMessage.style.display = 'none';
                        });

                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()

                            var requiredFields = form.querySelectorAll('[required]');
                            requiredFields.forEach(function(field) {
                                if (!field.checkValidity()) {
                                    var errorSpan = document.getElementById('error-' + field.id);
                                    if (errorSpan) {
                                        errorSpan.style.display = 'block';
                                    }
                                }
                            });
                        }

                        form.classList.add('was-validated')
                    }, false)
                })
        })()

        function validarCorreo() {
            var correoUsuario = document.getElementById("inputCorreo").value;
            var errorSpan = document.getElementById("error-inputCorreo");
            var btnIngresar = document.getElementById("btningresar");
            var correoValido = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(correoUsuario);

            if (!correoValido) {
                errorSpan.textContent = "Correo inválido";
                errorSpan.style.display = "block";
                btnIngresar.disabled = true;
                return;
            } else {
                errorSpan.style.display = "none";
                btnIngresar.disabled = false;

                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        if (this.responseText === "true") {
                            errorSpan.textContent = "Este correo ya se encuentra registrado.";
                            errorSpan.style.display = "block";
                            btnIngresar.disabled = true;
                        } else {
                            errorSpan.textContent = "Requerido.";
                            errorSpan.style.display = "none";
                            btnIngresar.disabled = false;
                        }
                    }
                };
                xhttp.open("POST", "./Actions/User/validarCorreo.php", true);
                xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhttp.send("correo_usuario=" + correoUsuario);
            }
        }

        function validarContrasena(){
            var inputPassword = document.getElementById('inputPassword').value;
            var confirmPassword = document.getElementById('confirmPassword').value;

            var errorSpanInputPassword = document.getElementById("error-inputPassword");
            var errorSpanConfirmPassword= document.getElementById("error-confirmPassword");
            var submitButton = document.querySelector('button[type="submit"]');

            if (inputPassword === confirmPassword) {
                errorSpanInputPassword.textContent = "Requerido.";
                errorSpanConfirmPassword.textContent = "Requerido.";
                errorSpanInputPassword.style.display = "none";
                errorSpanConfirmPassword.style.display = "none";
                submitButton.removeAttribute("disabled");
            } else {
                errorSpanInputPassword.textContent = "Las contraseñas no coinciden";
                errorSpanConfirmPassword.textContent = "Las contraseñas no coinciden";
                errorSpanInputPassword.style.display = "block";
                errorSpanConfirmPassword.style.display = "block";
                submitButton.setAttribute("disabled", "disabled");
            }
        }

        const passwordInput = document.getElementById('inputPassword');
        const confirmPasswordInput = document.getElementById('confirmPassword');
        const passwordToggle = document.getElementById('passwordToggle');
        const confirmPasswordToggle = document.getElementById('confirmPasswordToggle');
        const passwordIcon = document.getElementById('passwordIcon');
        const confirmPasswordIcon = document.getElementById('confirmPasswordIcon');

        passwordInput.addEventListener('input', togglePasswordIcon);
        confirmPasswordInput.addEventListener('input', togglePasswordIcon);

        function togglePasswordIcon() {
            passwordToggle.style.display = passwordInput.value.length > 0 ? 'block' : 'none';
            confirmPasswordToggle.style.display = confirmPasswordInput.value.length > 0 ? 'block' : 'none';
        }

        function togglePassword(inputId) {
            const passwordInput = document.getElementById(inputId);
            const type = passwordInput.type === 'password' ? 'text' : 'password';
            passwordInput.type = type;

            const icon = document.querySelector(`[onclick="togglePassword('${inputId}')"] i`);
            icon.textContent = type === 'password' ? 'visibility' : 'visibility_off';
        }
    </script>
</body>

</html>