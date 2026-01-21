<?php
session_start();

$ruta = '../../../IdiomaConfig/' . $_SESSION['lang'] . '.php';
include($ruta);
include('../../../ConexionBD/conexion.php');

$Id_proveedor_laft = $_SESSION['id_proveedor_usuarios'];
$consultaLaft = $conexion->prepare("SELECT * FROM laft WHERE Id_proveedor_laft = ?");
$consultaLaft->bind_param("s", $Id_proveedor_laft);
$consultaLaft->execute();
$resultado = $consultaLaft->get_result();
$tipo_proveedor = $_SESSION['tipo_proveedor'];

if ($resultado && $resultado->num_rows > 0) {
    $row = $resultado->fetch_assoc();
    $Id_laft = $row['Id_laft'];
    $tipo_persona_laft = $row['tipo_persona_laft'];
} else {
    $Id_laft = null;
    $tipo_persona_laft = null;
}

if ($tipo_persona_laft == "Juridica") {
    $consultaNacionalidad = $conexion->prepare("SELECT pais.pais FROM laft_persona_juridica
                        INNER JOIN pais ON laft_persona_juridica.Id_pais_persona_juridica = pais.id_pais
                        WHERE Id_laft_persona_juridica = ?");
    $consultaNacionalidad->bind_param("i", $Id_laft);
    $consultaNacionalidad->execute();
    $resultadoNacionalidad = $consultaNacionalidad->get_result();
}
if ($tipo_persona_laft == "Natural") {
    $consultaNacionalidad = $conexion->prepare("SELECT pais.pais FROM laft_persona_natural
    INNER JOIN pais ON laft_persona_natural.Id_pais_persona_natural = pais.id_pais
    WHERE id_laft_persona_natural = ?");
    $consultaNacionalidad->bind_param("i", $Id_laft);
    $consultaNacionalidad->execute();
    $resultadoNacionalidad = $consultaNacionalidad->get_result();
}

$row = $resultadoNacionalidad->fetch_assoc();
$nacionalidad = $row['pais'];

$consultaProveedor = $conexion->prepare("SELECT * FROM proveedores WHERE Id_proveedor = ?");
$consultaProveedor->bind_param("s", $Id_proveedor_laft);
$consultaProveedor->execute();
$resultadoProveedor = $consultaProveedor->get_result();
$row = $resultadoProveedor->fetch_assoc();
$formulario_ambiental = $row['formulario_ambiental'];
?>
<section>

    <div class="card card-principal">
        <div class="card-header">
            <?php echo $lang['documentacion'] ?>
        </div>
        <div class="card-body">
            <form id="formDocumentacion" enctype="multipart/form-data">
                <div class="row">

                    <div class="col-md-12 mt-4" id="file_copia_cedula_representante_legal">
                        <p class="mb-0 custom-margin-2">ID o Cédula del Representante Legal: *</p>
                        <input type="file" class="form-control file-input mt-4" name="copia_cedula_representante_legal" id="copia_cedula_representante_legal" accept=".pdf" required>
                        <div style="color: red; margin-top: 15px;" id="error_copia_cedula_representante_legal" style="color: red; display: none;"></div>
                    </div>
                    <div class="col-md-12 mt-4" style="display: none;" id="filePreviewContainercopia_cedula_representante_legal">
                        <p class="mb-0 custom-margin-2">ID o Cédula del Representante Legal: *</p>
                        <div id="filePreviewContentcopia_cedula_representante_legal" class="mt-4"></div>
                        <button class="btn btn-success" id="modifyFileButtoncopia_cedula_representante_legal" type="button" style="display: none; margin-top: 10px;" onclick="showFileInputId(this)">Modificar archivo</button>
                    </div>

                    <?php
                    if ($tipo_persona_laft == "Natural") {
                    ?>
                        <div class="col-md-12 mt-4" id="file_certificado_afiliacion">
                            <p class="mb-0 custom-margin-2"><?php echo $lang['certificado_afiliacion'] ?></p>
                            <input type="file" class="form-control file-input mt-4" name="certificado_afiliacion" id="certificado_afiliacion" accept=".pdf" required>
                            <div style="color: red; margin-top: 15px;" id="error_certificado_afiliacion" style="color: red; display: none;"></div>
                        </div>
                        <div class="col-md-12 mt-4" style="display: none;" id="filePreviewContainercertificado_afiliacion">
                            <p class="mb-0 custom-margin-2"><?php echo $lang['certificado_afiliacion'] ?></p>
                            <div id="filePreviewContentcertificado_afiliacion" class="mt-4"></div>
                            <button class="btn btn-success" id="modifyFileButtoncertificado_afiliacion" type="button" style="display: none; margin-top: 10px;" onclick="showFileInputId(this)">Modificar archivo</button>
                        </div>

                        <div class="col-md-12 mt-4" id="file_carta_personas_cargo">
                            <p class="mb-0 custom-margin-2"><?php echo $lang['carta_personas_cargo'] ?></p>
                            <input type="file" class="form-control file-input mt-4" name="carta_personas_cargo" id="carta_personas_cargo" accept=".pdf">
                            <div style="color: red; margin-top: 15px;" id="error_carta_personas_cargo" style="color: red; display: none;"></div>
                        </div>
                        <div class="col-md-12 mt-4" style="display: none;" id="filePreviewContainercarta_personas_cargo">
                            <p class="mb-0 custom-margin-2"><?php echo $lang['carta_personas_cargo'] ?></p>
                            <div id="filePreviewContentcarta_personas_cargo" class="mt-4"></div>
                            <button class="btn btn-success" id="modifyFileButtoncarta_personas_cargo" type="button" style="display: none; margin-top: 10px;" onclick="showFileInputId(this)">Modificar archivo</button>
                        </div>
                    <?php } ?>

                    <?php if ($nacionalidad == "Colombia") { ?>

                        <div class="col-md-12 mt-4" id="file_RUT">
                            <p class="mb-0 custom-margin-2">RUT: *</p>
                            <input type="file" class="form-control file-input mt-4" name="RUT" id="RUT" accept=".pdf" required>
                            <div style="color: red; margin-top: 15px;" id="error_RUT" style="color: red; display: none;"></div>
                        </div>
                        <div class="col-md-12 mt-4" style="display: none;" id="filePreviewContainerRUT">
                            <p class="mb-0 custom-margin-2">RUT: *</p>
                            <div id="filePreviewContentRUT" class="mt-4"></div>
                            <button class="btn btn-success" id="modifyFileButtonRUT" type="button" style="display: none; margin-top: 10px;" onclick="showFileInputId(this)">Modificar archivo</button>
                        </div>
                        <?php if ($tipo_persona_laft == "Juridica") { ?>
                            <div class="col-md-12 mt-4" id="file_camara_comercio">
                                <p class="mb-0 custom-margin-2">Cámara de Comercio: *</p>
                                <input type="file" class="form-control file-input mt-4" name="camara_comercio" id="camara_comercio" accept=".pdf" required>
                                <div style="color: red; margin-top: 15px;" id="error_camara_comercio" style="color: red; display: none;"></div>
                            </div>

                            <div class="col-md-12 mt-4" style="display: none;" id="filePreviewContainercamara_comercio">
                                <p class="mb-0 custom-margin-2">Cámara de Comercio: *</p>
                                <div id="filePreviewContentcamara_comercio" class="mt-4"></div>
                                <button class="btn btn-success" id="modifyFileButtoncamara_comercio" type="button" style="display: none; margin-top: 10px;" onclick="showFileInputId(this)">Modificar archivo</button>
                            </div>
                        <?php } ?>

                    <?php } ?>

                    <div class="col-md-12 mt-4" id="file_certificacion_bancaria">
                        <p class="mb-0 custom-margin-2"><?php echo $lang['certificacion_bancaria'] ?></p>
                        <input type="file" class="form-control file-input mt-4" name="certificacion_bancaria" id="certificacion_bancaria" accept=".pdf" required>
                        <div style="color: red; margin-top: 15px;" id="error_certificacion_bancaria" style="color: red; display: none;"></div>
                    </div>
                    <div class="col-md-12 mt-4" style="display: none;" id="filePreviewContainercertificacion_bancaria">
                        <p class="mb-0 custom-margin-2"><?php echo $lang['certificacion_bancaria'] ?></p>
                        <div id="filePreviewContentcertificacion_bancaria" class="mt-4"></div>
                        <button class="btn btn-success" id="modifyFileButtoncertificacion_bancaria" type="button" style="display: none; margin-top: 10px;" onclick="showFileInputId(this)">Modificar archivo</button>
                    </div>

                    <?php
                    if ($tipo_proveedor == "Indirecto") {
                    ?>
                        <div class="mb-3 mt-4">
                            <p><?php echo $lang['portafolio_servicios'] ?></p>
                            <div class="row">
                                <div class="col-md-12">
                                    <input type="hidden" id="portafolioServicios" name="portafolioServicios">
                                    <div class="form-check form-switch d-inline-block me-3">
                                        <input class="form-check-input" type="radio" name="portafolioOptions" id="flexSwitchCheckSi" onchange="toggleOptions(this)" required>
                                        <label class="form-check-label" for="flexSwitchCheckSi">Sí</label>
                                    </div>

                                    <div class="form-check form-switch d-inline-block">
                                        <input class="form-check-input" type="radio" name="portafolioOptions" id="flexSwitchCheckNo" onchange="toggleOptions(this)" required>
                                        <label class="form-check-label" for="flexSwitchCheckNo">No</label>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4" id="additionalOptions" style="display:none;">
                                <p><?php echo $lang['tipo_portafolio'] ?></p>
                                <input type="hidden" id="tipo_portafolio" name="tipo_portafolio">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="checkboxUrl" onchange="toggleUrlOrPdf('url')" required>
                                    <label class="form-check-label" for="checkboxUrl">URL</label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="checkboxPdf" onchange="toggleUrlOrPdf('pdf')" required>
                                    <label class="form-check-label" for="checkboxPdf">PDF</label>
                                </div>

                                <div id="urlInput" style="display:none;" class="mt-4">
                                    <label for="inputUrl" class="form-label"><?php echo $lang['url_portafolio'] ?></label>
                                    <input type="text" class="form-control" id="inputUrl" name="urlPortafolio" placeholder="https://ejemplo.com/portafolio">
                                </div>

                                <div class="col-md-12 mt-4" id="pdfInput" style="display:none;">
                                    <p class="mb-0 custom-margin-2"><?php echo $lang['pdf_portafolio'] ?></p>
                                    <input type="file" class="form-control file-input mt-4" name="pdf_portafolio" id="pdf_portafolio" accept=".pdf" required>
                                    <div style="color: red; margin-top: 15px;" id="error_pdf_portafolio" style="color: red; display: none;"></div>
                                </div>
                                <div class="col-md-12 mt-4" style="display: none;" id="filePreviewContainerpdf_portafolio">
                                    <p class="mb-0 custom-margin-2"><?php echo $lang['pdf_portafolio'] ?></p>
                                    <div id="filePreviewContentpdf_portafolio" class="mt-4"></div>
                                    <button class="btn btn-success" id="modifyFileButtonpdf_portafolio" type="button" style="display: none; margin-top: 10px;" onclick="showFileInputId(this)">Modificar archivo</button>
                                </div>
                            </div>
                        </div>

                    <?php
                    }
                    ?>
                </div>
            </form>
        </div>
    </div>
</section>
<button id="back_documentacion" class="btn btn-Laft-back"><?php echo $lang['anterior'] ?></button>

<?php if ($formulario_ambiental == 1) { ?>
    <button id="next_documentacion" class="btn btn-Laft-next"><?php echo $lang['siguiente'] ?></button>
<?php } else { ?>
    <button id="next_documentacion" class="btn btn-Laft-next"><?php echo $lang['finalizar'] ?></button>
<?php } ?>


<script>
    $(document).ready(function() {
        const fileInputs = document.querySelectorAll('.file-input');

        fileInputs.forEach(function(input) {
            input.addEventListener('change', function(event) {
                const file = event.target.files[0];
                const fileExtension = file ? file.name.split('.').pop().toLowerCase() : '';
                const errorContainer = document.getElementById('error_' + event.target.id);

                if (fileExtension !== 'pdf') {
                    event.target.value = '';
                    errorContainer.style.display = 'block';
                    errorContainer.textContent = 'Solo se permiten archivos PDF.';
                } else {
                    errorContainer.style.display = 'none';
                    errorContainer.textContent = '';

                }
            });
        });
    });

    function showFileInputId(fileId) {

        var newfileid = fileId.id.replace('modifyFileButton', '');

        if (newfileid != "pdf_portafolio") {
            var fileFieldContainer = document.getElementById("file_" + newfileid);
        } else {
            var fileFieldContainer = document.getElementById("pdfInput");
        }

        var filePreviewContainer = document.getElementById("filePreviewContainer" + newfileid);
        var modifyFileButton = document.getElementById("modifyFileButton" + newfileid);

        fileFieldContainer.style.display = "block";
        filePreviewContainer.innerHTML = '';
        filePreviewContainer.style.display = "none";
        modifyFileButton.style.display = "none";
        $('#' + newfileid).attr("required", "required");
    }

    function toggleOptions(checkbox) {
        const additionalOptions = document.getElementById('additionalOptions');
        var portafolioServicios = document.getElementById('portafolioServicios');
        if (checkbox.id === 'flexSwitchCheckSi') {
            additionalOptions.style.display = 'block';
            document.getElementById('checkboxUrl').required = true;
            document.getElementById('checkboxPdf').required = true;
            portafolioServicios.value = 1;
        } else {
            additionalOptions.style.display = 'none';
            document.getElementById('checkboxUrl').required = false;
            document.getElementById('checkboxPdf').required = false;
            document.getElementById('urlInput').style.display = 'none';
            document.getElementById('inputUrl').required = false;
            document.getElementById('pdf_portafolio').style.display = 'none';
            document.getElementById('pdf_portafolio').required = false;
            portafolioServicios.value = 0;
        }
    }

    function toggleUrlOrPdf(option) {
        const checkboxUrl = document.getElementById('checkboxUrl');
        const checkboxPdf = document.getElementById('checkboxPdf');
        const urlInput = document.getElementById('urlInput');
        const pdfInput = document.getElementById('pdfInput');
        const tipo_portafolio = document.getElementById('tipo_portafolio');

        if (option === 'url' && checkboxUrl.checked) {
            checkboxPdf.checked = false;
            checkboxPdf.required = false;
            urlInput.style.display = 'block';
            document.getElementById('inputUrl').required = true;
            pdfInput.style.display = 'none';
            document.getElementById('pdf_portafolio').required = false;
            tipo_portafolio.value = "Url";

            var filePreviewContainerpdf_portafolio = document.getElementById('filePreviewContainerpdf_portafolio');
            if (filePreviewContainerpdf_portafolio) {
                filePreviewContainerpdf_portafolio.style.display = 'none';
            }
        } else if (option === 'pdf' && checkboxPdf.checked) {
            checkboxUrl.checked = false;
            checkboxUrl.required = false;
            pdfInput.style.display = 'block';
            document.getElementById('pdf_portafolio').required = true;
            urlInput.style.display = 'none';
            document.getElementById('inputUrl').required = false;
            tipo_portafolio.value = "PDF";
        }
    }

    // Deshabilitar desmarcar la opción seleccionada sin marcar la otra
    function preventDeselection(checkbox, otherCheckbox) {
        checkbox.addEventListener('change', function() {
            if (!checkbox.checked && !otherCheckbox.checked) {
                // Si intentan desmarcar el actual sin marcar el otro, volver a marcarlo
                checkbox.checked = true;
            }
        });
    }
</script>