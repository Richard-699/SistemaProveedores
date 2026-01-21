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
            <?php echo $lang['title_gestion_ambiental'] ?>
        </div>
        <div class="card-body">
            <form id="formGestionAmbiental" class="needs-validation" novalidate>
                <div class="row">

                    <div class="col-md-6 mt-4">
                        <p><?php echo $lang['cuenta_sistema_gestion_ambiental'] ?></p>
                        <select name="cuenta_sistema_gestion_ambiental" id="cuenta_sistema_gestion_ambiental" class="form-select" required>
                            <option value="" selected disabled><?php echo $lang['seleccionar_opcion'] ?></option>
                            <option value="1"><?php echo $lang['cumple'] ?></option>
                            <option value="0"><?php echo $lang['no_cumple'] ?></option>
                            <option value="0.5"><?php echo $lang['cumplimiento_parcial'] ?></option>
                            <option value="N/A"><?php echo $lang['no_aplica'] ?></option>
                        </select>
                    </div>
                    <div class="col-md-6 mt-4">
                        <p><?php echo $lang['certificado_ISO_14001'] ?></p>
                        <input type="hidden" name="certificado_ISO_14001" required>
                        <label>
                            <input type="checkbox" id="certificado_ISO_14001_chk_1" class="option-checkbox" data-hidden-name="certificado_ISO_14001" value="1"> Sí
                        </label>
                        <label>
                            <input type="checkbox" id="certificado_ISO_14001_chk_2" class="option-checkbox custom-margin-2" data-hidden-name="certificado_ISO_14001" value="0"> No
                        </label>
                    </div>
                    <div class="col-md-12 mt-4">
                        <p><?php echo $lang['cuenta_departamento_gestion_politica_ambiental'] ?></p>
                        <select name="cuenta_departamento_gestion_politica_ambiental" id="cuenta_departamento_gestion_politica_ambiental" class="form-select" required>
                            <option value="" selected disabled><?php echo $lang['seleccionar_opcion'] ?></option>
                            <option value="1"><?php echo $lang['cumple'] ?></option>
                            <option value="0"><?php echo $lang['no_cumple'] ?></option>
                            <option value="0.5"><?php echo $lang['cumplimiento_parcial'] ?></option>
                            <option value="N/A"><?php echo $lang['no_aplica'] ?></option>
                        </select>
                    </div>
                    <div class="col-md-12 mt-4">
                        <p><?php echo $lang['tiene_identificados_aspectos_impactos'] ?></p>
                        <select name="tiene_identificados_aspectos_impactos" id="tiene_identificados_aspectos_impactos" class="form-select" required>
                            <option value="" selected disabled><?php echo $lang['seleccionar_opcion'] ?></option>
                            <option value="1"><?php echo $lang['cumple'] ?></option>
                            <option value="0"><?php echo $lang['no_cumple'] ?></option>
                            <option value="0.5"><?php echo $lang['cumplimiento_parcial'] ?></option>
                            <option value="N/A"><?php echo $lang['no_aplica'] ?></option>
                        </select>
                    </div>
                    <div class="col-md-12 mt-4">
                        <p><?php echo $lang['principales_requisitos_legales'] ?></p>
                        <textarea class="form-control" name="principales_requisitos_legales" required></textarea>
                    </div>
                    <div class="col-md-6 mt-4">
                        <p><?php echo $lang['realiza_registro_anual_autoridades'] ?></p>
                        <select name="realiza_registro_anual_autoridades" id="realiza_registro_anual_autoridades" class="form-select" required>
                            <option value="" selected disabled><?php echo $lang['seleccionar_opcion'] ?></option>
                            <option value="1"><?php echo $lang['cumple'] ?></option>
                            <option value="0"><?php echo $lang['no_cumple'] ?></option>
                            <option value="0.5"><?php echo $lang['cumplimiento_parcial'] ?></option>
                            <option value="N/A"><?php echo $lang['no_aplica'] ?></option>
                        </select>
                    </div>
                    <div class="col-md-6 mt-4">
                        <p><?php echo $lang['ha_obtenido_sancion'] ?></p>
                        <input type="hidden" name="ha_obtenido_sancion" required>
                        <label>
                            <input type="checkbox" id="ha_obtenido_sancion_chk_1" class="option-checkbox" data-hidden-name="ha_obtenido_sancion" value="1"> Sí
                        </label>
                        <label>
                            <input type="checkbox" id="ha_obtenido_sancion_chk_2" class="option-checkbox custom-margin-2" data-hidden-name="ha_obtenido_sancion" value="0"> No
                        </label>
                    </div>
                    <div class="col-md-12 mt-4">
                        <p><?php echo $lang['permiso_uso_recursos_naturales'] ?></p>
                        <select name="permiso_uso_recursos_naturales" id="permiso_uso_recursos_naturales" class="form-select" required>
                            <option value="" selected disabled><?php echo $lang['seleccionar_opcion'] ?></option>
                            <option value="1"><?php echo $lang['cumple'] ?></option>
                            <option value="0"><?php echo $lang['no_cumple'] ?></option>
                            <option value="0.5"><?php echo $lang['cumplimiento_parcial'] ?></option>
                            <option value="N/A"><?php echo $lang['no_aplica'] ?></option>
                        </select>
                    </div>
                    <div class="col-md-12 mt-4" id="permisos_cuenta_cont" style="display:none;">
                        <p><?php echo $lang['permisos_cuenta'] ?></p>
                        <textarea class="form-control" name="permisos_cuenta" id="permisos_cuenta"></textarea>
                    </div>
                    <div class="col-md-6 mt-4">
                        <p><?php echo $lang['plan_manejo_integral_residuos'] ?></p>
                        <select name="plan_manejo_integral_residuos" id="plan_manejo_integral_residuos" class="form-select" required>
                            <option value="" selected disabled><?php echo $lang['seleccionar_opcion'] ?></option>
                            <option value="1"><?php echo $lang['cumple'] ?></option>
                            <option value="0"><?php echo $lang['no_cumple'] ?></option>
                            <option value="0.5"><?php echo $lang['cumplimiento_parcial'] ?></option>
                            <option value="N/A"><?php echo $lang['no_aplica'] ?></option>
                        </select>
                    </div>
                    <div class="col-md-6 mt-4">
                        <p><?php echo $lang['genera_residuos_posconsumo'] ?></p>
                        <select name="genera_residuos_posconsumo" id="genera_residuos_posconsumo" class="form-select" required>
                            <option value="" selected disabled><?php echo $lang['seleccionar_opcion'] ?></option>
                            <option value="1"><?php echo $lang['cumple'] ?></option>
                            <option value="0"><?php echo $lang['no_cumple'] ?></option>
                            <option value="0.5"><?php echo $lang['cumplimiento_parcial'] ?></option>
                            <option value="N/A"><?php echo $lang['no_aplica'] ?></option>
                        </select>
                    </div>
                    <div class="col-md-12 mt-4">
                        <p><?php echo $lang['controles_realiza_gestion_residuos_solidos_peligrosos'] ?></p>
                        <textarea class="form-control" name="controles_realiza_gestion_residuos_solidos_peligrosos"></textarea>
                    </div>
                    <div class="col-md-12 mt-4">
                        <p><?php echo $lang['genera_vertimiento_aguas_residuales_industriales'] ?></p>
                        <select name="genera_vertimiento_aguas_residuales_industriales" id="genera_vertimiento_aguas_residuales_industriales" class="form-select" required>
                            <option value="" selected disabled><?php echo $lang['seleccionar_opcion'] ?></option>
                            <option value="1"><?php echo $lang['cumple'] ?></option>
                            <option value="0"><?php echo $lang['no_cumple'] ?></option>
                            <option value="0.5"><?php echo $lang['cumplimiento_parcial'] ?></option>
                            <option value="N/A"><?php echo $lang['no_aplica'] ?></option>
                        </select>
                    </div>
                    <div class="col-md-12 mt-4">
                        <p><?php echo $lang['controles_realiza_gestion_vertimientos'] ?></p>
                        <textarea class="form-control" name="controles_realiza_gestion_vertimientos"></textarea>
                    </div>
                    <div class="col-md-12 mt-4">
                        <p><?php echo $lang['genera_emisiones_atmosfericas'] ?></p>
                        <select name="genera_emisiones_atmosfericas" id="genera_emisiones_atmosfericas" class="form-select" required>
                            <option value="" selected disabled><?php echo $lang['seleccionar_opcion'] ?></option>
                            <option value="1"><?php echo $lang['cumple'] ?></option>
                            <option value="0"><?php echo $lang['no_cumple'] ?></option>
                            <option value="0.5"><?php echo $lang['cumplimiento_parcial'] ?></option>
                            <option value="N/A"><?php echo $lang['no_aplica'] ?></option>
                        </select>
                    </div>
                    <div class="col-md-12 mt-4">
                        <p><?php echo $lang['controles_realiza_gestion_emisiones'] ?></p>
                        <textarea class="form-control" name="controles_realiza_gestion_emisiones"></textarea>
                    </div>
                    <div class="col-md-12 mt-4">
                        <p><?php echo $lang['plan_contingencia_manejo_transporte'] ?></p>
                        <select name="plan_contingencia_manejo_transporte" id="plan_contingencia_manejo_transporte" class="form-select" required>
                            <option value="" selected disabled><?php echo $lang['seleccionar_opcion'] ?></option>
                            <option value="1"><?php echo $lang['cumple'] ?></option>
                            <option value="0"><?php echo $lang['no_cumple'] ?></option>
                            <option value="0.5"><?php echo $lang['cumplimiento_parcial'] ?></option>
                            <option value="N/A"><?php echo $lang['no_aplica'] ?></option>
                        </select>
                    </div>
                    <div class="col-md-12 mt-4">
                        <p><?php echo $lang['controles_realiza_gestion_sustancias_quimicas'] ?></p>
                        <textarea class="form-control" name="controles_realiza_gestion_sustancias_quimicas"></textarea>
                    </div>
                    <div class="col-md-12 mt-4">
                        <p><?php echo $lang['observaciones_gestion_ambiental'] ?></p>
                        <textarea class="form-control" name="observaciones_gestion_ambiental"></textarea>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<button id="next_gestionAmbiental" class="btn btn-Laft-next"><?php echo $lang['siguiente'] ?></button>
