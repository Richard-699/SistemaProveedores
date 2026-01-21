<?php
session_start();
include('../../../ConexionBD/conexion.php');
$ruta = '../../../IdiomaConfig/' . $_SESSION['lang'] . '.php';
include($ruta);
?>
<section>
<form id="formContactoOficialCumplimiento">
    <div class="card card-principal">
        <div class="card-header">
            <?php echo $lang['title_contacto_oficial_cumplimiento'] ?>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mt-3">
                    <p><?php echo $lang['text_oficial_cumplimiento'] ?></p>
                    <input type="hidden" class="form-control" id="oficial_cumplimiento" name="oficial_cumplimiento" required>
                    <label>
                        <input type="checkbox" id="chk_1" name="opcion" value="1" onclick="setValue(this)"> Sí
                    </label>
                    <label>
                        <input type="checkbox" id="chk_2" name="opcion" value="0" onclick="setValue(this)"> No
                    </label>
                </div>
            </div>
        </div>
    </div>
    <div id="formulario_adicional" class="card card-secondary" style="display: none;">
        <div class="card-header">
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mt-3">
                    <p><?php echo $lang['Nombres'] ?></p>
                    <input type="text" class="form-control" name="nombres_contacto">
                </div>
                <div class="col-md-6 mt-3">
                    <p><?php echo $lang['Apellidos'] ?></p>
                    <input type="text" class="form-control" name="apellidos_contacto">
                </div>
                <div class="col-md-6 mt-4">
                    <p><?php echo $lang['cargo'] ?></p>
                    <input type="text" class="form-control" name="cargo_contacto">
                </div>
                <div class="col-md-6 mt-4">
                    <p><?php echo $lang['correo'] ?></p>
                    <input type="text" class="form-control" name="correo_electronico_contacto" oninput="validarCorreo(this)">
                </div>
                <div class="col-md-6 mt-4">
                    <p><?php echo $lang['telefono'] ?></p>
                    <input type="text" class="form-control" name="numero_contacto" oninput="validarNumero(this)">
                </div>
            </div>
        </div>
    </div>
</form>
<section>
<button id="next_contactoOficialCumplimiento" class="btn btn-Laft-next"><?php echo $lang['siguiente'] ?></button>
<button id="back_contactoOficialCumplimiento" class="btn btn-Laft-back"><?php echo $lang['anterior'] ?></button>
<script>
    function setValue(checkbox) {
        document.querySelectorAll('input[name="opcion"]').forEach(el => {
            if (el !== checkbox) el.checked = false;
        });

        const hiddenInput = document.getElementById('oficial_cumplimiento');
        const additionalForm = document.getElementById('formulario_adicional');
        const inputs = additionalForm.querySelectorAll('input');

        if (checkbox.checked && checkbox.value == '1') {
            hiddenInput.value = '1';
            additionalForm.style.display = 'block';
            inputs.forEach(input => input.setAttribute('required', 'required'));
        } else {
            hiddenInput.value = '0';
            additionalForm.style.display = 'none';
            inputs.forEach(input => input.removeAttribute('required'));
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
</script>