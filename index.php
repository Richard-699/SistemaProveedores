<?php
$idioma = $_GET['idioma'] ?? 'es';
$new_idioma = ucfirst($idioma);
$ruta = 'IdiomaConfig/' . $new_idioma . '.php';
include($ruta);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Haceb Whirlpool</title>
    <link rel="shortcut icon" href="./img/LogoBlanco.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.6/dist/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="./Estilos/Generals/estilos_login.css">
</head>

<body>

    <div class="container w-75 mt-5 bg-white rounded shadow">
        <div class="row align-items-stretch">
            <div class="col bg">

            </div>
            <div class="col p-5 rounded-end">
                <h2 class="fw-bold text-center py-4"><?php echo $lang['Login']; ?></h2>

                <form method="POST" action="Actions/Generals/validacion_login.php">
                    <?php
                    include "ConexionBD/conexion.php";
                    ?>
                    <div class="mb-4 form-group mt-5">
                        <input autocomplete="off" type="text" class="custom-input" id="usuario" name="usuario" placeholder=" " required>
                        <label for="Usuario" class="floating-label"><?php echo $lang['User']; ?></label>
                    </div>
                    <div class="mb-4 form-group">
                        <input autocomplete="off" type="password" class="custom-input" id="inputPassword" name="password" placeholder=" " required>
                        <label for="password" class="floating-label"><?php echo $lang['Password']; ?></label>
                        <button class="password-toggle" id="passwordToggle" type="button" onclick="togglePassword('inputPassword')">
                            <i class="material-icons" id="passwordIcon">visibility</i>
                        </button>
                    </div>

                    <div class="d-grid mb-4">
                        <button type="submit" value="Ingresar" name="btningresar" class="btn btn-success align-items-center" id="btningresar">
                            <i class="material-icons align-middle" style="font-size: 24px;">login</i> <span class="align-middle"><?php echo $lang['Login']; ?></span>
                        </button>
                    </div>
                    <p style="text-align: center; margin: 0 auto;">
                        <a href="#" id="reset-password-link"><?php echo $lang['Forgot_Password']; ?></a>
                    </p>
                    <p style="text-align: center; margin: 0 auto;"><?php echo $lang['Sin_cuenta']; ?> <a href="registro.php"><?php echo $lang['Register']; ?></a></p>
                </form>
            </div>
        </div>
    </div>

    <br>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js" integrity="sha384-mQ93GR66B00ZXjt0YO5KlohRA5SY2XofN4zfuZxLkoj1gXtW8ANNCe9d5Y3eG5eD" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const passwordInput = document.getElementById('inputPassword');
        const passwordToggle = document.getElementById('passwordToggle');
        const passwordIcon = document.getElementById('passwordIcon');

        passwordInput.addEventListener('input', togglePasswordIcon);

        function togglePasswordIcon() {
            passwordToggle.style.display = passwordInput.value.length > 0 ? 'block' : 'none';
        }

        function togglePassword(inputId) {
            const passwordInput = document.getElementById(inputId);
            const type = passwordInput.type === 'password' ? 'text' : 'password';
            passwordInput.type = type;
            const icon = document.querySelector(`[onclick="togglePassword('${inputId}')"] i`);
            icon.textContent = type === 'password' ? 'visibility' : 'visibility_off';
        }

        document.getElementById('reset-password-link').addEventListener('click', function(event) {
        event.preventDefault();
        
        Swal.fire({
            title: 'Restablecer Contraseña',
            text: 'Para restablecer la contraseña debes solicitarlo al administrador.',
            icon: 'info',
            confirmButtonText: 'Aceptar',
            confirmButtonColor: '#0093B2'
        });
    });
    </script>
</body>

</html>