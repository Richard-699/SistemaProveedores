<html>

<head>
<<<<<<< HEAD
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.5/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.5/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>


=======
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.5/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.5/dist/sweetalert2.min.js"></script>
>>>>>>> 8fe25a02a378af3db1c5f09c74bddd125a144800
    <style>
        .btn-update {
            padding: 5px;
            border-radius: 5px;
            border: none;
            height: 37px;
            width: 90px;
            background-color: #0093B2;
            color: white;
        }

        .btn-secondary {
            margin-top: -6px;
        }

        .swal-custom-popup {
            z-index: 9999 !important;
        }
<<<<<<< HEAD

        .select2-results__option {
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
        }

        .select2-container {
            margin-top: 15px;
        }

        .logo-container {
            display: flex;
            justify-content: center;
            margin-top: 10px;
        }

        .logo-circle {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 2px solid #ccc;
            background-color: #f8f9fa;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            transition: all 0.2s;
        }

        .logo-circle:hover {
            border-color: #007bff;
        }

        .logo-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .placeholder-icon {
            font-size: 30px;
            color: #6c757d;
        }
=======
>>>>>>> 8fe25a02a378af3db1c5f09c74bddd125a144800
    </style>
</head>

<body>

    <div id="editarProveedorPartial" style="display: none; width: 75%;">
        <h2>Editar Proveedor</h2>
<<<<<<< HEAD
        <form id="formEditarProveedor" enctype="multipart/form-data">
=======
        <form id="formEditarProveedor">
>>>>>>> 8fe25a02a378af3db1c5f09c74bddd125a144800

            <input type="hidden" id="Id_proveedor" name="Id_proveedor" value="<?php echo $proveedor['Id_proveedor']; ?>">

            <div class="row">
                <div class="col-md-6 mt-5">
                    <label for="numero_acreedor" class="floating-label">Nº Acreedor</label>
                    <input type="text" name="numero_acreedor" value="<?php echo $proveedor['numero_acreedor']; ?>" oninput="validarNumeroAcreedor()" class="form-control custom-input" id="numero_acreedor" placeholder="Ingrese el número de acreedor" name="usuario">
                </div>

                <div class="col-md-6 mt-5">
                    <label for="nombre_proveedor" class="floating-label">Nombre: *</label>
                    <input type="text" name="nombre_proveedor" value="<?php echo $proveedor['nombre_proveedor']; ?>" class="form-control custom-input" id="nombre_proveedor" placeholder="Ingrese el nombre del proveedor" required>
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
<<<<<<< HEAD
                                    <option value="<?php echo $MostrarCommodities['Id_commodity']; ?>"><?php echo $MostrarCommodities['descripcion_commodity'] ?></option>
=======
                                    <option value="<?php echo $MostrarCommodities['descripcion_commodity']; ?>"><?php echo $MostrarCommodities['descripcion_commodity'] ?></option>
>>>>>>> 8fe25a02a378af3db1c5f09c74bddd125a144800
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
                            <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault1" value="1" name="maneja_el_formato_CostBreakDown">
                            <label class="form-check-label" for="flexSwitchCheckDefault1">Si</label>
                        </div>
                        <span class="mx-2"></span>
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
                        <span class="mx-2"></span>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault4" value="0" name="formulario_ambiental">
                            <label class="form-check-label" for="flexSwitchCheckDefault4">No</label>
                        </div>
                    </div>
                </div>
<<<<<<< HEAD

                <div class="col-md-6 mt-5" id="hiddenIndirectos" style="display: none">
                    <label for="servicio_suministro">Servicios o Suministros: *</label>
                    <select id="servicio_suministro" name="servicios_suministros[]" class="form-control" multiple></select>
                </div>

=======
>>>>>>> 8fe25a02a378af3db1c5f09c74bddd125a144800
                <div class="col-md-6 mt-5">
                    <label for="correo_proveedor" class="floating-label">Correo Proveedor:*</label>
                    <input type="text" name="correo_proveedor" value="<?php echo $proveedor['correo_proveedor']; ?>" class="form-control custom-input" id="correo_proveedor" placeholder="Ingrese el correo del proveedor">
                </div>
<<<<<<< HEAD

=======
>>>>>>> 8fe25a02a378af3db1c5f09c74bddd125a144800
                <div class="col-md-6 mt-5">
                    <label>¿Autoriza Carta para Beneficiarios Finales?: *</label>
                    <div class="d-flex mt-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault5" value="1" name="carta_beneficiarios_finales">
                            <label class="form-check-label" for="flexSwitchCheckDefault5">Si</label>
                        </div>
                        <span class="mx-2"></span>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault6" value="0" name="carta_beneficiarios_finales">
                            <label class="form-check-label" for="flexSwitchCheckDefault6">No</label>
                        </div>
                    </div>
                </div>
