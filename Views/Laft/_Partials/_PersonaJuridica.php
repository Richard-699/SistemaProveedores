<?php
session_start();
include('../../../ConexionBD/conexion.php');
$ruta = '../../../IdiomaConfig/' . $_SESSION['lang'] . '.php';
include($ruta);
$paises = mysqli_query($conexion, "SELECT * FROM pais");
include '../../spinner.html'; 
?>
<section>
    <div class="card card-principal">
        <div class="card-header">
            <?php echo $lang['title_persona_juridica'] ?>
        </div>
        <div class="card-body">
            <form id="formPersonaJuridica" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6 mt-4">
                        <p><?php echo $lang['razon_social'] ?></p>
                        <input type="text" class="form-control" name="razon_social_persona_juridica" required>
                    </div>
                    <div class="col-md-6 mt-4">
                        <p><?php echo $lang['tipo_identificacion'] ?></p>
                        <select name="tipo_identificacion_persona_juridica" id="tipo_identificacion_persona_juridica" class="form-select" required>
                            <option value="" selected disabled><?php echo $lang['seleccionar_opcion'] ?></option>
                            <option value="NIT">NIT</option>
                            <option value="Otro"><?php echo $lang['otro'] ?></option>
                        </select>
                        <span class="error-message" style="display:none; color:red">Requerido.</span>
                    </div>
                    <div class="col-md-6 mt-4" id="otroTipoIdentificacionContainer" style="display:none;">
                        <p><?php echo $lang['otro_tipo_identificacion'] ?></p>
                        <input type="text" class="form-control" name="otro_tipo_identificacion" id="otro_tipo_identificacion">
                    </div>
                    <div class="col-md-6 mt-4">
                        <p><?php echo $lang['numero_identificacion'] ?></p>
                        <input type="text" class="form-control" id="numero_identificacion_persona_juridica" name="numero_identificacion_persona_juridica" required oninput="validarNumero(this)">
                    </div>
                    <div class="col-md-6 mt-4">
                        <p><?php echo $lang['digito_verificacion'] ?></p>
                        <input type="text" class="form-control" id="digito_verificacion" name="digito_verificacion" oninput="validarNumero(this)" required>
                    </div>
                    <div class="col-md-6 mt-4">
                        <p><?php echo $lang['pais'] ?></p>
                        <select name="Id_pais_persona_juridica" id="Id_pais_persona_juridica" required class="form-select" onchange="updateIndicativo()">
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
                        <input type="text" class="form-control" name="departamento_persona_juridica">
                    </div>
                    <div class="col-md-6 mt-4">
                        <p><?php echo $lang['ciudad'] ?></p>
                        <input type="text" class="form-control" name="ciudad_persona_juridica">
                    </div>
                    <div class="col-md-2 mt-4">
                        <p><?php echo $lang['telefono'] ?></p>
                        <select name="indicativo_persona_juridica" id="indicativo_persona_juridica" class="form-control">
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
                        <input type="text" class="form-control" name="telefono_persona_juridica" required oninput="validarNumero(this)">
                    </div>
                    <div class="col-md-6 mt-4">
                        <p><?php echo $lang['correo'] ?></p>
                        <input type="text" class="form-control" name="correo_electronico_persona_juridica" required oninput="validarCorreo(this)">
                    </div>
                    <div class="col-md-6 mt-3">
                        <p><?php echo $lang['direccion'] ?></p>
                        <input type="text" class="form-control" name="direccion_persona_juridica" required>
                    </div>
                    <div class="col-md-6 mt-4">
                        <p><?php echo $lang['codigo_ciiu'] ?></p>
                        <input type="text" class="form-control" name="codigo_ciiu_persona_juridica" required>
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
                        <input type="text" class="form-control" name="cuantos_dias_condicion_pago" id="cuantos_dias_condicion_pago" oninput="validarNumero(this)">
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

                    <div class="col-md-12 mt-4">
                        <p><?php echo $lang['certificacion'] ?></p>
                    </div>
                    <div class="col-md-12 mt-4 d-flex align-items-center">
                        <input type="checkbox" class="file-checkbox" id="ISO_9001" data-file="ISO_9001">
                        <p class="mb-0 custom-margin-2">ISO 9001</p>
                        <input name="ISO_9001" type="hidden">
                    </div>
                    <div class="col-md-12 mt-4" id="file_ISO_9001" style="display: none;">
                        <input type="file" class="form-control file-input" name="doc_ISO_9001" id="doc_ISO_9001" accept=".pdf">
                        <div style="color: red; margin-top: 15px;" id="error_ISO_9001" style="color: red; display: none;"></div>
                    </div>
                    <div class="col-md-6 mt-4" style="display: none;" id="filePreviewContainerISO_9001">
                        <div id="filePreviewContentISO_9001"></div>
                        <button class="btn btn-success" id="modifyFileButtonISO_9001" type="button" style="display: none; margin-top: 10px;" onclick="showFileInputId(this)">Modificar archivo</button>
                    </div>
                    <div class="col-md-12 mt-4 d-flex align-items-center">
                        <input type="hidden" name="ISO_14001">
                        <input type="checkbox" id="ISO_14001" class="file-checkbox" data-file="ISO_14001">
                        <p class="mb-0 custom-margin-2">ISO 14001</p>
                    </div>
                    <div class="col-md-12 mt-4" id="file_ISO_14001" style="display: none;">
                        <input type="file" class="form-control file-input" name="doc_ISO_14001" id="doc_ISO_14001" accept=".pdf">
                        <div style="color: red; margin-top: 15px;" id="error_ISO_14001" style="color: red; display: none;"></div>
                    </div>
                    <div class="col-md-6 mt-4" style="display: none;" id="filePreviewContainerISO_14001">
                        <div id="filePreviewContentISO_14001"></div>
                        <button class="btn btn-success" id="modifyFileButtonISO_14001" type="button" style="display: none; margin-top: 10px;" onclick="showFileInputId(this)">Modificar archivo</button>
                    </div>
                    <div class="col-md-12 mt-4 d-flex align-items-center">
                        <input type="hidden" name="ISO_45001">
                        <input type="checkbox" id="ISO_45001" class="file-checkbox" data-file="ISO_45001">
                        <p class="mb-0 custom-margin-2">ISO 45001</p>
                    </div>
                    <div class="col-md-12 mt-4" id="file_ISO_45001" style="display: none;">
                        <input type="file" class="form-control" name="doc_ISO_45001" id="doc_ISO_45001" accept=".pdf">
                        <div style="color: red; margin-top: 15px;" id="error_ISO_45001" style="color: red; display: none;"></div>
                    </div>
                    <div class="col-md-6 mt-4" style="display: none;" id="filePreviewContainerISO_45001">
                        <div id="filePreviewContentISO_45001"></div>
                        <button class="btn btn-success" id="modifyFileButtonISO_45001" type="button" style="display: none; margin-top: 10px;" onclick="showFileInputId(this)">Modificar archivo</button>
                    </div>
                    <div class="col-md-12 mt-4 d-flex align-items-center">
                        <input type="hidden" name="BASC">
                        <input type="checkbox" id="BASC" class="file-checkbox" data-file="BASC">
                        <p class="mb-0 custom-margin-2">BASC</p>
                    </div>
                    <div class="col-md-12 mt-4" id="file_BASC" style="display: none;">
                        <input type="file" class="form-control" name="doc_BASC" id="doc_BASC" accept=".pdf">
                        <div style="color: red; margin-top: 15px;" id="error_BASC" style="color: red; display: none;"></div>
                    </div>
                    <div class="col-md-6 mt-4" style="display: none;" id="filePreviewContainerBASC">
                        <div id="filePreviewContentBASC"></div>
                        <button class="btn btn-success" id="modifyFileButtonBASC" type="button" style="display: none; margin-top: 10px;" onclick="showFileInputId(this)">Modificar archivo</button>
                    </div>
                    <div class="col-md-12 mt-4 d-flex align-items-center">
                        <input type="hidden" name="OEA">
                        <input type="checkbox" id="OEA" class="file-checkbox" data-file="OEA">
                        <p class="mb-0 custom-margin-2">OEA</p>
                    </div>
                    <div class="col-md-12 mt-4" id="file_OEA" style="display: none;">
                        <input type="file" class="form-control" name="doc_OEA" id="doc_OEA" accept=".pdf">
                        <div style="color: red; margin-top: 15px;" id="error_OEA" style="color: red; display: none;"></div>
                    </div>
                    <div class="col-md-6 mt-4" style="display: none;" id="filePreviewContainerOEA">
                        <div id="filePreviewContentOEA"></div>
                        <button class="btn btn-success" id="modifyFileButtonOEA" type="button" style="display: none; margin-top: 10px;" onclick="showFileInputId(this)">Modificar archivo</button>
                    </div>
                    <div class="col-md-12 mt-4 d-flex align-items-center">
                        <input type="hidden" name="Otro">
                        <input type="checkbox" id="Otro" class="file-checkbox" data-file="Otro">
                        <p class="mb-0 custom-margin-2"><?php echo $lang['otro'] ?></p>
                    </div>
                    <div class="col-md-12 mt-4" id="otroFieldContainer2" style="display: none;">
                        <p><?php echo $lang['cual'] ?></p>
                        <input type="text" class="form-control" name="otro_certificacion" id="otro_certificacion">
                    </div>
                    <div class="col-md-12 mt-4" id="file_Otro" style="display: none;">
                        <input type="file" class="form-control" name="doc_Otro" id="doc_Otro" accept=".pdf">
                        <div style="color: red; margin-top: 15px;" id="error_Otro" style="color: red; display: none;"></div>
                    </div>
                    <div class="col-md-6 mt-4" style="display: none;" id="filePreviewContainerOtro">
                        <div id="filePreviewContentOtro"></div>
                        <button class="btn btn-success" id="modifyFileButtonOtro" type="button" style="display: none; margin-top: 10px;" onclick="showFileInputId(this)">Modificar archivo</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<button id="next_personaJuridica" class="btn btn-Laft-next"><?php echo $lang['siguiente'] ?></button>
