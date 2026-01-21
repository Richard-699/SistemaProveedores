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
    </style>
</head>

<body>

    <div id="editarServicioSuministroPartial" style="display: none; width: 50%;">
        <h2>Editar Servicio/Suministro</h2>
        <form id="formEditarServicioSuministro">
            <div class="row">
            <input type="hidden" value="<?php echo $result_servicioSuministro['id_servicio_suministro']; ?>" id="id_servicio_suministro" name="id_servicio_suministro">
                <div class="col-md-12 mt-5">
                    <label for="servicio_suministro" class="floating-label">Servicio o Suministro: *</label>
                    <input type="text" name="servicio_suministro" value="<?php echo $result_servicioSuministro['servicio_suministro']; ?>" class="form-control custom-input" id="servicio_suministro">
                </div>
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
    document.getElementById("cerrarFancybox").addEventListener("click", function() {
        $.fancybox.close();
    });

    $('.btn-update').on('click', function(e) {
        e.preventDefault();

        var formData = $('#formEditarServicioSuministro').serialize();
        debugger;
        $.ajax({
            url: '../../Actions/User/actualizarServicioSuministro.php',
            type: 'POST',
            data: formData,
            success: function(response) {
                console.log(response);
                var result = JSON.parse(response);
                $.fancybox.close();
                if (result.success) {
                    window.location.href = "serviciosSuministros.php?successUpdate=true";
                }
            },
            error: function(xhr, status, error) {
                console.log('Error al actualizar el proveedor: ' + error);
            }
        });
    });
</script>