<<<<<<< HEAD

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
                    <input type="hidden" name="logo_bd" class="form-control custom-input" id="logo_bd" style="display: none;">
                </div>

                <div class="col-md-6 mt-5" id="hiddenDirectos" style="display: none">
                    <label for="logo_proveedor" class="floating-label">Logo Proveedor: *</label>

                    <div class="logo-container">
                        <div id="logoPreview" class="logo-circle">
                            <i class="fas fa-camera placeholder-icon"></i>
                        </div>

                        <input type="file" name="logo_proveedor" class="form-control custom-input" id="logo_proveedor" accept="image/*" style="display: none;">
                    </div>
                </div>

=======
>>>>>>> 8fe25a02a378af3db1c5f09c74bddd125a144800
            </div>
        </form>
        <div class="text-end mt-5">
            <button class="btn-update">Actualizar</button>
<<<<<<< HEAD
=======
            <button id="cerrarFancybox" class="btn btn-secondary">Cerrar</button>
>>>>>>> 8fe25a02a378af3db1c5f09c74bddd125a144800
        </div>
    </div>
</body>

</html>

<script>
    $(document).ready(function() {

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

        var checkbox5 = document.getElementById("flexSwitchCheckDefault5");
        var checkbox6 = document.getElementById("flexSwitchCheckDefault6");

        checkbox5.addEventListener("change", function() {
            if (checkbox5.checked) {
                checkbox6.checked = false;
            }
        });

        checkbox6.addEventListener("change", function() {
            if (checkbox6.checked) {
                checkbox5.checked = false;
            }
        });

        var idioma = '<?php echo $proveedor['idioma_proveedor']; ?>';
        $('#idioma_proveedor').val(idioma);

        var tipo_proveedor = '<?php echo $proveedor['tipo_proveedor']; ?>';
        $('#tipo_proveedor').val(tipo_proveedor);

        let hiddenDirectos = document.querySelectorAll('#hiddenDirectos');
        let hiddenIndirectos = document.querySelectorAll('#hiddenIndirectos');

        if (tipo_proveedor === 'Directo') {
            hiddenDirectos.forEach(function(elemento) {
                elemento.style.display = 'block';
            });
            hiddenIndirectos.forEach(function(elemento) {
                elemento.style.display = 'none';
            });

<<<<<<< HEAD
            <?php $ruta_base = $proveedor['logo_proveedor']; ?>
            var logo_proveedor = '<?php echo $ruta_base ? $ruta_base . '?' . time() : ''; ?>';
            var logoPreview = document.getElementById('logoPreview');
            var logoInput = document.getElementById('logo_proveedor');

            if (logo_proveedor) {
                logoPreview.innerHTML = '<img src="' + logo_proveedor + '" alt="Logo Proveedor" class="logo-image">';
            } else {
                logoPreview.innerHTML = '<i class="fas fa-camera placeholder-icon"></i>';
            }

            logoPreview.addEventListener('click', function() {
                logoInput.click();
            });

            logoInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        logoPreview.innerHTML = '<img src="' + e.target.result + '" alt="Nuevo Logo" class="logo-image">';
                    }
                    reader.readAsDataURL(this.files[0]);
                } else if (!logo_proveedor) {
                    // Si cancela la selección y no había logo previo, vuelve al ícono
                    logoPreview.innerHTML = '<i class="fas fa-camera placeholder-icon"></i>';
                }
            });

            var Id_commodity = <?php echo json_encode($proveedor['id_commodity_proveedor']); ?>;
            $('#Commodity').val(Id_commodity);
            var historia_proveedor = <?php echo json_encode($proveedor['historia_proveedor']); ?>;
            $('#historia_proveedor').val(historia_proveedor);
            var descripcion_proveedor = <?php echo json_encode($proveedor['descripcion_proveedor']); ?>;
            $('#descripcion_proveedor').val(descripcion_proveedor);
            var porcentaje_bom_proveedor = '<?php echo $proveedor['porcentaje_bom_proveedor']; ?>';
            $('#porcentaje_bom_proveedor').val(porcentaje_bom_proveedor);
            var logo_bd = '<?php echo $proveedor['logo_proveedor']; ?>';
            $('#logo_bd').val(logo_bd);
            
=======
            var commodity_proveedor = '<?php echo $proveedor['commodity_proveedor']; ?>';
            $('#Commodity').val(commodity_proveedor);
