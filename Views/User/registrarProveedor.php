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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="shortcut icon" href="../../img/LogoBlanco.png" type="image/x-icon">
    <title>Registrar Proveedor</title>
    <link rel="stylesheet" href="../../Estilos/User/estilos_registrarProveedor.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.5/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.5/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../../Estilos/Supplier/estilos_costBreakDown.css">
    <link rel="stylesheet" href="../../Estilos/Generals/estilos_nav.css">

    <style>
        .select2-container {
            margin-top: 15px;
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
                        <a href="#" class="text-decoration-none px-3 py-2 d-block">
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
                <br><br>
                <h2 class="title">Registrar Proveedor</h2>
                <form class="needs-validation" action="" novalidate id="form_proveedor" method="POST" enctype="multipart/form-data">
                    <div class="row mt-5">
                        <div class="col-md-6 mt-5">
                            <label for="numero_acreedor" class="floating-label">Nº Acreedor</label>
                            <input type="text" name="numero_acreedor" oninput="validarNumeroAcreedor()" class="form-control custom-input" id="numero_acreedor" placeholder="Ingrese el número de acreedor" name="usuario">
                        </div>

                        <div class="col-md-6 mt-5">
                            <label for="nombre_proveedor" class="floating-label">Nombre: *</label>
                            <input type="text" name="nombre_proveedor" class="form-control custom-input" id="nombre_proveedor" placeholder="Ingrese el nombre del proveedor" required>
                        </div>

                        <div class="col-md-6 mt-5">
                            <label for="idioma_proveedor" class="floating-label">Formato de Idioma: *</label>
                            <select id="idioma_proveedor" class="form-select custom-input" name="idioma_proveedor" required>
                                <option selected disabled value="">Seleccione una opción</option>
                                <option value="Es">Español</option>
                                <option value="En">Inglés</option>
                            </select>
                        </div>

                        <div class="col-md-6 mt-5">
                            <label for="tipo_proveedor" class="floating-label">Tipo de Proveedor: *</label>
                            <select onchange="validar_tipo_proveedor()" id="tipo_proveedor" class="form-select custom-input" name="tipo_proveedor" required>
                                <option selected disabled value="">Seleccione una opción</option>
                                <option value="Directo">Directo</option>
                                <option value="Indirecto">Indirecto</option>
                            </select>
                        </div>

                        <?php
                        include('../../ConexionBD/conexion.php');
                        ?>

                        <div class="col-md-6 mt-5" id="hiddenIndirectos" style="display: none">
                            <div class="form-group">
                                <label for="categoria" class="floating-label">Categoría: *</label>
                                <select id="categorias" class="form-select custom-input" name="id_categoria" onchange="cargarSubCategoria()">
                                    <option value="" disabled selected>Seleccione una opción</option>
                                    <?php
                                    $consultarCategorias = mysqli_query($conexion, "SELECT * FROM proveedor_categorias
                                                                                ORDER BY descripcion_categoria ASC");

                                    if (mysqli_num_rows($consultarCategorias) > 0) {
                                        while ($MostrarCategorias = mysqli_fetch_array($consultarCategorias)) {
                                    ?>
                                            <option value="<?php echo $MostrarCategorias['Id_categoria']; ?>"><?php echo $MostrarCategorias['descripcion_categoria'] ?></option>
                                    <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 mt-5" id="hiddenIndirectos" style="display: none">
                            <div class="form-group">
                                <label for="sub_categoria" class="floating-label">Sub - Categoría: *</label>
                                <select id="sub_categoria" class="form-select custom-input" name="id_sub_categoria">
                                    <option value="" disabled selected>Seleccione una opción</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 mt-5" id="hiddenDirectos" style="display: none">
                            <div class="form-group">
                                <label for="Commodity" class="floating-label">Commodity: *</label>
                                <select id="Commodity" class="form-select custom-input" name="commodity_proveedor">
                                    <option value="" disabled selected>Seleccione una opción</option>
                                    <?php

                                    $consultarCommodities = mysqli_query($conexion, "SELECT * FROM proveedor_commodities 
                                                                            ORDER BY descripcion_commodity ASC");

                                    if (mysqli_num_rows($consultarCommodities) > 0) {
                                        while ($MostrarCommodities = mysqli_fetch_array($consultarCommodities)) {
                                    ?>
                                            <option value="<?php echo $MostrarCommodities['Id_commodity']; ?>"><?php echo $MostrarCommodities['descripcion_commodity'] ?></option>
                                    <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 mt-5" id="hiddenDirectos" style="display: none">
                            <label>¿Maneja el formato Cost Breakdown?: *</label>
                            <div class="d-flex mt-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" checked id="flexSwitchCheckDefault1" value="1" name="maneja_el_formato_CostBreakDown">
                                    <label class="form-check-label" for="flexSwitchCheckDefault1">Si</label>
                                </div>

                                <div class="form-check form-switch mlcheck">
                                    <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault2" value="0" name="maneja_el_formato_CostBreakDown">
                                    <label class="form-check-label" for="flexSwitchCheckDefault2">No</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mt-5">
                            <label>¿Debe llenar el formulario de ambiental?: *</label>
                            <div class="d-flex mt-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault3" value="1" name="formulario_ambiental">
                                    <label class="form-check-label" for="flexSwitchCheckDefault3">Si</label>
                                </div>

                                <div class="form-check form-switch mlcheck">
                                    <input class="form-check-input" type="checkbox" checked id="flexSwitchCheckDefault4" value="0" name="formulario_ambiental">
                                    <label class="form-check-label" for="flexSwitchCheckDefault4">No</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 mt-5" id="hiddenDirectos" style="display: none">
                            <label for="historia_proveedor" class="floating-label">Historia: *</label>
                            <textarea maxlength="2000" type="text" name="historia_proveedor" class="form-control custom-input textarea-contador" id="historia_proveedor" placeholder="Ingrese la historia del proveedor" required></textarea>
                        </div>

                        <div class="col-md-12 mt-5" id="hiddenDirectos" style="display: none">
                            <label for="descripcion_proveedor" class="floating-label">Descripción: *</label>
                            <textarea maxlength="5000" type="text" name="descripcion_proveedor" class="form-control custom-input textarea-contador" id="descripcion_proveedor" placeholder="Ingrese la descripción del proveedor" required></textarea>
                        </div>

                        <div class="col-md-6 mt-5" id="hiddenDirectos" style="display: none">
                            <label for="porcentaje_bom_proveedor" class="floating-label">% Peso BOM: *</label>
                            <input type="text" oninput="validarPorcentaje(this)" name="porcentaje_bom_proveedor" class="form-control custom-input" id="porcentaje_bom_proveedor" placeholder="Ingrese el nombre del proveedor" required>
                        </div>

                        <div class="col-md-6 mt-5" id="hiddenDirectos" style="display: none">
                            <label for="logo_proveedor" class="floating-label">Logo Proveedor: *</label>
                            <input type="file" name="logo_proveedor" class="form-control custom-input" id="logo_proveedor" required accept="image/*">
                        </div>

                        <div class="col-md-6 mt-5" id="hiddenIndirectos" style="display: none">
                            <label for="servicio_suministro">Servicio o Suministro: *</label>
                            <select id="servicio_suministro" name="servicios_suministros[]" class="form-control" multiple></select>
                        </div>

                        <!-- Campos ocultos -->

                        <input type="email" class="form-control custom-input hidden" id="correo_proveedor" name="correo_proveedor">
                        <input type="text" class="form-control custom-input hidden" id="password_proveedor" name="password_proveedor">

                        <div class="col-md-12 text-center mt-5" id="registrarBtn">
                            <button onclick="userPassword()" type="submit" value="Ingresar" name="btningresar" class="btn btn-success registrarBtn">Continuar</button>
                        </div>

                    </div>
                </form>
                <br><br>
            </div>
        </div>

    </div>
    <div class="modal fade" id="passwordModal" tabindex="-1" aria-labelledby="passwordModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="passwordModalLabel">Ingrese el correo del proveedor para enviar las credenciales.</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="passwordInput" class="form-label">Correo</label>
                        <input type="text" class="form-control" id="correo_envio" oninput="validarCorreo()" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" disabled id="btnEnviar" class="btn btn-success">Enviar y Registrar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>

<script>
    document.getElementById('form_proveedor').addEventListener('submit', function(event) {
        event.preventDefault();
        userPassword();
    });

    document.querySelectorAll('.textarea-contador').forEach(textarea => {
        const max = parseInt(textarea.getAttribute('maxlength'));

        let contador = document.createElement('small');
        contador.className = 'contador-texto';
        contador.textContent = `0 / ${max} caracteres`;

        textarea.insertAdjacentElement('afterend', contador);

        textarea.addEventListener('input', function() {
            if (this.value.length > max) {
                this.value = this.value.substring(0, max);
            }
            contador.textContent = `${this.value.length} / ${max} caracteres`;
        });
    });

    function cargarSubCategoria() {
        var categoriaSeleccionada = document.getElementById("categorias").value;
        var subcategoriaSelect = document.getElementById("sub_categoria");

        if (subcategoriaSelect) {
            subcategoriaSelect.innerHTML = "";
        }

        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var subcategorias = JSON.parse(xhr.responseText);

                var option = document.createElement("option");
                option.text = "Seleccione una opción";
                option.value = "";
                option.disabled = true;
                option.selected = true;
                subcategoriaSelect.appendChild(option);

                subcategorias.forEach(function(subcategoria) {
                    var option = document.createElement("option");
                    option.text = subcategoria.descripcion_sub_categoria;
                    option.value = subcategoria.Id_sub_categoria;
                    subcategoriaSelect.appendChild(option);
                });
            }
        };

        xhr.open("GET", "../../Actions/User/obtener_subcategorias.php?categoria=" + categoriaSeleccionada, true);
        xhr.send();
    }

    function validar_tipo_proveedor() {
        let tipo_proveedor = $('#tipo_proveedor').val();
        let hiddenDirectos = document.querySelectorAll('#hiddenDirectos');
        let hiddenIndirectos = document.querySelectorAll('#hiddenIndirectos');

        if (tipo_proveedor === 'Directo') {
            hiddenDirectos.forEach(function(elemento) {
                elemento.style.display = 'block';
            });
            hiddenIndirectos.forEach(function(elemento) {
                elemento.style.display = 'none';
            });
        } else {
            hiddenDirectos.forEach(function(elemento) {
                elemento.style.display = 'none';
            });
            hiddenIndirectos.forEach(function(elemento) {
                elemento.style.display = 'block';
            });
        }
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

    function generarPasswordTemporal() {

        var caracteres = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
        var longitud = 8;
        var codigo = "";

        for (var i = 0; i < longitud; i++) {
            codigo += caracteres.charAt(Math.floor(Math.random() * caracteres.length));
        }

        return codigo;
    }

    function validarPorcentaje(input) {
        let valor = input.value;
        valor = valor.replace(/[^0-9.]/g, '');

        const partes = valor.split('.');
        if (partes.length > 2) valor = partes[0] + '.' + partes.slice(1).join('');

        if (valor.endsWith('.')) {
            input.value = valor;
            return;
        }

        let numero = parseFloat(valor);

        if (isNaN(numero)) {
            input.value = valor;
            return;
        }

        if (numero > 100) numero = 100;
        input.value = numero.toString().includes('.') ? numero.toFixed(2).replace(/0+$/, '').replace(/\.$/, '') : numero;
    }

    (function() {
        'use strict'
        var forms = document.querySelectorAll('.needs-validation')

        Array.prototype.slice.call(forms)
            .forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }

                    form.classList.add('was-validated')
                }, false)
            })
    })()

    function userPassword() {

        var nombreProveedor = document.getElementById('nombre_proveedor').value;
        nombreProveedor = nombreProveedor.toLowerCase().replace(/\s+/g, '');
        var idioma_proveedor = document.getElementById('idioma_proveedor').value;
        var tipo_proveedor = document.getElementById('tipo_proveedor').value;

        if (tipo_proveedor == 'Directo') {
            var commodityProveedor = document.getElementById('Commodity').value;
            if (nombreProveedor !== '' && commodityProveedor !== '' && tipo_proveedor !== '' && idioma_proveedor !== '') {
                var correogenerado = nombreProveedor;
                var passwordgenerado = generarPasswordTemporal();

                document.getElementById('correo_proveedor').value = correogenerado;
                document.getElementById('password_proveedor').value = passwordgenerado;

                var myModal = new bootstrap.Modal(document.getElementById('passwordModal'));
                myModal.show();
            }
        } else {
            var categorias = document.getElementById('categorias').value;
            var sub_categoria = document.getElementById('sub_categoria').value;

            if (nombreProveedor !== '' && categorias !== '' && sub_categoria !== null && tipo_proveedor !== '' && idioma_proveedor !== '') {
                var correogenerado = nombreProveedor;
                var passwordgenerado = generarPasswordTemporal();

                document.getElementById('correo_proveedor').value = correogenerado;
                document.getElementById('password_proveedor').value = passwordgenerado;

                var myModal = new bootstrap.Modal(document.getElementById('passwordModal'));
                myModal.show();
            }
        }
    }

    $('#passwordModal').on('hidden.bs.modal', function() {
        $('.modal-backdrop').remove();
    });

    $('#passwordModal').on('shown.bs.modal', function() {
        $('#correo_envio').focus();
    });

    document.querySelector('#passwordModal .modal-footer .btn-success').addEventListener('click', function() {
        $('#passwordModal').modal('hide');

        var correo_envio = $('#correo_envio').val();
        var formData = new FormData(document.getElementById('form_proveedor'));
        formData.append('correo_envio', correo_envio);

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
        xhr.open('POST', '../../Actions/User/registrarProveedor.php', true);
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
                            title: "Registro exitoso",
                            text: "El proveedor se ha registrado con éxito.",
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
                            text: "Los datos no se cargaron correctamente, intenta de nuevo...",
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

    function validarNumeroAcreedor() {

        var numero_acreedor = document.getElementById('numero_acreedor');
        var value = numero_acreedor.value.trim();
        var numericValue = value.replace(/\D/g, '');
        if (numericValue.length > 15) {
            numericValue = numericValue.slice(0, 15);
        }
        numero_acreedor.value = numericValue;

        var data = {
            numero_acreedor: value
        };

        $.ajax({
            url: '../../Actions/User/consultarProveedor.php',
            type: 'POST',
            data: data,
            success: function(response) {
                var jsonResponse = JSON.parse(response);
                if (jsonResponse.success) {
                    $('#numero_acreedor').next('.invalid-feedback').remove();
                    $('#numero_acreedor').removeClass('is-valid');
                    $('#numero_acreedor').addClass('is-invalid');
                    $('#numero_acreedor').after('<div class="invalid-feedback">Este proveedor ya está registrado</div>');
                    $('#registrarBtn button').prop('disabled', true);
                } else {
                    $('#numero_acreedor').removeClass('is-invalid');
                    $('#numero_acreedor').addClass('is-valid');
                    $('#numero_acreedor').next('.invalid-feedback').remove();
                    $('#registrarBtn button').prop('disabled', false);
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });

    }

    document.addEventListener("DOMContentLoaded", function() {

        $('#servicio_suministro').select2({
            placeholder: "Seleccione una opción",
            allowClear: true
        });
        $.ajax({
            url: '../../Actions/User/consultarServiciosSuministros.php',
            type: 'GET',
            data: {
                _: new Date().getTime()
            },
            dataType: 'json',
            success: function(data) {
                data.forEach(item => {
                    $('#servicio_suministro').append(new Option(item.servicio_suministro, item.id_servicio_suministro));
                });
                $('#servicio_suministro').select2({
                    placeholder: "Seleccione los servicios o suministros",
                    allowClear: true,
                    width: '100%'
                });
            }
        });

        var checkbox1 = document.getElementById("flexSwitchCheckDefault1");
        var checkbox2 = document.getElementById("flexSwitchCheckDefault2");

        checkbox1.addEventListener("change", function() {
            if (checkbox1.checked) {
                checkbox2.checked = false;
            }
        });

        checkbox2.addEventListener("change", function() {
            if (checkbox2.checked) {
                checkbox1.checked = false;
            }
        });

        var checkbox3 = document.getElementById("flexSwitchCheckDefault3");
        var checkbox4 = document.getElementById("flexSwitchCheckDefault4");

        checkbox3.addEventListener("change", function() {
            if (checkbox3.checked) {
                checkbox4.checked = false;
            }
        });

        checkbox4.addEventListener("change", function() {
            if (checkbox4.checked) {
                checkbox3.checked = false;
            }
        });
    });
</script>