<button id="back_personaJuridica" class="btn btn-Laft-back"><?php echo $lang['anterior'] ?></button>

<script>
    $(document).ready(function() {

        $('input[type="checkbox"]').change(function() {
            var name = $(this).attr('id');
            debugger;
            if ($(this).is(':checked')) {
                $('input[name="' + name + '"]').val('1');
                $('#file_' + name).show();
                if (name == 'flexSwitchCheck1') {
                    $('#doc_permiso_licencia_operar').attr("required", "required");
                } else {
                    $('#doc_' + name).attr("required", "required");
                }

                if (name == 'flexSwitchCheck2') {
                    $('#doc_permiso_licencia_operar').removeAttr("required", "required");
                }
            } else {
                $('input[name="' + name + '"]').val('0');
                $('#file_' + name).hide();
                if (name == 'flexSwitchCheck1') {
                    $('#doc_permiso_licencia_operar').removeAttr("required", "required");
                } else {
                    $('#doc_' + name).removeAttr("required");
                }
            }
        });

        $('#Otro').change(function() {
            if ($(this).is(':checked')) {
                $('#otroFieldContainer2').show();
                $('#otro_certificacion').attr("required", "required");
            } else {
                $('#otroFieldContainer2').hide();
                $('#otro_certificacion').val("");
                $('#otro_certificacion').removeAttr("required");
            }
        });

        $('#tipo_identificacion_persona_juridica').change(function() {
            var otro_tipo_identificacion = document.getElementById('otro_tipo_identificacion');
            if ($(this).val() === 'Otro') {
                $('#otroTipoIdentificacionContainer').show();
                otro_tipo_identificacion.setAttribute("required", "required");
            } else {
                $('#otroTipoIdentificacionContainer').hide();
                otro_tipo_identificacion.removeAttribute("required");
            }
        });

        $('#flexSwitchCheck1').change(function() {
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
            } else {
                $('#requiere_permiso_licencia_operar').val('');
            }
        });
    });

    function updateIndicativo() {
        var paisSelect = document.getElementById("Id_pais_persona_juridica");
        var indicativoSelect = document.getElementById("indicativo_persona_juridica");

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

    document.querySelectorAll('.file-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', (event) => {
            const fileId = event.target.getAttribute('data-file');
            const fileContainer = document.getElementById(`file_${fileId}`);
            const fileInput = document.getElementById(`doc_${fileId}`);
            const filePreviewContainer = document.getElementById(`filePreviewContainer${fileId}`);
            const modifyFileButton = document.querySelector(`#modifyFileButton${fileId}`);

            if (event.target.checked) {
                fileContainer.style.display = 'block';
                fileInput.addEventListener('change', (e) => handleFileInput(e, filePreviewContainer, modifyFileButton));
            } else {
                fileContainer.style.display = 'none';
                filePreviewContainer.style.display = 'none';
                modifyFileButton.style.display = 'none';
                fileInput.value = '';
            }
        });
    });

    function handleFileInput(event, previewContainer, modifyButton) {
        const file = event.target.files[0];
        const fileId = event.target.id.replace('doc_', '');
        const errorMessageContainer = document.getElementById(`error_${fileId}`);

        if (file && file.type === 'application/pdf') {
            previewContainer.style.display = 'block';
            modifyButton.style.display = 'block';
            errorMessageContainer.style.display = 'none';

            const reader = new FileReader();
            reader.onload = (e) => {
                const previewContent = document.createElement('p');
                previewContent.textContent = file.name;
                previewContainer.innerHTML = '';
                previewContainer.appendChild(previewContent);
            };
            reader.readAsDataURL(file);
        } else {
            event.target.value = '';
            errorMessageContainer.style.display = 'block';
            errorMessageContainer.textContent = 'Solo se permiten archivos PDF.';
            previewContainer.style.display = 'none';
            modifyButton.style.display = 'none';
        }
    }

    function showFileInputId(fileId) {
        debugger;
        var newfileid = fileId.id.replace('modifyFileButton', '');

        var fileFieldContainer = document.getElementById("file_" + newfileid);
        var filePreviewContainer = document.getElementById("filePreviewContainer" + newfileid);
        var modifyFileButton = document.getElementById("modifyFileButton" + newfileid);

        fileFieldContainer.style.display = "block";
        filePreviewContainer.innerHTML = '';
        modifyFileButton.style.display = "none";
        $('#doc_' + newfileid).attr("required", "required");
    }
</script>