>>>>>>> 8fe25a02a378af3db1c5f09c74bddd125a144800

            var maneja_formato_costbreakdown = '<?php echo $proveedor['maneja_formato_costbreakdown']; ?>';
            if (maneja_formato_costbreakdown == 1) {
                var flexSwitchCheckDefault1 = document.getElementById('flexSwitchCheckDefault1');
                flexSwitchCheckDefault1.checked = true;
            } else if (maneja_formato_costbreakdown == 0) {
                var flexSwitchCheckDefault2 = document.getElementById('flexSwitchCheckDefault2');
                flexSwitchCheckDefault2.checked = true;
            }
        } else {
            hiddenDirectos.forEach(function(elemento) {
                elemento.style.display = 'none';
            });
            hiddenIndirectos.forEach(function(elemento) {
                elemento.style.display = 'block';
            });

<<<<<<< HEAD
            cargarServiciosSuministros();

=======
>>>>>>> 8fe25a02a378af3db1c5f09c74bddd125a144800
            var Id_categoria = '<?php echo $proveedor['Id_categoria']; ?>';
            $('#categorias').val(Id_categoria);

            var cargarId = true
            cargarSubCategoria(cargarId);

            var id_sub_categoria = '<?php echo $proveedor['Id_sub_categoria']; ?>';
            $('#sub_categoria').val(id_sub_categoria);
        }

        var formulario_ambiental = '<?php echo $proveedor['formulario_ambiental']; ?>';
        if (formulario_ambiental == 1) {
            var flexSwitchCheckDefault3 = document.getElementById('flexSwitchCheckDefault3');
            flexSwitchCheckDefault3.checked = true;
        } else if (formulario_ambiental == 0) {
            var flexSwitchCheckDefault4 = document.getElementById('flexSwitchCheckDefault4');
            flexSwitchCheckDefault4.checked = true;
        }

        var carta_beneficiarios_finales = '<?php echo $proveedor['carta_beneficiarios_finales']; ?>';
        if (carta_beneficiarios_finales == 1) {
            var flexSwitchCheckDefault5 = document.getElementById('flexSwitchCheckDefault5');
            flexSwitchCheckDefault5.checked = true;
        } else if (carta_beneficiarios_finales == 0) {
            var flexSwitchCheckDefault6 = document.getElementById('flexSwitchCheckDefault6');
            flexSwitchCheckDefault6.checked = true;
        }
    });

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
    }

    function cargarSubCategoria(cargarId) {
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

                if (cargarId) {
                    var id_sub_categoria = '<?php echo $proveedor['Id_sub_categoria']; ?>';
                    if (id_sub_categoria) {
                        $('#sub_categoria').val(id_sub_categoria);
                    }
                }
            }
        };

        xhr.open("GET", "../../Actions/User/obtener_subcategorias.php?categoria=" + categoriaSeleccionada, true);
        xhr.send();
    }

<<<<<<< HEAD
    function cargarServiciosSuministros() {
        var Id_proveedor = '<?php echo $proveedor['Id_proveedor']; ?>';

        $.ajax({
            url: '../../Actions/User/consultarServicioSuministro_By__Id__Proveedor.php',
            method: 'POST',
            data: {
                id_proveedor: Id_proveedor
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    let opciones = response.opciones;
                    let seleccionadas = response.seleccionadas.map(String);

                    if ($('#servicio_suministro').data('select2')) {
                        $('#servicio_suministro').select2('destroy');
                    }
                    $('#servicio_suministro').empty();

                    opciones.forEach(function(item) {
                        $('#servicio_suministro').append(
                            `<option value="${item.id_servicio}">${item.servicio_suministro}</option>`
                        );
                    });

                    $('#servicio_suministro').select2({
                        placeholder: "Seleccione las opciones",
                        allowClear: true,
                        dropdownParent: $('#editarProveedorPartial')
                    });

                    $('#servicio_suministro').val(seleccionadas).trigger('change');
                    $('#servicio_suministro').select2('open').select2('close');
                } else {
                    alert('Error al cargar los datos');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error en la solicitud:', error);
            }
        });
    }
=======
    document.getElementById("cerrarFancybox").addEventListener("click", function() {
        $.fancybox.close();
    });
>>>>>>> 8fe25a02a378af3db1c5f09c74bddd125a144800

    $('.btn-update').on('click', function(e) {
        e.preventDefault();

<<<<<<< HEAD
        var form = $('#formEditarProveedor')[0]; 
        var formData = new FormData(form);
=======
        var formData = $('#formEditarProveedor').serialize();
>>>>>>> 8fe25a02a378af3db1c5f09c74bddd125a144800

        $.ajax({
            url: '../../Actions/User/actualizarProveedor.php',
            type: 'POST',
            data: formData,
<<<<<<< HEAD
            processData: false,
            contentType: false,
            success: function(response) {
                console.log(response);
=======
            success: function(response) {
>>>>>>> 8fe25a02a378af3db1c5f09c74bddd125a144800
                var result = JSON.parse(response);
                $.fancybox.close();
                if (result.success) {
                    window.location.href = "proveedoresRegistrados.php?success=true";
                }
            },
            error: function(xhr, status, error) {
                console.log('Error al actualizar el proveedor: ' + error);
            }
        });
    });
</script>