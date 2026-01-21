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
                    <a href="#" class="text-decoration-none px-3 py-2 d-block">
                        <i class="material-icons">key</i> Gestión Accesos
                    </a>
                </li>
                <li>
                    <a href="restablecerContrasena.php" class="text-decoration-none px-3 py-2 d-block">
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

                $consultarSolicitudesRegistro = mysqli_query($conexion, "SELECT * FROM usuarios 
                inner join roles ON roles.id_rol = usuarios.id_rol_usuarios
                where estado_registro='0'");

                if (mysqli_num_rows($consultarSolicitudesRegistro) > 0) {
                ?>

                    <div style="width:70%; margin: 0 auto; margin-top: 180px;">
                        <center>
                            <h3>Gestión de accesos </h3>
                            <br><br><br>
                        </center>
                        <table id="MostrarAccesos" class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Correo</th>
                                    <th>Tipo</th>
                                    <th></th>
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
                                        <td><?php echo $MostrarSolicitudes['decripcion_rol'] ?>
                                        <td>
                                            <?php echo '<a class="btn-aprobarAdmin" href="../../Actions/Admin/aprobarSolicitudes.php?id_usuario=' . $MostrarSolicitudes['Id_usuario'] . '">Aprobar</a>' ?>
                                        </td>
                                        <td>
                                            <?php echo '<a onclick="confirmarRechazo(' . $MostrarSolicitudes['id_usuario'] . ')" class="btn btn-danger editbtnPass" href="#" data-toggle="modal" data-target="#ModalAccesos">Rechazar</a>' ?>
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
                } else {
                ?>
                    <h3 class="mensaje">No se presentan solicitudes de acceso en este momento...</h3>
                <?php
                }

                ?>

            </div>
        </div>
    </div>

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

        $(".sidebar ul li").on('click', function() {
            $(".sidebar ul li.active").removeClass('active');
            $(this).addClass('active');
        });

        $('.open-btn').on('click', function() {
            $('.sidebar').addClass('active');
        });

        $('.close-btn').on('click', function() {
            $('.sidebar').removeClass('active');
        });
    </script>
    <script src="../../Js/idiomaTablas.js"></script>
</body>

</html>