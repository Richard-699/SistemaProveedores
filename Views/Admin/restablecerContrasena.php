<?php
include("../../ConexionBD/conexion.php");
session_start();

if (empty($_SESSION["id_rol_usuarios"])) {
    header("Location:../../index.php");
}
if ($_SESSION["id_rol_usuarios"] != 1) {
    header("Location:../../index.php");
}
error_reporting(0);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HWI</title>
    <link rel="shortcut icon" href="./img/LogoBlanco.png" type="image/x-icon">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="shortcut icon" href="../../img/LogoBlanco.png" type="image/x-icon">
    <link rel="stylesheet" href="../../Estilos/Admin/estilos_index.css">
    <link rel="stylesheet" href="../../Estilos/Supplier/estilos_costBreakDown.css">
    <link rel="stylesheet" href="../../Estilos/Generals/estilos_nav.css">

    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.5/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.5/js/dataTables.bootstrap5.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.5/css/dataTables.bootstrap5.css">
</head>

<body>

    <div class="main-container d-flex">
        <div class="sidebar" id="side_nav">
            <div class="header-box px-2 pt-3 pb-4 d-flex justify-content-between">
            </div>

            <ul class="list-unstyled px-2">
                <li>
                    <a class="fondo-img" href="#">
                        <img src="../../img/hwiLogo.png" class="img-logo">
                    </a>
                </li>
                <li class="mt-5">
                    <a href="index.php" class="text-decoration-none px-3 py-2 d-block">
                        <i class="material-icons">home</i> Inicio
                    </a>
                </li>
                <li>
                    <a href="gestionAccesos.php" class="text-decoration-none px-3 py-2 d-block">
                        <i class="material-icons">key</i> Gestión Accesos
                    </a>
                </li>
                <li>
                    <a href="#" class="text-decoration-none px-3 py-2 d-block">
                        <i class="material-icons">lock_reset</i> Restablecer Contraseñas
                    </a>
                </li>
            </ul>
        </div>
        <div class="content" id="contenido">

            <div class="franja">
                <div class="datos-sesion">
                    <i class="material-icons icon-nav">person</i>
                    <h2 class="usuario"><?php echo $_SESSION["nombre_usuario"]; ?></h2>
                    <div class="separador"></div>
                    <a href="../../Actions/Generals/cerrarsesion.php" class="cerrar-sesion">
                        <i class="material-icons icon-nav">logout</i>
                        <h2 class="text-cerrarsesion">Cerrar Sesión</h2>
                    </a>
                </div>
            </div>

            <div id="accesos">
                <?php

                $consultarSolicitudesRegistro = mysqli_query($conexion, "SELECT * FROM usuarios LEFT JOIN proveedores ON usuarios.id_proveedor_usuarios = proveedores.Id_proveedor");

                if (mysqli_num_rows($consultarSolicitudesRegistro) > 0) {
                ?>

                    <div style="width:70%; margin: 0 auto; margin-top: 180px;">
                        <center>
                            <h3>Restablecer Contraseñas </h3>
                            <br><br><br>
                        </center>
                        <table id="MostrarAccesos" class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Usuario/Proveedor</th>
                                    <th>Correo</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                while ($MostrarSolicitudes = mysqli_fetch_array($consultarSolicitudesRegistro)) {
                                ?>
                                    <tr>
                                        <td><?php
                                            if ($MostrarSolicitudes['nombre_proveedor'] == "") {
                                                if ($MostrarSolicitudes['nombre_usuario'] == "") {
                                                    echo "N/A";
                                                } else {
                                                    echo $MostrarSolicitudes['nombre_usuario'];
                                                }
                                            } else {
                                                echo $MostrarSolicitudes['nombre_proveedor'];
                                            } ?></td>
                                        <td><?php echo $MostrarSolicitudes['correo_usuario'] ?></td>
                                        <td><?php echo $MostrarSolicitudes['correo_proveedor'] ?></td>
                                        <td>
                                            <button onclick="userPassword('<?php echo $MostrarSolicitudes['Id_usuario']; ?>')" type="button" class="editbtnPassNew">
                                                <span class="material-icons">lock_reset</span>
                                            </button>
                                        </td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                        <script>
                            $(document).ready(function() {
                                $('#MostrarAccesos').DataTable({
                                    language: espanol
                                });
                            });
                        </script>
                    </div>
                    <br><br>
                <?php

                }
                ?>

            </div>
        </div>
    </div>

    <div class="modal fade" id="passwordModal" tabindex="-1" aria-labelledby="passwordModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="passwordModalLabel">Ingrese el correo del usuario o proveedor para enviar la nueva contraseña.</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <input type="hidden" id="userIdField" name="userId">
                        <input type="hidden" id="passwordTemp" name="passwordTemp">
                        <label for="passwordInput" class="form-label">Correo</label>
                        <input type="text" class="form-control" id="correo_envio" oninput="validarCorreo()" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" disabled id="btnEnviar" class="btn btn-success">Enviar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>

<script>
    function confirmarRechazo(id_usuario) {
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                cancelButton: "btn btn-danger",
                confirmButton: "btn btn-success"
            },
            buttonsStyling: false
        });

        swalWithBootstrapButtons.fire({
            title: "¿Estás seguro?",
            text: "¡No podrás revertir esto!",
            icon: "warning",
            showCancelButton: true,
            cancelButtonText: "No, cancelar",
            confirmButtonText: "Sí, rechazar",
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "../../Actions/Admin/rechazarSolicitudes.php?id_usuario=" + id_usuario;
            }
        });
    }

    function generarPasswordTemporal() {

        var caracteres = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
        var longitud = 8;
        var codigo = "";

        for (var i = 0; i < longitud; i++) {
            codigo += caracteres.charAt(Math.floor(Math.random() * caracteres.length));
        }

        return codigo;
    }

    function userPassword(idUsuario) {
        var myModal = new bootstrap.Modal(document.getElementById('passwordModal'));
        myModal.show();
        document.getElementById('userIdField').value = idUsuario;
        var passwordgenerado = generarPasswordTemporal();
        document.getElementById('passwordTemp').value = passwordgenerado;
    }

    function validarCorreo() {
        var correoInput = document.getElementById('correo_envio');
        var correo = correoInput.value;
        var regexCorreo = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        var correoValido = regexCorreo.test(correo);

        if (correoValido) {
            correoInput.classList.remove('is-invalid');
            correoInput.classList.add('is-valid');
            document.getElementById('btnEnviar').disabled = false;
        } else {
            document.getElementById('btnEnviar').disabled = true;
            correoInput.classList.remove('is-valid');
            correoInput.classList.add('is-invalid');
        }
    }

    document.querySelector('#passwordModal .modal-footer .btn-success').addEventListener('click', function() {
        $('#passwordModal').modal('hide');

        var correo_envio = $('#correo_envio').val();
        var id_usuario = $('#userIdField').val();
        var password_temp = $('#passwordTemp').val();

        var formData = new FormData();
        formData.append('correo_envio', correo_envio);
        formData.append('id_usuario', id_usuario);
        formData.append('password_temp', password_temp);

        var overlay = document.createElement('div');
        overlay.classList.add('overlay');
        document.body.appendChild(overlay);

        var spinnerContainer = document.createElement('div');
        spinnerContainer.classList.add('spinner-container');
        overlay.appendChild(spinnerContainer);

        var spinner = document.createElement('div');
        spinner.classList.add('spinner-border', 'text-primary');
        spinner.setAttribute('role', 'status');
        spinnerContainer.appendChild(spinner);

        var formInputs = document.querySelectorAll('#form_proveedor input, #form_proveedor select, #form_proveedor button');
        formInputs.forEach(function(input) {
            input.setAttribute('disabled', 'true');
        });

        var xhr = new XMLHttpRequest();
        xhr.open('POST', '../../Actions/Admin/restablecerContrasena.php', true);
        xhr.onload = function() {

            var spinnerContainer = document.querySelector('.modal-body .text-center');
            if (spinnerContainer) {
                spinnerContainer.parentNode.removeChild(spinnerContainer);
            }

            var overlay = document.querySelector('.overlay');
            if (overlay) {
                overlay.parentNode.removeChild(overlay);
            }

            var formInputs = document.querySelectorAll('#form_proveedor input, #form_proveedor select, #form_proveedor button');
            formInputs.forEach(function(input) {
                input.removeAttribute('disabled');
            });

            if (xhr.status === 200) {
                debugger;
                try {
                    var response = JSON.parse(xhr.responseText);
                    console.log(response);
                    if (response.success) {
                        Swal.fire({
                            icon: "success",
                            title: "Restablecimiento Exitoso",
                            text: "Las credenciales han sido enviadas para el restablecimiento.",
                            showConfirmButton: false,
                            timer: 2000
                        });
                        setTimeout(function() {
                            window.location.reload();
                        }, 2000);
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "Restablecimiento fallido, intenta de nuevo...",
                            showConfirmButton: false,
                            timer: 2000
                        });
                        setTimeout(function() {
                            window.location.href = "#";
                        }, 2000);
                    }
                } catch (error) {
                    console.error("Error al analizar la respuesta JSON:", error);
                }
            }
        };
        xhr.send(formData);
    });
</script>
<script src="../../Js/idiomaTablas.js"></script>