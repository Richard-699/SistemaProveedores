<?php
session_start();
include('../../../ConexionBD/conexion.php');
$ruta = '../../../IdiomaConfig/' . $_SESSION['lang'] . '.php';
include($ruta);
$paises = mysqli_query($conexion, "SELECT * FROM pais");
?>
<section>
    <div class="card card-principal">
        <div class="card-header">
            <?php echo $lang['title_persona_natural'] ?>
        </div>
        <div class="card-body">
            <form id="formPersonaNatural" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6 mt-3">
                        <p><?php echo $lang['Nombres'] ?></p>
                        <input type="text" class="form-control" name="nombres_persona_natural" required>
                    </div>
                    <div class="col-md-6 mt-3">
                        <p><?php echo $lang['Apellidos'] ?></p>
                        <input type="text" class="form-control" name="apellidos_persona_natural" required>
                    </div>
                    <div class="col-md-6 mt-4">
                        <p><?php echo $lang['tipo_identificacion'] ?></p>
                        <select name="tipo_identificacion_persona_natural" id="tipo_identificacion__persona_natural" class="form-select" required>
                            <option value="" selected disabled><?php echo $lang['seleccionar_opcion'] ?></option>
                            <option value="Cedula Ciudadania"><?php echo $lang['cedula_ciudadania'] ?></option>
                            <option value="Cedula Extranjeria"><?php echo $lang['cedula_extranjeria'] ?></option>
                        </select>
                        <span class="error-message" style="display:none; color:red">Requerido.</span>
                    </div>
                    <div class="col-md-6 mt-4">
                        <p><?php echo $lang['numero_identificacion'] ?></p>
                        <input type="text" class="form-control" name="numero_identificacion_persona_natural" required oninput="validarNumero(this)">
                    </div>
                    <div class="col-md-6 mt-4">
                        <p><?php echo $lang['pais'] ?></p>
                        <select name="Id_pais_persona_natural" id="Id_pais_persona_natural" required class="form-select" onchange="updateIndicativo()">
                            <option value="" selected disabled><?php echo $lang['seleccionar_opcion'] ?></option>
                            <?php
                            while ($MostrarPaises = mysqli_fetch_array($paises)) {
                            ?>
                                <option value="<?php echo $MostrarPaises['id_pais']; ?>" data-indicativo="<?php echo $MostrarPaises['indicativo_pais']; ?>"><?php echo $MostrarPaises['pais'] ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-6 mt-4">
                        <p><?php echo $lang['departamento'] ?></p>
                        <input type="text" class="form-control" name="departamento_persona_natural" required>
                    </div>
                    <div class="col-md-6 mt-4">
                        <p><?php echo $lang['ciudad'] ?></p>
                        <input type="text" class="form-control" name="ciudad_persona_natural" required>
                    </div>
                    <div class="col-md-6 mt-3">
                        <p><?php echo $lang['direccion'] ?></p>
                        <input type="text" class="form-control" name="direccion_persona_natural" required>
                    </div>
                    <div class="col-md-2 mt-4">
                        <p><?php echo $lang['telefono'] ?></p>
                        <select name="indicativo_persona_natural" id="indicativo_persona_natural" class="form-control">
                            <?php
                            $indicativos = mysqli_query($conexion, "SELECT indicativo_pais FROM pais GROUP BY indicativo_pais ORDER BY indicativo_pais");
                            while ($MostrarPais = mysqli_fetch_array($indicativos)) {
                            ?>
                                <option value="<?php echo $MostrarPais['indicativo_pais']; ?>"><?php echo "+ " . $MostrarPais['indicativo_pais'] ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-4" style="margin-top: 63px;">
                        <input type="text" class="form-control" name="telefono_persona_natural" required oninput="validarNumero(this)">
                    </div>
                    <div class="col-md-6 mt-4">
                        <p><?php echo $lang['correo'] ?></p>
                        <input type="text" class="form-control" name="correo_electronico_persona_natural" required oninput="validarCorreo(this)">
                        <span class="error-message" style="display:none; color:red">Correo inválido.</span>
                    </div>
                    <div class="col-md-6 mt-4">
                        <p><?php echo $lang['sector_actividad'] ?></p>
                        <input type="text" class="form-control" name="sector_economico_persona_natural" required>
                    </div>
                    <div class="col-md-6 mt-4">
                        <p><?php echo $lang['condicion_pago'] ?></p>
                        <select name="condicion_pago" id="condicion_pago" class="form-select" onchange="toggleOtroField()" required>
                            <option value="" selected disabled><?php echo $lang['seleccionar_opcion'] ?></option>
                            <option value="Inmediato"><?php echo $lang['inmediato'] ?></option>
                            <option value="15"><?php echo "15 " . $lang['dias'] ?></option>
                            <option value="30"><?php echo "30 " . $lang['dias'] ?></option>
                            <option value="45"><?php echo "45 " . $lang['dias'] ?></option>
                            <option value="60"><?php echo "60 " . $lang['dias'] ?></option>
                            <option value="Otro"><?php echo $lang['otro'] ?></option>
                        </select>
                    </div>

                    <div class="col-md-6 mt-4" id="otroFieldContainer" style="display: none;">
                        <p><?php echo $lang['cuantos_dias'] ?></p>
                        <input type="text" class="form-control" name="cuantos_dias_condicion_pago" id="cuantos_dias_condicion_pago">
                    </div>
                    <div class="col-md-6 mt-4">
                        <p><?php echo $lang['requiere_permiso'] ?></p>
                        <div class="d-flex mt-4">
                            <input type="hidden" name="requiere_permiso_licencia_operar" id="requiere_permiso_licencia_operar">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="flexSwitchCheck1">
                                <label class="form-check-label" for="flexSwitchCheck1">Si</label>
                            </div>

                            <div class="form-check form-switch mlcheck">
                                <input class="form-check-input" type="checkbox" id="flexSwitchCheck2">
                                <label class="form-check-label" for="flexSwitchCheck2">No</label>
                            </div>
                        </div>
                    </div>

                    <div style="display: none;" class="col-md-6 mt-4" id="fileFieldContainer">
                        <p><?php echo $lang['adjunte_permiso_licencia'] ?></p>
                        <input type="file" class="form-control" name="doc_permiso_licencia_operar" id="doc_permiso_licencia_operar" accept=".pdf">
                        <p style="margin-top: 15px; color: red;" id="mensaje_error"></p>
                    </div>

                    <div class="col-md-6 mt-4" style="display: none;" id="filePreviewContainer">
                        <p><?php echo $lang['archivo_permiso_licencia'] ?> </p>
                        <div id="filePreviewContent"></div>
                        <button class="btn btn-success" id="modifyFileButton" type="button" style="display: none; margin-top: 10px;" onclick="showFileInput()">Modificar archivo</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<button id="next_personaNatural" class="btn btn-Laft-next"><?php echo $lang['siguiente'] ?></button>
<button id="back_personaNatural" class="btn btn-Laft-back"><?php echo $lang['anterior'] ?></button>

<script>
    $(document).ready(function() {
        var checkbox1 = document.getElementById("flexSwitchCheck1");
        var checkbox2 = document.getElementById("flexSwitchCheck2");

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

        $('#flexSwitchCheck1').change(function() {
            debugger;
            if ($(this).is(':checked')) {
                var modifyFileButton = document.getElementById('modifyFileButton');
                var filePreviewContainer = document.getElementById('filePreviewContainer');

                if (modifyFileButton !== null) {
                    if (modifyFileButton.display == 'none' || modifyFileButton.display == undefined) {
                        $('#fileFieldContainer').show();
                    }
                }
                if (modifyFileButton == null) {
                    $('#fileFieldContainer').show();
                }
                $('#flexSwitchCheck2').prop('checked', false);
                $('#requiere_permiso_licencia_operar').val(1);
                $('#doc_permiso_licencia_operar').attr("required", "required");
            } else {
                $('#fileFieldContainer').hide();
                $('#requiere_permiso_licencia_operar').val('');
            }
        });

        $('#flexSwitchCheck2').change(function() {
            if ($(this).is(':checked')) {
                $('#fileFieldContainer').hide();
                $('#flexSwitchCheck1').prop('checked', false);
                $('#requiere_permiso_licencia_operar').val(0);
                var modifyFileButton = document.getElementById('modifyFileButton');
                modifyFileButton.style.display = 'none';
                var filePreviewContainer = document.getElementById('filePreviewContainer');
                filePreviewContainer.style.display = 'none';
                $('#doc_permiso_licencia_operar').removeAttr("required", "required");
            } else {
                $('#requiere_permiso_licencia_operar').val('');
            }
        });
    });

    function updateIndicativo() {
        var paisSelect = document.getElementById("Id_pais_persona_natural");
        var indicativoSelect = document.getElementById("indicativo_persona_natural");

        var selectedOption = paisSelect.options[paisSelect.selectedIndex];
        var indicativo = selectedOption.getAttribute("data-indicativo");

        for (var i = 0; i < indicativoSelect.options.length; i++) {
            if (indicativoSelect.options[i].value == indicativo) {
                indicativoSelect.selectedIndex = i;
                break;
            }
        }
    }

    function toggleOtroField() {
        var select = document.getElementById("condicion_pago");
        var otroFieldContainer = document.getElementById("otroFieldContainer");
        var otroFieldInput = document.getElementById("cuantos_dias_condicion_pago");

        if (select.value === "Otro") {
            otroFieldContainer.style.display = "block";
            otroFieldInput.setAttribute("required", "required");
        } else {
            otroFieldContainer.style.display = "none";
            otroFieldInput.removeAttribute("required");
        }
    }

    function validarNumero(input) {
        var value = input.value.replace(/\D/g, '');
        input.value = value;
    }

    function validarCorreo(input) {
        var value = input.value;
        var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
        var isValid = emailPattern.test(value);

        if (isValid) {
            $(input).removeClass('is-invalid').addClass('is-valid');
            $(input).siblings('.error-message').hide();
        } else {
            $(input).removeClass('is-valid').addClass('is-invalid');
            $(input).siblings('.error-message').show();
        }
    }

    function showFileInput(event) {
        if (event) {
            event.preventDefault();
        }

        var fileFieldContainer = document.getElementById("fileFieldContainer");
        var filePreviewContainer = document.getElementById("filePreviewContainer");
        var modifyFileButton = document.getElementById("modifyFileButton");

        fileFieldContainer.style.display = "block";
        filePreviewContainer.innerHTML = '';
        modifyFileButton.style.display = "none";
    }

    document.getElementById('doc_permiso_licencia_operar').addEventListener('change', function(event) {
        var fileInput = event.target;
        var file = fileInput.files[0];

        if (file) {
            if (file.type !== 'application/pdf') {
                document.getElementById('mensaje_error').textContent = 'Solo se permiten archivos PDF.';
                fileInput.value = '';
            }
        }
    });
</script>