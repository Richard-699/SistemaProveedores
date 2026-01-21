<?php
session_start();

if (empty($_SESSION["id_rol_usuarios"])) {
    header("Location:../../index.php");
}
if ($_SESSION["id_rol_usuarios"] != 2) {
    header("Location:../../index.php");
}

include('../../ConexionBD/conexion.php');

$Id_area_usuario = $_SESSION['Id_area_usuario'];
$correo_usuario = $_SESSION["correo_usuario"];

error_reporting(0);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="shortcut icon" href="../../img/LogoBlanco.png" type="image/x-icon">
    <link rel="stylesheet" href="../../Estilos/Supplier/estilos_costBreakDown.css">
    <link rel="stylesheet" href="../../Estilos/Generals/estilos_nav.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.4/css/dataTables.bootstrap5.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <title>Proveedores</title>
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
                    <a href="index.php" class="text-decoration-none px-3 py-2 d-block">
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
                <?php
                if ($Id_area_usuario != 4) {
                ?>
                    <li class="">
                        <a href="vincularProveedor.php" class="text-decoration-none px-3 py-2 d-block">
                            <i class="material-icons">person_add</i> Vinculación Proveedor
                        </a>
                    </li>
                <?php
                }
                if ($Id_area_usuario == 3) {
                ?>
                    <li class="">
                        <a href="historicoLAFT.php" class="text-decoration-none px-3 py-2 d-block">
                            <i class="material-icons">history</i> Histórico LAFT
                        </a>
                    </li>
                <?php } ?>
                <li class="">
                    <a href="#" class="text-decoration-none px-3 py-2 d-block">
                        <i class="material-icons">how_to_reg</i> Proveedores Vinculados
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
                    $consultarProveedores = mysqli_query($conexion, "SELECT * FROM proveedores WHERE proveedor_aprobado = 1");

                    if (mysqli_num_rows($consultarProveedores) > 0) {
                    ?>
                        <h2 class="title" style="margin-top: 130px;">Proveedores Vinculados</h2>
                        <div style="width:90%; margin: 0 auto; margin-top: 130px; position: relative;">
                            <?php
                            if ($Id_area_usuario == 4) {
                            ?>
                                <div style="position: absolute; right: 0; top: -70px;">
                                    <button type="button" onclick="exportarExcel()" class="export-btn"><i class="fa-solid fa-file-excel"></i> Exportar</button>
                                </div>
                            <?php } ?>
                            <table id="tableProveedores" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-start">N° Acreedor</th>
                                        <th>Proveedor</th>
                                        <th>Tipo Proveedor</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while ($MostrarProveedores = mysqli_fetch_array($consultarProveedores)) {
                                    ?>
                                        <tr>
                                            <td class="text-start"><?php echo $MostrarProveedores['numero_acreedor']; ?></td>
                                            <td><?php echo $MostrarProveedores['nombre_proveedor']; ?></td>
                                            <td><?php echo $MostrarProveedores['tipo_proveedor']; ?></td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <br><br>
                    <?php
                    } else {
                    ?>
                        <h3 style="margin-top: 250px;" class="mensaje">No existen proveedores vinculados en este momento...</h3>
                    <?php
                    }
                    ?>
                </div>
            </div>

            <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
            <script src="https://cdn.datatables.net/2.1.4/js/jquery.dataTables.min.js"></script>
            <script src="https://cdn.datatables.net/2.1.4/js/dataTables.bootstrap5.min.js"></script>
            <script src="../../Js/idiomaTablas.js"></script>
            <script>
                $(document).ready(function() {
                    $('#tableProveedores').DataTable({
                        language: espanol
                    });
                });
            </script>

        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.4/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.4/js/dataTables.bootstrap5.js"></script>



    <!--     <script src="../../Js/idiomaTablas.js"></script> -->

</html>

<script>
    function exportarExcel() {
        window.location.href = '../../Actions/User/exportarProveedores.php';
    }
</script>