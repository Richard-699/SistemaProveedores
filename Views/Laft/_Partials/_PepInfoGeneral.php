<?php
session_start();

$ruta = '../../../IdiomaConfig/' . $_SESSION['lang'] . '.php';
include($ruta);
?>
<section>
    <div class="card card-principal">
        <div class="card-header">
            <?php echo $lang['title_pep_infogeneral'] ?>
        </div>
        <div class="card-body">
            <p class="medium"><?php echo $lang['text_pep_infogeneral'] ?></p>
        </div>
    </div>
    <div class="card card-secondary">
        <div class="card-header">
        </div>
        <div class="card-body">
            <p class="medium-2" style="text-align: justify;"><?php echo $lang['preguntas_pep_infogeneral'] ?></p>
            <form id="formPepInfoGeneral" class="needs-validation" novalidate>
                <div class="row mt-5">
                    <div class="col-md-8">
                        <p><?php echo $lang['maneja_o_ha_manejado_recursos_publicos'] ?></p>
                    </div>
                    <div class="col-md-2 d-flex align-items-center checkbox-group-1">
                        <p class="mb-0 custom-margin"><?php echo $lang['Si'] ?></p>
                        <input type="checkbox" value="1" name="maneja_o_ha_manejado_recursos_publicos" id="maneja_o_ha_manejado_recursos_publicos_chk1">
                    </div>
                    <div class="col-md-2 d-flex align-items-center checkbox-group-1">
                        <p class="mb-0 custom-margin"><?php echo $lang['No'] ?></p>
                        <input type="checkbox" value="0" name="maneja_o_ha_manejado_recursos_publicos" id="maneja_o_ha_manejado_recursos_publicos_chk2">
                    </div>

                    <div class="col-md-8">
                        <p><?php echo $lang['tiene_o_ha_tenido_cargo_publico'] ?></p>
                    </div>
                    <div class="col-md-2 d-flex align-items-center checkbox-group-2">
                        <p class="mb-0 custom-margin"><?php echo $lang['Si'] ?></p>
                        <input type="checkbox" value="1" name="tiene_o_ha_tenido_cargo_publico" id="tiene_o_ha_tenido_cargo_publico_chk1">
                    </div>
                    <div class="col-md-2 d-flex align-items-center checkbox-group-2">
                        <p class="mb-0 custom-margin"><?php echo $lang['No'] ?></p>
                        <input type="checkbox" value="0" name="tiene_o_ha_tenido_cargo_publico" id="tiene_o_ha_tenido_cargo_publico_chk2">
                    </div>

                    <div class="col-md-8">
                        <p><?php echo $lang['ocupa_o_ha_ocupado_cargo_publico_organizaciones_gubernamentales'] ?></p>
                    </div>
                    <div class="col-md-2 d-flex align-items-center checkbox-group-3">
                        <p class="mb-0 custom-margin"><?php echo $lang['Si'] ?></p>
                        <input type="checkbox" value="1" name="ocupa_o_ha_ocupado_cargo_publico_organizaciones_gubernamentales" id="ocupa_o_ha_ocupado_cargo_publico_organizaciones_gubernamentales_chk1">
                    </div>
                    <div class="col-md-2 d-flex align-items-center checkbox-group-3">
                        <p class="mb-0 custom-margin"><?php echo $lang['No'] ?></p>
                        <input type="checkbox" value="0" name="ocupa_o_ha_ocupado_cargo_publico_organizaciones_gubernamentales" id="ocupa_o_ha_ocupado_cargo_publico_organizaciones_gubernamentales_chk2">
                    </div>

                    <div class="col-md-8">
                        <p><?php echo $lang['ocupa_o_ha_ocupado_cargo_publico_pais_diferente_colombia'] ?></p>
                    </div>
                    <div class="col-md-2 d-flex align-items-center checkbox-group-4">
                        <p class="mb-0 custom-margin"><?php echo $lang['Si'] ?></p>
                        <input type="checkbox" value="1" name="ocupa_o_ha_ocupado_cargo_publico_pais_diferente_colombia" id="ocupa_o_ha_ocupado_cargo_publico_pais_diferente_colombia_chk1">
                    </div>
                    <div class="col-md-2 d-flex align-items-center checkbox-group-4">
                        <p class="mb-0 custom-margin"><?php echo $lang['No'] ?></p>
                        <input type="checkbox" value="0" name="ocupa_o_ha_ocupado_cargo_publico_pais_diferente_colombia" id="ocupa_o_ha_ocupado_cargo_publico_pais_diferente_colombia_chk2">
                    </div>
                </div>
                <div class="col-md-12 error-message-general" style="display:none; color:red">Debe chequear una opción por pregunta.</div>
            </form>
        </div>
    </div>
