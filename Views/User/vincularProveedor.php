<?php
session_start();

include('../../ConexionBD/conexion.php');

$Id_area_usuario = $_SESSION['Id_area_usuario'];
$correo_usuario = $_SESSION["correo_usuario"];

if (empty($_SESSION["id_rol_usuarios"])) {
    header("Location:../../index.php");
}
if ($_SESSION["id_rol_usuarios"] != 2) {
    header("Location:../../index.php");
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="shortcut icon" href="../../img/LogoBlanco.png" type="image/x-icon">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link rel="stylesheet" href="../../Estilos/User/estilos_registrarPartNumber.css">
    <link rel="stylesheet" href="../../Estilos/Supplier/estilos_costBreakDown.css">
    <link rel="stylesheet" href="../../Estilos/Generals/estilos_nav.css">
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.5/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.5/js/dataTables.bootstrap5.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.5/css/dataTables.bootstrap5.css">
    <title>Vinculación Proveedor</title>

</head>

<body>
    <div class="main-container d-flex">
        <div class="sidebar" id="side_nav">
            <div class="header-box px-2 pt-3 pb-4 d-flex justify-content-between">
            </div>

            <ul class="list-unstyled px-2">
                <li>
                    <a class="fondo-img" href="index.php">
                        <img src="../../img/hwiLogo.png" class="img-logo">
                    </a>
                </li>
                <li class="mt-4">
<<<<<<< HEAD
                    <a href="index.php" class="text-decoration-none px-3 py-2 d-block">
=======
                    <a href="index.php" class="text-decoration-none px-3 py-1 d-block">
>>>>>>> 8fe25a02a378af3db1c5f09c74bddd125a144800
                        <i class="material-icons">home</i> Inicio
                    </a>
                </li>
                <?php
                if ($Id_area_usuario == 1) {
                ?>
                    <li class="">
                        <a href="registrarProveedor.php" class="text-decoration-none px-3 py-2 d-block">
                            <i class="material-icons">apartment</i> Agregar Proveedor
                        </a>
                    </li>
                    <li class="">
                        <a href="proveedoresRegistrados.php" class="text-decoration-none px-3 py-2 d-block">
                            <i class="material-icons">groups</i> Proveedores Registrados
                        </a>
                    </li>
                    <li class="">
                        <a href="registrarPartNumber.php" class="text-decoration-none px-3 py-2 d-block">
                            <i class="material-icons">add</i> Agregar PartNumber
                        </a>
                    </li>
                    <li class="">
                        <a href="partNumbers.php" class="text-decoration-none px-3 py-2 d-block">
                            <i class="material-icons">precision_manufacturing</i> PartNumbers
                        </a>
                    </li>
                    <li class="">
                        <a href="resultadoCbd.php" class="text-decoration-none px-3 py-2 d-block">
                            <i class="material-icons">description</i> Resultado Cbd
                        </a>
                    </li>
                <?php
                }
                ?>
                <li class="">
                    <a href="#" class="text-decoration-none px-3 py-2 d-block">
                        <i class="material-icons">person_add</i> Vinculación Proveedor
                    </a>
                </li>
                <?php
                if ($Id_area_usuario == 3) {
                ?>
                    <li class="">
                        <a href="historicoLAFT.php" class="text-decoration-none px-3 py-2 d-block">
                            <i class="material-icons">history</i> Histórico LAFT
                        </a>
                    </li>
                <?php } ?>
                <li class="">
                    <a href="proveedores.php" class="text-decoration-none px-3 py-2 d-block">
<<<<<<< HEAD
                       <i class="material-icons">how_to_reg</i> Proveedores Vinculados
=======
                        <i class="material-icons">how_to_reg</i> Proveedores Vinculados
>>>>>>> 8fe25a02a378af3db1c5f09c74bddd125a144800
                    </a>
                </li>
                <?php
                if ($Id_area_usuario == 1) {
                ?>
                    <li class="">
                        <a href="serviciosSuministros.php" class="text-decoration-none px-3 py-2 d-block">
                            <i class="material-icons">design_services</i> Servicios/Suministros
                        </a>
                    </li>
                <?php
                }
                ?>
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

            <div class="form-container">
                <div id="accesos">
                    <?php

                    //Negociación
                    if ($Id_area_usuario == 1) {
                        $consultarProveedoresLaft = mysqli_query($conexion, "SELECT * FROM proveedores
<<<<<<< HEAD
                                LEFT JOIN laft ON proveedores.Id_proveedor = laft.Id_proveedor_laft
=======
                                INNER JOIN laft ON proveedores.Id_proveedor = laft.Id_proveedor_laft
>>>>>>> 8fe25a02a378af3db1c5f09c74bddd125a144800
                                INNER JOIN vinculacion_proveedor ON proveedores.Id_proveedor = vinculacion_proveedor.Id_proveedor_vinculacion_proveedor");
                    }

                    //Ambiental
                    if ($Id_area_usuario == 2) {
                        $consultarProveedoresLaft = mysqli_query($conexion, "SELECT * FROM proveedores
                                INNER JOIN laft ON proveedores.Id_proveedor = laft.Id_proveedor_laft
                                INNER JOIN vinculacion_proveedor ON proveedores.Id_proveedor = vinculacion_proveedor.Id_proveedor_vinculacion_proveedor
<<<<<<< HEAD
                                WHERE formulario_ambiental = '1' AND proveedores.idioma_proveedor = 'Es'");
=======
                                INNER JOIN laft ON proveedores.Id_proveedor = laft.Id_proveedor_laft
                                WHERE formulario_ambiental = '1'");
>>>>>>> 8fe25a02a378af3db1c5f09c74bddd125a144800
                    }

                    //Cumplimiento
                    if ($Id_area_usuario == 3) {
                        $consultarProveedoresLaft = mysqli_query($conexion, "SELECT * FROM proveedores
                        INNER JOIN laft ON proveedores.Id_proveedor = laft.Id_proveedor_laft
                        INNER JOIN vinculacion_proveedor ON proveedores.Id_proveedor = vinculacion_proveedor.Id_proveedor_vinculacion_proveedor");
                    }

                    if (mysqli_num_rows($consultarProveedoresLaft) > 0) {
                    ?>
                        <h2 class="title">Vinculación Proveedor</h2>
<<<<<<< HEAD
                        <div style="width: 100%; font-size: 15px; margin-left: -50px; margin-top: 80px;">
=======
                        <div style="width: 90%; margin-left: -40px; margin-top: 80px;">
>>>>>>> 8fe25a02a378af3db1c5f09c74bddd125a144800
                            <table id="MostrarAccesos" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Actualizado</th>
                                        <th>Proveedor</th>
                                        <th>Tipo</th>
                                        <th>Ambiental</th>
                                        <th>Cumplimiento</th>
                                        <th>Negociación</th>
                                        <th>Estado</th>
                                        <th style="width: 10px;"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while ($MostrarProveedoresLaft = mysqli_fetch_array($consultarProveedoresLaft)) {
                                    ?>
                                        <tr>
                                            <td><?php echo $MostrarProveedoresLaft['ultima_actualizacion_laft']; ?></td>
                                            <td><?php echo $MostrarProveedoresLaft['nombre_proveedor']; ?></td>
                                            <td><?php echo $MostrarProveedoresLaft['tipo_proveedor']; ?></td>
                                            <td>
                                                <?php
                                                if ($MostrarProveedoresLaft['formulario_ambiental'] == 1) {
                                                    if (is_null($MostrarProveedoresLaft['aprobado_ambiental'])) {
                                                        echo "Pendiente";
                                                    } elseif ($MostrarProveedoresLaft['aprobado_ambiental'] == 1) {
                                                        echo "Aprobado";
                                                    } elseif ($MostrarProveedoresLaft['aprobado_ambiental'] == 0) {
                                                        echo "Rechazado";
                                                    }
                                                } else {
                                                    echo "N/A";
                                                }
                                                ?></td>
                                            <td><?php
                                                if (is_null($MostrarProveedoresLaft['aprobado_cumplimiento'])) {
                                                    echo "Pendiente";
                                                } elseif ($MostrarProveedoresLaft['aprobado_cumplimiento'] == 1) {
                                                    echo "Aprobado";
                                                } elseif ($MostrarProveedoresLaft['aprobado_cumplimiento'] == 0) {
                                                    echo "Rechazado";
                                                }

                                                ?></td>
                                            <td><?php
                                                if (is_null($MostrarProveedoresLaft['aprobado_negociacion'])) {
                                                    echo "Pendiente";
                                                } elseif ($MostrarProveedoresLaft['aprobado_negociacion'] == 1) {
                                                    echo "Aprobado";
                                                } elseif ($MostrarProveedoresLaft['aprobado_negociacion'] == 0) {
                                                    echo "Rechazado";
                                                }
                                                ?></td>
                                            <td><?php
                                                if ($MostrarProveedoresLaft['proveedor_aprobado'] == 1) {
                                                    echo "Aprobado";
                                                } else {
                                                    echo "Pendiente";
                                                } ?></td>
                                            <td>
                                                <?php echo '<a class="btn btn-success editbtnPass" href="proveedorDetalles.php?id_proveedor=' . $MostrarProveedoresLaft['Id_proveedor'] . '"><i class="material-icons">visibility</i></a>' ?>
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
                        <h3 style="margin-top: 250px;" class="mensaje">No se presentan solicitudes de vinculación en este momento...</h3>
                    <?php
                    }
                    ?>

                </div>
            </div>
        </div>
    </div>

    <script src="../../Js/idiomaTablas.js"></script>

</html>

</html>