<button id="back_gestionAmbiental" class="btn btn-Laft-back"><?php echo $lang['anterior'] ?></button>

<script>
    $(document).ready(function() {
        const checkboxes = document.querySelectorAll('.option-checkbox');

        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('click', function() {
                const hiddenInputName = this.getAttribute('data-hidden-name');
                const hiddenInput = document.querySelector(`input[type="hidden"][name="${hiddenInputName}"]`);
                const siblingCheckboxes = document.querySelectorAll(`.option-checkbox[data-hidden-name="${hiddenInputName}"]`);

                siblingCheckboxes.forEach(sibling => {
                    if (sibling !== this) {
                        sibling.checked = false;
                    }
                });

                if (this.checked) {
                    hiddenInput.value = this.value;
                } else {
                    hiddenInput.value = '';
                }
            });
        });

        const selectElement = document.getElementById('permiso_uso_recursos_naturales');
        const permisos_cuenta_cont = document.getElementById('permisos_cuenta_cont');
        const textareaElement = document.getElementById('permisos_cuenta');

        selectElement.addEventListener('change', function() {
            if (this.value === '1') {
                permisos_cuenta_cont.style.display = 'block';
                textareaElement.setAttribute('required', 'required');
            } else {
                permisos_cuenta_cont.style.display = 'none';
                textareaElement.removeAttribute('required');
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

</script>