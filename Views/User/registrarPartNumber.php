<?php
session_start();

if (empty($_SESSION["id_rol_usuarios"])) {
    header("Location:../../index.php");
}
if ($_SESSION["id_rol_usuarios"] != 2) {
    header("Location:../../index.php");
}

$Id_area_usuario = $_SESSION['Id_area_usuario'];
error_reporting(0);
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
    <title>Registrar Part Number</title>

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
                        <a href="#" class="text-decoration-none px-3 py-2 d-block">
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
                    <a href="vincularProveedor.php" class="text-decoration-none px-3 py-2 d-block">
                        <i class="material-icons">person_add</i> Vinculación Proveedor
                    </a>
                </li>
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
                <h2 class="title">Agregar PartNumber</h2>
                <form class="" enctype="multipart/form-data" action="../../Actions/User/registrarPartNumber.php" method="POST" onsubmit="return validateForm()">
                    <div class="mt-5">
                        <p>Proveedor</p>
                        <select class="form-select select2" name="id_proveedor" onchange="mostrarDatos()">
                            <option value="" disabled selected>Seleccione el Proveedor</option>
                            <?php
                            include('../../ConexionBD/conexion.php');

                            $consultarProveedores = mysqli_query($conexion, "SELECT * FROM proveedores
                            INNER JOIN usuarios ON proveedores.Id_proveedor = usuarios.id_proveedor_usuarios
                            WHERE usuarios.Estado_Registro = 1 ORDER BY nombre_proveedor ASC");

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

                    <div class="fixed-button-container">
                        <button disabled type="button" class="btn btn-primary addpartnumber" onclick="agregarPartNumber()">+</button>
                    </div>

                    <div class="row mt-3" id="ContenerdorPartNumbers">

                    </div>

                    <div class="col-md-12 text-center mt-5" id="registrarBtn">
                        <button type="submit" value="Ingresar" class="btn btn-success registrarBtn">Registrar PartNumber</button>
                        <br><br><br><br><br>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script>
        jQuery(document).ready(function() {
            jQuery('select[name="id_proveedor"]').select2({
                placeholder: "Seleccione el Proveedor",
                allowClear: true
            });

            $('.addpartnumber').hide();
            $('#ContenerdorPartNumbers').hide();
            $("#registrarBtn").hide();
        });

        function mostrarDatos() {
            $('#ContenerdorPartNumbers').show();
            $('.addpartnumber').show();
            $('#ContenerdorPartNumbers').empty();

            $("#registrarBtn").show();

            var nuevoFormulario = `
                <div class="row mt-5">
                    <div class="col-md-6">
                        <label for="PartNumber" class="floating-label">PartNumber: *</label>
                        <input name="partnumber[]" type="text" id="partnumberForm1" oninput="activarBoton1()" class="form-control custom-input" placeholder="Ingrese el PartNumber">
                    </div>
                    <div class="col-md-6">
                        <label for="Descripcion_PartNumber" class="floating-label">Descripción PartNumber: *</label>
                        <div class="input-group">
                            <input name="descripcion_partnumber[]" id="descripcionForm1" oninput="activarBoton1()" type="text" class="form-control custom-input" placeholder="Ingrese la descripción del PartNumber">
                        </div>
                    </div>
<<<<<<< HEAD
                    <div class="col-md-6 mt-4">
                        <label for="imagen_partnumber" class="floating-label">Imagen PartNumber: *</label>
                        <div class="input-group">
                            <input name="imagen_partnumber[]" id="imagen_partnumberForm1" onchange="activarBoton1()" type="file" accept="image/*" class="form-control custom-input">
                        </div>
                    </div>
                    <div class="col-md-6 mt-4">
                        <label for="porcentaje_peso_bom_partnumber" class="floating-label">% Peso BOM PartNumber: *</label>
                        <div class="input-group">
                            <input name="porcentaje_peso_bom_partnumber[]" id="porcentaje_peso_bom_partnumberForm1" oninput="activarBoton1()" type="text" class="form-control custom-input" placeholder="Ingrese el % del peso en la BOM del PartNumber">
                        </div>
                    </div>
=======
>>>>>>> 8fe25a02a378af3db1c5f09c74bddd125a144800
                    <div class="col-md-11 mt-4">
                        <div class="form-group">
                            <label for="Commodity" class="floating-label">Commodity: *</label>
                            <select id="Commodity" class="form-select custom-input" name="commodity_proveedor[]">
                                <option value="" disabled selected>Seleccione una opción</option>
                                <?php

                                $consultarCommodities = mysqli_query($conexion, "SELECT * FROM proveedor_commodities 
                                                                        ORDER BY descripcion_commodity ASC");

                                if (mysqli_num_rows($consultarCommodities) > 0) {
                                    while ($MostrarCommodities = mysqli_fetch_array($consultarCommodities)) {
                                ?>
                                        <option value="<?php echo $MostrarCommodities['descripcion_commodity']; ?>"><?php echo $MostrarCommodities['descripcion_commodity'] ?></option>
                                <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-1" style="margin-top: 42px;">
                    <button type="button" disabled class="btn btn-danger removepartnumber" onclick="removerPartNumber(this)"><i class="material-icons">delete</i></button>
                    </div>
                    <hr class="mt-5">
                </div>
            `;

            $('#ContenerdorPartNumbers').append(nuevoFormulario);
        }

        function agregarPartNumber() {

            var nuevoFormulario = `
                <div class="row mt-5">
                    <div class="col-md-6">
<<<<<<< HEAD
                        <label for="PartNumber" class="floating-label">PartNumber: *</label>
=======
                        <label for="PartNumber" class="floating-label">PartNumber</label>
>>>>>>> 8fe25a02a378af3db1c5f09c74bddd125a144800
                        <input name="partnumber[]" type="text" id="partnumberForm2" oninput="activarBoton2()" class="form-control custom-input" placeholder="Ingrese el PartNumber">
                    </div>
                    <div class="col-md-6">
                        <label for="Descripcion_PartNumber" class="floating-label">Descripción PartNumber: *</label>
                        <div class="input-group">
                            <input name="descripcion_partnumber[]" id="descripcionForm2" oninput="activarBoton2()" type="text" class="form-control custom-input" placeholder="Ingrese la descripción del PartNumber">
                        </div>
                    </div>
<<<<<<< HEAD
                    <div class="col-md-6 mt-4">
                        <label for="imagen_partnumber" class="floating-label">Imagen PartNumber: *</label>
                        <div class="input-group">
                            <input name="imagen_partnumber[]" id="imagen_partnumberForm2" type="file" accept="image/*" class="form-control custom-input">
                        </div>
                    </div>
                    <div class="col-md-6 mt-4">
                        <label for="porcentaje_peso_bom_partnumber" class="floating-label">% Peso BOM PartNumber: *</label>
                        <div class="input-group">
                            <input name="porcentaje_peso_bom_partnumber[]" id="porcentaje_peso_bom_partnumberForm2" oninput="activarBoton1()" type="text" class="form-control custom-input" placeholder="Ingrese el % del peso en la BOM del PartNumber">
                        </div>
                    </div>
=======
>>>>>>> 8fe25a02a378af3db1c5f09c74bddd125a144800
                    <div class="col-md-11 mt-4">
                            <div class="form-group">
                                <label for="Commodity" class="floating-label">Commodity: *</label>
                                <select id="Commodity" class="form-select custom-input" name="commodity_proveedor[]">
                                    <option value="" disabled selected>Seleccione una opción</option>
                                    <?php

                                    $consultarCommodities = mysqli_query($conexion, "SELECT * FROM proveedor_commodities 
                                                                            ORDER BY descripcion_commodity ASC");

                                    if (mysqli_num_rows($consultarCommodities) > 0) {
                                        while ($MostrarCommodities = mysqli_fetch_array($consultarCommodities)) {
                                    ?>
                                            <option value="<?php echo $MostrarCommodities['descripcion_commodity']; ?>"><?php echo $MostrarCommodities['descripcion_commodity'] ?></option>
                                    <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    <div class="col-md-1" style="margin-top: 42px;">
                    <button type="button" class="btn btn-danger removepartnumber" onclick="removerPartNumber(this)"><i class="material-icons">delete</i></button>
                    </div>
                    <hr class="mt-5">
                </div>
            `;

            $('#ContenerdorPartNumbers').append(nuevoFormulario);

            var addButton = document.querySelector('.addpartnumber');
            addButton.setAttribute('disabled', 'disabled');
        }

        function removerPartNumber(button) {
            $(button).closest('.row').remove();
        }

        function activarBoton1() {
            var partNumberForm = $('#partnumberForm1').val();
            var descripcionForm = $('#descripcionForm1').val();
            var imagen_partnumberForm = $('#imagen_partnumberForm1').val();
            var porcentaje_peso_bom_partnumberForm = $('#porcentaje_peso_bom_partnumberForm1').val();

            var addButton = document.querySelector('.addpartnumber');

            if (partNumberForm !== '' && descripcionForm !== '' && imagen_partnumberForm !== '' && porcentaje_peso_bom_partnumberForm !== '') {
                addButton.removeAttribute('disabled');
            } else {
                addButton.setAttribute('disabled', 'disabled');
            }
        }

        function activarBoton2() {
            var partNumberForm = $('#partnumberForm2').val();
            var descripcionForm = $('#descripcionForm2').val();
            var imagen_partnumberForm = $('#imagen_partnumberForm2').val();
            var porcentaje_peso_bom_partnumberForm = $('#porcentaje_peso_bom_partnumberForm2').val();
            var addButton = document.querySelector('.addpartnumber');

            if (partNumberForm !== '' && descripcionForm !== '' && imagen_partnumberForm !== '' && porcentaje_peso_bom_partnumberForm !== '') {
                addButton.removeAttribute('disabled');
            } else {
                addButton.setAttribute('disabled', 'disabled');
            }
        }
    </script>


</html>