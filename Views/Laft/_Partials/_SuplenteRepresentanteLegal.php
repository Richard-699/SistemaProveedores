<?php
session_start();
include('../../../ConexionBD/conexion.php');
$ruta = '../../../IdiomaConfig/' . $_SESSION['lang'] . '.php';
include($ruta);
$carta_beneficiarios_finales = $_SESSION['carta_beneficiarios_finales'];
?>
<section>
    <div class="card card-principal">
        <div class="card-header">
            <?php echo $lang['title_suplente_representante_legal'] ?>
        </div>
        <div class="card-body">
            <form id="formSuplenteRepresentanteLegal">
<<<<<<< HEAD
            <input type="hidden" name="carta_beneficiarios_finales" value="<?php echo $carta_beneficiarios_finales; ?>">
=======
                <input type="hidden" name="carta_beneficiarios_finales" value="<?php echo $carta_beneficiarios_finales; ?>">
>>>>>>> 8fe25a02a378af3db1c5f09c74bddd125a144800
                <div class="row">
                    <div class="col-md-6 mt-4">
                        <p><?php echo $lang['tipo_identificacion'] ?></p>
                        <select name="tipo_documento_representante_legal" id="tipo_documento_representante_legal" class="form-select" required>
                            <option value="" selected disabled><?php echo $lang['seleccionar_opcion'] ?></option>
                            <option value="Cedula Ciudadania"><?php echo $lang['cedula_ciudadania'] ?></option>
                            <option value="Cedula Extranjeria"><?php echo $lang['cedula_extranjeria'] ?></option>
                            <option value="ID">ID</option>
                            <option value="Pasaporte"><?php echo $lang['pasaporte'] ?></option>
                        </select>
                        <span class="error-message" style="display:none; color:red">Requerido.</span>
                    </div>
                    <div class="col-md-6 mt-4">
                        <p><?php echo $lang['numero_identificacion'] ?></p>
                        <input type="text" class="form-control" name="numero_identificacion_representante_legal" required oninput="validarNumero(this)">
                    </div>
                    <div class="col-md-6 mt-4">
                        <p><?php echo $lang['Nombres'] ?></p>
                        <input type="text" class="form-control" name="nombres_representante_legal" required>
                    </div>
                    <div class="col-md-6 mt-4">
                        <p><?php echo $lang['Apellidos'] ?></p>
                        <input type="text" class="form-control" name="apellidos_representante_legal" required>
                    </div>
                    <div class="col-md-6 mt-4">
                        <p><?php echo $lang['correo'] ?></p>
                        <input type="text" class="form-control" name="correo_electronico_representante_legal" required oninput="validarCorreo(this)">
                    </div>
                    <div class="col-md-6 mt-4">
                        <p><?php echo $lang['telefono'] ?></p>
                        <input type="text" class="form-control" name="numero_contacto_representante_legal" required oninput="validarNumero(this)">
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<button id="next_suplenteRepresentanteLegal" class="btn btn-Laft-next"><?php echo $lang['siguiente'] ?></button>
<button id="back_suplenteRepresentanteLegal" class="btn btn-Laft-back"><?php echo $lang['anterior'] ?></button>

<script>
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
</script>