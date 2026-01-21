<html>

<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.5/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.5/dist/sweetalert2.min.js"></script>
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

        .logo-container {
            display: flex;
            justify-content: left;
            margin-top: 15px;
        }

        .logo-circle {
            width: 160px;
            height: 150px;
            border-radius: 30%;
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

    <div id="editarPartNumberPartial" style="display: none; width: 75%;">
        <h2>Editar Part Number</h2>
        <form id="formEditarPartNumber">
<<<<<<< HEAD
            <input type="hidden" name="ruta_imagen_anterior" value="<?php echo $result_partnumber['imagen_partnumber']; ?>">
=======
>>>>>>> 8fe25a02a378af3db1c5f09c74bddd125a144800
            <div class="row">
                <div class="col-md-6 mt-5">
                    <label for="partnumber" class="floating-label">Part Number: *</label>
                    <input type="text" name="partnumber" value="<?php echo $result_partnumber['partnumber']; ?>" class="form-control custom-input" id="partnumber" placeholder="Ingrese el part number" name="partnumber">
                </div>

                <div class="col-md-6 mt-5">
                    <label for="descripcion_partnumber" class="floating-label">Descripción: *</label>
                    <input type="text" name="descripcion_partnumber" value="<?php echo $result_partnumber['descripcion_partnumber']; ?>" class="form-control custom-input" id="descripcion_partnumber" placeholder="Ingrese la descripción" required>
                </div>
                <div class="col-md-6 mt-4">
                    <div class="form-group">
                        <label for="Commodity" class="floating-label">Commodity: *</label>
                        <select id="Commodity" class="form-select custom-input" name="commodity_partnumber">
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
<<<<<<< HEAD
                <div class="col-md-6 mt-4">
                    <label for="descripcion_partnumber" class="floating-label">% Peso BOM: *</label>
                    <input type="text" name="porcentaje_peso_bom_partnumber" value="<?php echo $result_partnumber['porcentaje_peso_bom_partnumber']; ?>" class="form-control custom-input" id="porcentaje_peso_bom_partnumber" placeholder="Ingrese el porcentaje" required>
                </div>
                <div class="col-md-6 mt-4">
                    <label for="imagen_partnumber" class="floating-label">Imagen PartNumber: *</label>

                    <div class="logo-container">
                        <div id="imagePreviewPartNumber" class="logo-circle">
                            <i class="fas fa-camera placeholder-icon"></i>
                        </div>

                        <input type="file" name="imagen_partnumber" class="form-control custom-input" id="imagen_partnumber_input" accept="image/*" style="display: none;">
                    </div>
                </div>
=======
>>>>>>> 8fe25a02a378af3db1c5f09c74bddd125a144800
            </div>
        </form>
        <div class="text-end mt-5">
            <button class="btn-update">Actualizar</button>
            <button id="cerrarFancybox" class="btn btn-secondary">Cerrar</button>
        </div>
    </div>
</body>

</html>

<script>
    $(document).ready(function() {
        var commodity_partnumber = '<?php echo $result_partnumber['commodity_partnumber']; ?>';
        $('#Commodity').val(commodity_partnumber);
<<<<<<< HEAD

        <?php $ruta_base = $result_partnumber['imagen_partnumber']; ?>
        var rutaImagenActual = '<?php echo $ruta_base ? $ruta_base . '?' . time() : ''; ?>';

        var imagePreviewDiv = document.getElementById('imagePreviewPartNumber');
        var imageInput = document.getElementById('imagen_partnumber_input');
        
        if (rutaImagenActual) {
            imagePreviewDiv.innerHTML = '<img src="' + rutaImagenActual + '" alt="Imagen PN" class="logo-image">';
        } else {
            imagePreviewDiv.innerHTML = '<i class="fas fa-camera placeholder-icon"></i>';
        }

        imagePreviewDiv.addEventListener('click', function() {
            imageInput.click();
        });

        imageInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    imagePreviewDiv.innerHTML = '<img src="' + e.target.result + '" alt="Nueva Imagen PN" class="logo-image">';
                }
                reader.readAsDataURL(this.files[0]);
            } else if (!rutaImagenActual) {
                imagePreviewDiv.innerHTML = '<i class="fas fa-camera placeholder-icon"></i>';
            }
        });
    });
    
=======
    });

>>>>>>> 8fe25a02a378af3db1c5f09c74bddd125a144800
    document.getElementById("cerrarFancybox").addEventListener("click", function() {
        $.fancybox.close();
    });

    $('.btn-update').on('click', function(e) {
        e.preventDefault();

<<<<<<< HEAD
        var form = $('#formEditarPartNumber')[0]; 
        var formData = new FormData(form);
=======
        var formData = $('#formEditarPartNumber').serialize();
>>>>>>> 8fe25a02a378af3db1c5f09c74bddd125a144800

        $.ajax({
            url: '../../Actions/User/actualizarPartNumber.php',
            type: 'POST',
            data: formData,
<<<<<<< HEAD
            processData: false, 
            contentType: false,
=======
>>>>>>> 8fe25a02a378af3db1c5f09c74bddd125a144800
            success: function(response) {
                var result = JSON.parse(response);
                $.fancybox.close();
                if (result.success) {
                    window.location.href = "partNumbers.php?success=true";
                }
            },
            error: function(xhr, status, error) {
                console.log('Error al actualizar el proveedor: ' + error);
            }
        });
    });
</script>