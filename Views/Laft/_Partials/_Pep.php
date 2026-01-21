<?php
session_start();
include('../../../ConexionBD/conexion.php');
$ruta = '../../../IdiomaConfig/' . $_SESSION['lang'] . '.php';
include($ruta);
?>
<section>
    <div class="card card-principal">
        <div class="card-header">
            <?php echo $lang['title_pep'] ?>
        </div>
        <div class="card-body">
            <form id="formPEP" class="needs-validation" novalidate>
                <div class="row formRow" id="formRow_0">
                    <div class="col-md-6 mt-4">
                        <p><?php echo $lang['Nombre_PEP'] ?></p>
                        <input type="text" class="form-control" name="nombre_pep[]" required>
                    </div>
                    <div class="col-md-6 mt-4">
                        <p><?php echo $lang['tipo_identificacion'] ?></p>
                        <select name="tipo_documento_pep[]" id="tipo_documento_pep" class="form-select" required>
                            <option value="" selected disabled><?php echo $lang['seleccionar_opcion'] ?></option>
                            <option value="Cedula Ciudadania"><?php echo $lang['cedula_ciudadania'] ?></option>
                            <option value="Cedula Extranjeria"><?php echo $lang['cedula_extranjeria'] ?></option>
                        </select>
                        <span class="error-message" style="display:none; color:red">Requerido.</span>
                    </div>
                    <div class="col-md-6 mt-4">
                        <p><?php echo $lang['numero_identificacion'] ?></p>
                        <input type="text" class="form-control" name="numero_identificacion_pep[]" required oninput="validarNumero(this)">
                    </div>
                    <div class="col-md-6 mt-4">
                        <p><?php echo $lang['cargo_pep'] ?></p>
                        <input type="text" class="form-control" name="cargo_ocupa_pep[]" required>
                    </div>
                    <div class="col-md-12 mt-4">
                        <p><?php echo $lang['cargo_ocupa_ocupo_cataloga_pep'] ?></p>
                        <input type="text" class="form-control" name="cargo_ocupa_ocupo_cataloga_pep[]" required>
                    </div>
                    <div class="col-md-6 mt-4">
                        <p><?php echo $lang['desde_cuando_pep'] ?></p>
                        <input type="date" class="form-control" name="desde_cuando_pep[]" required>
                    </div>
                    <div class="col-md-6 mt-4">
                        <p><?php echo $lang['hasta_cuando_pep'] ?></p>
                        <input type="date" class="form-control" name="hasta_cuando_pep[]" required>
                    </div>
                </div>
                <?php
                if ($_SESSION['lang'] == "es") {
                ?>
                    <div class="col-md-12 mt-4 error-message-general" style="display:none; color:red">La fecha "desde cuando" debe ser inferior a la fecha "hasta cuando"</div>
                <?php
                } else {
                ?>
                    <div class="col-md-12 mt-4 error-message-general" style="display:none; color:red">The "since when" date must be less than the "even when" date</div>
                <?php
                }
                ?>

            </form>
        </div>
    </div>
</section>

<button id="addForm" class="btn btn-success fixed-btn"><?php echo $lang['addPEP'] ?></button>
<button id="next_pep" class="btn btn-Laft-next"><?php echo $lang['siguiente'] ?></button>
<button id="back_pep" class="btn btn-Laft-back"><?php echo $lang['anterior'] ?></button>

<script>
    $(document).ready(function() {
        var contadorPEPS = 1;

        $('#addForm').on('click', function() {
            var formHtml = `
            <div class="row formRow" id="formRow_${contadorPEPS}">
            <hr style="margin-top: 50px;">
            <div class="col-md-6 mt-4">
                        <p><?php echo $lang['Nombre_PEP'] ?></p>
                        <input type="text" class="form-control" name="nombre_pep[]" required>
                    </div>
                    <div class="col-md-6 mt-4">
                        <p><?php echo $lang['tipo_identificacion'] ?></p>
                        <select name="tipo_documento_pep[]" id="tipo_documento_pep" class="form-select" required>
                            <option value="" selected disabled><?php echo $lang['seleccionar_opcion'] ?></option>
                            <option value="Cedula Ciudadania"><?php echo $lang['cedula_ciudadania'] ?></option>
                            <option value="Cedula Extranjeria"><?php echo $lang['cedula_extranjeria'] ?></option>
                        </select>
                        <span class="error-message" style="display:none; color:red">Requerido.</span>
                    </div>
                    <div class="col-md-6 mt-4">
                        <p><?php echo $lang['numero_identificacion'] ?></p>
                        <input type="text" class="form-control" name="numero_identificacion_pep[]" required oninput="validarNumero(this)">
                    </div>
                    <div class="col-md-6 mt-4">
                        <p><?php echo $lang['cargo_pep'] ?></p>
                        <input type="text" class="form-control" name="cargo_ocupa_pep[]" required>
                    </div>
                    <div class="col-md-12 mt-4">
                        <p><?php echo $lang['cargo_ocupa_ocupo_cataloga_pep'] ?></p>
                        <input type="text" class="form-control" name="cargo_ocupa_ocupo_cataloga_pep[]" required>
                    </div>
                    <div class="col-md-6 mt-4">
                        <p><?php echo $lang['desde_cuando_pep'] ?></p>
                        <input type="date" class="form-control" name="desde_cuando_pep[]" required>
                    </div>
                    <div class="col-md-5 mt-4">
                        <p><?php echo $lang['hasta_cuando_pep'] ?></p>
                        <input type="date" class="form-control" name="hasta_cuando_pep[]" required>
                    </div>
                    <div class="col-md-1 d-flex align-items-center justify-content-center">
                        <button type="button" class="btn btn-danger deleteForm"><i class="material-icons">delete</i></button>
                    </div>
            </div>
            `;

            var nuevoFormulario = $('#formRow_' + contadorPEPS);
            nuevoFormulario.find('.deleteForm').on('click', function() {
                $(this).closest('.formRow').remove();
            });

            $('#formPEP').append(formHtml);

            contadorPEPS++;
        });

        var nuevoFormulario = $('#formRow_' + contadorPEPS);
        nuevoFormulario.find('.deleteForm').on('click', function() {
            $(this).closest('.formRow').remove();
        });
    });
</script>