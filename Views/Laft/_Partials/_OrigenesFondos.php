<?php
session_start();

$ruta = '../../../IdiomaConfig/' . $_SESSION['lang'] . '.php';
include($ruta);
?>
<section>
    <div class="card card-principal">
        <div class="card-header">
            <?php echo $lang['title_origenes_fondos'] ?>
        </div>
        <div class="card-body">
            <p class="medium"><?php echo $lang['text_origenes_fondos'] ?></p>
        </div>
    </div>
    <div class="card card-secondary">
        <div class="card-header">
        </div>
        <div class="card-body">
            <form id="formOrigenesFondos">
                <div class="row">
                    <div class="col-md-12">
                        <p><?php echo $lang['declaracion_origen_fondos_informacion'] ?></p>
                    </div>
                    <div class="col-md-12 d-flex align-items-center checkbox-group-1">
                        <p class="mb-0 custom-margin"><?php echo $lang['Si'] ?></p>
                        <input type="checkbox" value="1" name="declaracion_origen_fondos_informacion" id="declaracion_origen_fondos_informacion_chk1">
                    </div>
                    <div class="col-md-12 d-flex align-items-center checkbox-group-1">
                        <p class="mb-0 custom-margin"><?php echo $lang['No'] ?></p>
                        <input type="checkbox" value="0" name="declaracion_origen_fondos_informacion" id="declaracion_origen_fondos_informacion_chk2">
                    </div>
                </div>
                <div class="col-md-12 mt-4 error-message-general" style="display:none; color:red">Debe chequear una opción.</div>
            </form>
        </div>
    </div>
    
</section>

<button id="next_origenesFondos" class="btn btn-Laft-next"><?php echo $lang['siguiente'] ?></button>
<button id="back_origenesFondos" class="btn btn-Laft-back"><?php echo $lang['anterior'] ?></button>

<script>

    $(document).ready(function() {
        var declaracion_origen_fondos_informacion_chk1 = document.getElementById("declaracion_origen_fondos_informacion_chk1");
        var declaracion_origen_fondos_informacion_chk2 = document.getElementById("declaracion_origen_fondos_informacion_chk2");

        declaracion_origen_fondos_informacion_chk1.addEventListener("change", function() {
            if (declaracion_origen_fondos_informacion_chk1.checked) {
                declaracion_origen_fondos_informacion_chk2.checked = false;
            }
        });

        declaracion_origen_fondos_informacion_chk2.addEventListener("change", function() {
            if (declaracion_origen_fondos_informacion_chk2.checked) {
                declaracion_origen_fondos_informacion_chk1.checked = false;
            }
        });
    });

</script>