</section>

<button id="next_pepInfoGeneral" class="btn btn-Laft-next"><?php echo $lang['siguiente'] ?></button>
<button id="back_pepInfoGeneral" class="btn btn-Laft-back"><?php echo $lang['anterior'] ?></button>

<script>
    $(document).ready(function() {
        var maneja_o_ha_manejado_recursos_publicos_chk1 = document.getElementById("maneja_o_ha_manejado_recursos_publicos_chk1");
        var maneja_o_ha_manejado_recursos_publicos_chk2 = document.getElementById("maneja_o_ha_manejado_recursos_publicos_chk2");

        maneja_o_ha_manejado_recursos_publicos_chk1.addEventListener("change", function() {
            if (maneja_o_ha_manejado_recursos_publicos_chk1.checked) {
                maneja_o_ha_manejado_recursos_publicos_chk2.checked = false;
            }
        });

        maneja_o_ha_manejado_recursos_publicos_chk2.addEventListener("change", function() {
            if (maneja_o_ha_manejado_recursos_publicos_chk2.checked) {
                maneja_o_ha_manejado_recursos_publicos_chk1.checked = false;
            }
        });


        var tiene_o_ha_tenido_cargo_publico_chk1 = document.getElementById("tiene_o_ha_tenido_cargo_publico_chk1");
        var tiene_o_ha_tenido_cargo_publico_chk2 = document.getElementById("tiene_o_ha_tenido_cargo_publico_chk2");

        tiene_o_ha_tenido_cargo_publico_chk1.addEventListener("change", function() {
            if (tiene_o_ha_tenido_cargo_publico_chk1.checked) {
                tiene_o_ha_tenido_cargo_publico_chk2.checked = false;
            }
        });

        tiene_o_ha_tenido_cargo_publico_chk2.addEventListener("change", function() {
            if (tiene_o_ha_tenido_cargo_publico_chk2.checked) {
                tiene_o_ha_tenido_cargo_publico_chk1.checked = false;
            }
        });


        var ocupa_o_ha_ocupado_cargo_publico_organizaciones_gubernamentales_chk1 = document.getElementById("ocupa_o_ha_ocupado_cargo_publico_organizaciones_gubernamentales_chk1");
        var ocupa_o_ha_ocupado_cargo_publico_organizaciones_gubernamentales_chk2 = document.getElementById("ocupa_o_ha_ocupado_cargo_publico_organizaciones_gubernamentales_chk2");

        ocupa_o_ha_ocupado_cargo_publico_organizaciones_gubernamentales_chk1.addEventListener("change", function() {
            if (ocupa_o_ha_ocupado_cargo_publico_organizaciones_gubernamentales_chk1.checked) {
                ocupa_o_ha_ocupado_cargo_publico_organizaciones_gubernamentales_chk2.checked = false;
            }
        });

        ocupa_o_ha_ocupado_cargo_publico_organizaciones_gubernamentales_chk2.addEventListener("change", function() {
            if (ocupa_o_ha_ocupado_cargo_publico_organizaciones_gubernamentales_chk2.checked) {
                ocupa_o_ha_ocupado_cargo_publico_organizaciones_gubernamentales_chk1.checked = false;
            }
        });


        var ocupa_o_ha_ocupado_cargo_publico_pais_diferente_colombia_chk1 = document.getElementById("ocupa_o_ha_ocupado_cargo_publico_pais_diferente_colombia_chk1");
        var ocupa_o_ha_ocupado_cargo_publico_pais_diferente_colombia_chk2 = document.getElementById("ocupa_o_ha_ocupado_cargo_publico_pais_diferente_colombia_chk2");

        ocupa_o_ha_ocupado_cargo_publico_pais_diferente_colombia_chk1.addEventListener("change", function() {
            if (ocupa_o_ha_ocupado_cargo_publico_pais_diferente_colombia_chk1.checked) {
                ocupa_o_ha_ocupado_cargo_publico_pais_diferente_colombia_chk2.checked = false;
            }
        });

        ocupa_o_ha_ocupado_cargo_publico_pais_diferente_colombia_chk2.addEventListener("change", function() {
            if (ocupa_o_ha_ocupado_cargo_publico_pais_diferente_colombia_chk2.checked) {
                ocupa_o_ha_ocupado_cargo_publico_pais_diferente_colombia_chk1.checked = false;
            }
        });
    });
</script>