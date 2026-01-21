<?php
session_start();
include('../../../ConexionBD/conexion.php');
$ruta = '../../../IdiomaConfig/' . $_SESSION['lang'] . '.php';
include($ruta);
?>
<section>
    <div class="card card-principal">
        <div class="card-header">
            <?php echo $lang['title_contacto_financiero'] ?>
        </div>
        <div class="card-body">
            <form id="formContactoFinanciero">
                <div class="row">
                    <div class="col-md-6 mt-3">
                        <p><?php echo $lang['Nombres'] ?></p>
                        <input type="text" class="form-control" name="nombres_contacto" required>
                    </div>
                    <div class="col-md-6 mt-3">
                        <p><?php echo $lang['Apellidos'] ?></p>
                        <input type="text" class="form-control" name="apellidos_contacto" required>
                    </div>
                    <div class="col-md-6 mt-4">
                        <p><?php echo $lang['cargo'] ?></p>
                        <input type="text" class="form-control" name="cargo_contacto" required>
                    </div>
                    <div class="col-md-6 mt-4">
                        <p><?php echo $lang['correo'] ?></p>
                        <input type="text" class="form-control" name="correo_electronico_contacto" required oninput="validarCorreo(this)">
                    </div>
                    <div class="col-md-6 mt-4">
                        <p><?php echo $lang['telefono'] ?></p>
                        <input type="text" class="form-control" name="numero_contacto" required oninput="validarNumero(this)">
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<button id="next_contactoFinanciero" class="btn btn-Laft-next"><?php echo $lang['siguiente'] ?></button>
<button id="back_contactoFinanciero" class="btn btn-Laft-back"><?php echo $lang['anterior'] ?></button>

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