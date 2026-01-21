<?php
session_start();

$Id_area_usuario = $_SESSION['Id_area_usuario'];

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
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link rel="stylesheet" href="../../Estilos/User/estilos_registrarPartNumber.css">
    <link rel="stylesheet" href="../../Estilos/Supplier/estilos_costBreakDown.css">
    <link rel="stylesheet" href="../../Estilos/Generals/estilos_nav.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.5/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.5/js/dataTables.bootstrap5.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.5/css/dataTables.bootstrap5.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <title>HWI</title>
    <style>
    th, td {
        text-align: left;
    }
</style>
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
                        <a href="#" class="text-decoration-none px-3 py-2 d-block">
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
                    <a href="proveedores.php" class="text-decoration-none px-3 py-2 d-block">
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
                <h2 class="title">Resultado Cost Breakdown</h2>
                <div class="row mt-5">
                    <div class="col-md-11">
                        <p>Proveedor</p>
                        <select class="form-select select2" name="id_proveedor" id="id_proveedor">
                            <option value="" disabled selected>Seleccione el Proveedor</option>
                            <?php
                            include('../../ConexionBD/conexion.php');

                            $consultarProveedores = mysqli_query($conexion, "SELECT * FROM proveedores
                            INNER JOIN usuarios ON proveedores.Id_proveedor = usuarios.id_proveedor_usuarios
                            WHERE usuarios.Estado_Registro = 1 and proveedores.tipo_proveedor = 'Directo'  ORDER BY nombre_proveedor ASC");

                            if (mysqli_num_rows($consultarProveedores) > 0) {
                                while ($MostrarProveedores = mysqli_fetch_array($consultarProveedores)) {
                            ?>
                                    <option value="<?php echo $MostrarProveedores['Id_proveedor']; ?>"><?php echo $MostrarProveedores['nombre_proveedor'] ?></option>
                            <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <button class="btn btn-primary btn-buscar" id="btn-consultar">
                            <span class="material-icons">search</span>
                        </button>
                    </div>
                </div>

                <div class="row mt-5">
                    <table id="proveedores-table" class="table table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th class="text-start">Fecha</th>
                                <th class="text-start">Diligenciado Por</th>
                                <th class="text-start">Part Number</th>
                                <th class="text-start">Descripcion</th>
                                <th class="text-start">Precio Neto</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <br><br><br><br>
            </div>
        </div>
    </div>

    <script>
        jQuery(document).ready(function() {
            jQuery('select[name="id_proveedor"]').select2({
                placeholder: "Seleccione el Proveedor",
                allowClear: true
            });
        });

        $(document).ready(function() {
            $('#btn-consultar').on('click', function() {
                var id_proveedor = $('#id_proveedor').val();
                debugger;
                if (id_proveedor) {
                    $.ajax({
                        url: '../../Actions/User/consultarResultadoCbd.php',
                        method: 'POST',
                        data: {
                            id_proveedor: id_proveedor
                        },
                        success: function(response) {
                            $('#proveedores-table tbody').empty();
                            $('#proveedores-table tbody').html(response);
                            $('#proveedores-table').DataTable();
                        },
                        error: function(xhr, status, error) {
                            console.error('Error en la consulta:', error);
                        }
                    });
                } else {
                    alert('Por favor seleccione un proveedor.');
                }
            });
        });

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

</html>