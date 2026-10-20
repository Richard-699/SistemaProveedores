<?php
$idioma = $_GET['idioma'] ?? 'es';
$new_idioma = ucfirst($idioma);
$ruta = 'IdiomaConfig/' . $new_idioma . '.php';

if (!file_exists($ruta)) {
    $ruta = 'IdiomaConfig/Es.php';
}
include($ruta);
/** @var array $lang */
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
                    <div class="mb-4 form-group mt-5">
                        <input autocomplete="off" type="text" class="custom-input" id="usuario" name="usuario" placeholder=" " required>
                        <label for="Usuario" class="floating-label"><?php echo $lang['User']; ?></label>
                    </div>
                    <div class="mb-4 form-group">
                        <input autocomplete="off" type="password" class="custom-input" id="inputPassword" name="password" placeholder=" " required>
                        <label for="password" class="floating-label"><?php echo $lang['Password']; ?></label>
                        <button class="password-toggle" id="passwordToggle" type="button" onclick="togglePassword('inputPassword')" aria-label="Mostrar contraseña">
                            <span id="passwordIcon" class="d-inline-flex align-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#0093B2" viewBox="0 0 16 16">
                                    <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z"/>
                                    <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/>
                                </svg>
                            </span>
                        </button>
                    </div>

                    <div class="d-grid mb-4">
                        <button type="submit" value="Ingresar" name="btningresar" class="btn btn-success d-flex align-items-center justify-content-center gap-2" id="btningresar">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M6 3.5a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-2a.5.5 0 0 0-1 0v2A1.5 1.5 0 0 0 6.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-8A1.5 1.5 0 0 0 5 3.5v2a.5.5 0 0 0 1 0z"/>
                                <path fill-rule="evenodd" d="M11.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5H1.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z"/>
                            </svg>
                            <span><?php echo $lang['Login']; ?></span>
                        </button>
                    </div>
                    <p style="text-align: center; margin: 0 auto;">
                        <a href="recuperarContrasena.php?idioma=<?php echo htmlspecialchars($idioma); ?>" id="reset-password-link"><?php echo $lang['Forgot_Password']; ?></a>
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

        const svgEye = `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#0093B2" viewBox="0 0 16 16"><path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z"/><path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/></svg>`;
        const svgEyeSlash = `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#0093B2" viewBox="0 0 16 16"><path d="M13.359 11.238C15.06 9.72 16 8 16 8s-3-5.5-8-5.5a7 7 0 0 0-2.79.588l.77.771A6 6 0 0 1 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755q-.247.248-.517.486z"/><path d="M11.297 9.124a3.5 3.5 0 0 0-4.42-4.42l1.096 1.096a1.5 1.5 0 0 1 1.656 1.656z"/><path d="M10.73 12.733A8 8 0 0 1 8 13c-5 0-8-5-8-5a13 13 0 0 1 2.072-2.877l1.408 1.408A6 6 0 0 0 8 11.5c.782 0 1.52-.162 2.18-.45z"/><path d="M13.646 14.354l-12-12 .708-.708 12 12z"/></svg>`;

        passwordInput.addEventListener('input', togglePasswordIcon);

        function togglePasswordIcon() {
            passwordToggle.style.display = passwordInput.value.length > 0 ? 'block' : 'none';
        }

        function togglePassword(inputId) {
            const passwordInput = document.getElementById(inputId);
            const type = passwordInput.type === 'password' ? 'text' : 'password';
            passwordInput.type = type;
            const iconContainer = document.querySelector(`[onclick="togglePassword('${inputId}')"] #passwordIcon`) || document.getElementById('passwordIcon');
            if (iconContainer) {
                iconContainer.innerHTML = type === 'password' ? svgEye : svgEyeSlash;
            }
        }
    </script>
</body>

</html>