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
            <?php echo $lang['title_sostenibilidad'] ?>
        </div>
        <div class="card-body">
            <form id="formSostenibilidad" class="needs-validation" novalidate>
                <div class="row">
                    <div class="col-md-6 mt-4">
                        <p><?php echo $lang['identificado_grupos_interes'] ?></p>
                        <select name="identificado_grupos_interes" id="identificado_grupos_interes" class="form-select" required>
                            <option value="" selected disabled><?php echo $lang['seleccionar_opcion'] ?></option>
                            <option value="1"><?php echo $lang['cumple'] ?></option>
                            <option value="0"><?php echo $lang['no_cumple'] ?></option>
                            <option value="0.5"><?php echo $lang['cumplimiento_parcial'] ?></option>
                            <option value="N/A"><?php echo $lang['no_aplica'] ?></option>
                        </select>
                    </div>
                    <div class="col-md-6 mt-4">
                        <p><?php echo $lang['realizado_analisis_materialidad'] ?></p>
                        <select name="realizado_analisis_materialidad" id="realizado_analisis_materialidad" class="form-select" required>
                            <option value="" selected disabled><?php echo $lang['seleccionar_opcion'] ?></option>
                            <option value="1"><?php echo $lang['cumple'] ?></option>
                            <option value="0"><?php echo $lang['no_cumple'] ?></option>
                            <option value="0.5"><?php echo $lang['cumplimiento_parcial'] ?></option>
                            <option value="N/A"><?php echo $lang['no_aplica'] ?></option>
                        </select>
                    </div>
                    <div class="col-md-6 mt-4">
                        <p><?php echo $lang['cuenta_estrategia_sostenibilidad'] ?></p>
                        <select name="cuenta_estrategia_sostenibilidad" id="cuenta_estrategia_sostenibilidad" class="form-select" required>
                            <option value="" selected disabled><?php echo $lang['seleccionar_opcion'] ?></option>
                            <option value="1"><?php echo $lang['cumple'] ?></option>
                            <option value="0"><?php echo $lang['no_cumple'] ?></option>
                            <option value="0.5"><?php echo $lang['cumplimiento_parcial'] ?></option>
                            <option value="N/A"><?php echo $lang['no_aplica'] ?></option>
                        </select>
                    </div>
                    <div class="col-md-12 mt-4">
                        <p><?php echo $lang['priorizado_objetivos_desarrollo_sostenible'] ?></p>
                        <select name="priorizado_objetivos_desarrollo_sostenible" id="priorizado_objetivos_desarrollo_sostenible" class="form-select" required>
                            <option value="" selected disabled><?php echo $lang['seleccionar_opcion'] ?></option>
                            <option value="1"><?php echo $lang['cumple'] ?></option>
                            <option value="0"><?php echo $lang['no_cumple'] ?></option>
                            <option value="0.5"><?php echo $lang['cumplimiento_parcial'] ?></option>
                            <option value="N/A"><?php echo $lang['no_aplica'] ?></option>
                        </select>
                    </div>
                    <div class="col-md-6 mt-4">
                        <p><?php echo $lang['cuenta_programas_inversion'] ?></p>
                        <select name="cuenta_programas_inversion" id="cuenta_programas_inversion" class="form-select" required>
                            <option value="" selected disabled><?php echo $lang['seleccionar_opcion'] ?></option>
                            <option value="1"><?php echo $lang['cumple'] ?></option>
                            <option value="0"><?php echo $lang['no_cumple'] ?></option>
                            <option value="0.5"><?php echo $lang['cumplimiento_parcial'] ?></option>
                            <option value="N/A"><?php echo $lang['no_aplica'] ?></option>
                        </select>
                    </div>
                    <div class="col-md-6 mt-4">
                        <p><?php echo $lang['cuenta_programas_mejorar_desempeno_ambiental'] ?></p>
                        <select name="cuenta_programas_mejorar_desempeno_ambiental" id="cuenta_programas_mejorar_desempeno_ambiental" class="form-select" required>
                            <option value="" selected disabled><?php echo $lang['seleccionar_opcion'] ?></option>
                            <option value="1"><?php echo $lang['cumple'] ?></option>
                            <option value="0"><?php echo $lang['no_cumple'] ?></option>
                            <option value="0.5"><?php echo $lang['cumplimiento_parcial'] ?></option>
                            <option value="N/A"><?php echo $lang['no_aplica'] ?></option>
                        </select>
                    </div>
                    <div class="col-md-6 mt-4">
                        <p><?php echo $lang['cuenta_programas_buen_gobierno_corporativo'] ?></p>
                        <select name="cuenta_programas_buen_gobierno_corporativo" id="cuenta_programas_buen_gobierno_corporativo" class="form-select" required>
                            <option value="" selected disabled><?php echo $lang['seleccionar_opcion'] ?></option>
                            <option value="1"><?php echo $lang['cumple'] ?></option>
                            <option value="0"><?php echo $lang['no_cumple'] ?></option>
                            <option value="0.5"><?php echo $lang['cumplimiento_parcial'] ?></option>
                            <option value="N/A"><?php echo $lang['no_aplica'] ?></option>
                        </select>
                    </div>
                    <div class="col-md-12 mt-5">
                        <p><?php echo $lang['cuenta_siguientes_políticas'] ?></p>
                    </div>
                    <div class="col-md-12 mt-4 d-flex align-items-center">
                        <input type="checkbox" name="politica_sostenibilidad" id="politica_sostenibilidad">
                        <p class="ms-2 mb-0"><?php echo $lang['politica_sostenibilidad'] ?></p>
                    </div>
                    <div class="col-md-12 mt-4 d-flex align-items-center">
                        <input type="checkbox" name="politica_ambiental">
                        <p class="ms-2 mb-0"><?php echo $lang['politica_ambiental'] ?></p>
                    </div>
                    <div class="col-md-12 mt-4 d-flex align-items-center">
                        <input type="checkbox" name="seguridad_salud_trabajo">
                        <p class="ms-2 mb-0"><?php echo $lang['seguridad_salud_trabajo'] ?></p>
                    </div>
                    <div class="col-md-12 mt-4 d-flex align-items-center">
                        <input type="checkbox" name="politica_derechos_humanos">
                        <p class="ms-2 mb-0"><?php echo $lang['politica_derechos_humanos'] ?></p>
                    </div>
                    <div class="col-md-12 mt-4 d-flex align-items-center">
                        <input type="checkbox" name="politica_debida_diligencia">
                        <p class="ms-2 mb-0"><?php echo $lang['politica_debida_diligencia'] ?></p>
                    </div>
                    <div class="col-md-12 mt-4 d-flex align-items-center">
                        <input type="checkbox" name="politica_prevencion">
                        <p class="ms-2 mb-0"><?php echo $lang['politica_prevencion'] ?></p>
                    </div>
                    <div class="col-md-12 mt-4 d-flex align-items-center">
                        <input type="checkbox" name="codigo_etica_empresarial">
                        <p class="ms-2 mb-0"><?php echo $lang['codigo_etica_empresarial'] ?></p>
                    </div>
                    <div class="col-md-12 mt-4 d-flex align-items-center">
                        <input type="checkbox" name="politica_igualdad">
                        <p class="ms-2 mb-0"><?php echo $lang['politica_igualdad'] ?></p>
                    </div>

                    <div class="col-md-12 mt-5">
                        <p><?php echo $lang['inscrito_iniciativa_fondos_sostenibles'] ?></p>
                        <textarea class="form-control" name="inscrito_iniciativa_fondos_sostenibles"></textarea>
                    </div>
                    <div class="col-md-12 mt-5">
                        <p><?php echo $lang['realiza_reporte_memoria_sostenibilidad'] ?></p>
                        <select name="realiza_reporte_memoria_sostenibilidad" id="realiza_reporte_memoria_sostenibilidad" class="form-select" required>
                            <option value="" selected disabled><?php echo $lang['seleccionar_opcion'] ?></option>
                            <option value="1"><?php echo $lang['cumple'] ?></option>
                            <option value="0"><?php echo $lang['no_cumple'] ?></option>
                            <option value="0.5"><?php echo $lang['cumplimiento_parcial'] ?></option>
                            <option value="N/A"><?php echo $lang['no_aplica'] ?></option>
                        </select>
                    </div>

                    <div class="col-md-12 mt-5">
                        <p><?php echo $lang['Ha_ejecutado_proyectos'] ?></p>
                    </div>
                    <div class="col-md-12 mt-4 d-flex align-items-center">
                        <input type="checkbox" name="produccion_limpia">
                        <p class="ms-2 mb-0"><?php echo $lang['produccion_limpia'] ?></p>
                    </div>
                    <div class="col-md-12 mt-4 d-flex align-items-center">
                        <input type="checkbox" name="economia_circular">
                        <p class="ms-2 mb-0"><?php echo $lang['economia_circular'] ?></p>
                    </div>
                    <div class="col-md-12 mt-4 d-flex align-items-center">
                        <input type="checkbox" name="cambio_climatico">
                        <p class="ms-2 mb-0"><?php echo $lang['cambio_climatico'] ?></p>
                    </div>
                    <div class="col-md-12 mt-4 d-flex align-items-center">
                        <input type="checkbox" name="huella_carbono">
                        <p class="ms-2 mb-0"><?php echo $lang['huella_carbono'] ?></p>
                    </div>
                    <div class="col-md-12 mt-4 d-flex align-items-center">
                        <input type="checkbox" name="net_zero_carbono_neutro">
                        <p class="ms-2 mb-0"><?php echo $lang['net_zero_carbono_neutro'] ?></p>
                    </div>
                    <div class="col-md-12 mt-4 d-flex align-items-center">
                        <input type="checkbox" name="energias_renovables">
                        <p class="ms-2 mb-0"><?php echo $lang['energias_renovables'] ?></p>
                    </div>
                    <div class="col-md-12 mt-4 d-flex align-items-center">
                        <input type="checkbox" name="energia_verde_I_REC">
                        <p class="ms-2 mb-0"><?php echo $lang['energia_verde_I_REC'] ?></p>
                    </div>
                    <div class="col-md-12 mt-4 d-flex align-items-center">
                        <input type="checkbox" name="eficiencia_energetica">
                        <p class="ms-2 mb-0"><?php echo $lang['eficiencia_energetica'] ?></p>
                    </div>
                    <div class="col-md-12 mt-4 d-flex align-items-center">
                        <input type="checkbox" name="ecoeficiencia_operacional">
                        <p class="ms-2 mb-0"><?php echo $lang['ecoeficiencia_operacional'] ?></p>
                    </div>
                    <div class="col-md-12 mt-4 d-flex align-items-center">
                        <input type="checkbox" name="sustancias_quimicas_ambientalmente_amigables">
                        <p class="ms-2 mb-0"><?php echo $lang['sustancias_quimicas_ambientalmente_amigables'] ?></p>
                    </div>
                    <div class="col-md-12 mt-4 d-flex align-items-center">
                        <input type="checkbox" name="reutilizacion_recirculacion_agua">
                        <p class="ms-2 mb-0"><?php echo $lang['reutilizacion_recirculacion_agua'] ?></p>
                    </div>
                    <div class="col-md-12 mt-4 d-flex align-items-center">
                        <input type="checkbox" name="aprovechamiento_aguas_lluvias">
                        <p class="ms-2 mb-0"><?php echo $lang['aprovechamiento_aguas_lluvias'] ?></p>
                    </div>
                    <div class="col-md-12 mt-4 d-flex align-items-center">
                        <input type="checkbox" name="automatizacion_digitalizacion_papel_cero">
                        <p class="ms-2 mb-0"><?php echo $lang['automatizacion_digitalizacion_papel_cero'] ?></p>
                    </div>
                    <div class="col-md-12 mt-4 d-flex align-items-center">
                        <input type="checkbox" name="basura_cero">
                        <p class="ms-2 mb-0"><?php echo $lang['basura_cero'] ?></p>
                    </div>
                    <div class="col-md-12 mt-4 d-flex align-items-center">
                        <input type="checkbox" name="cero_vertimientos">
                        <p class="ms-2 mb-0"><?php echo $lang['cero_vertimientos'] ?></p>
                    </div>
                    <div class="col-md-12 mt-4 d-flex align-items-center">
                        <input type="checkbox" name="cero_emisiones">
                        <p class="ms-2 mb-0"><?php echo $lang['cero_emisiones'] ?></p>
                    </div>
                    <div class="col-md-12 mt-4 d-flex align-items-center">
                        <input type="checkbox" name="ecodiseno_productos_embalajes">
                        <p class="ms-2 mb-0"><?php echo $lang['ecodiseno_productos_embalajes'] ?></p>
                    </div>
                    <div class="col-md-12 mt-4 d-flex align-items-center">
                        <input type="checkbox" name="analisis_ciclo_vida">
                        <p class="ms-2 mb-0"><?php echo $lang['analisis_ciclo_vida'] ?></p>
                    </div>
                    <div class="col-md-12 mt-4 d-flex align-items-center">
                        <input type="checkbox" name="contratacion_personas_discapacidad">
                        <p class="ms-2 mb-0"><?php echo $lang['contratacion_personas_discapacidad'] ?></p>
                    </div>
                    <div class="col-md-12 mt-4 d-flex align-items-center">
                        <input type="checkbox" name="contratacion_mujeres_altos_cargos_directivos">
                        <p class="ms-2 mb-0"><?php echo $lang['contratacion_mujeres_altos_cargos_directivos'] ?></p>
                    </div>
                    <div class="col-md-12 mt-4 d-flex align-items-center">
                        <input type="checkbox" name="seleccion_contratacion_criterios_diversidad">
                        <p class="ms-2 mb-0"><?php echo $lang['seleccion_contratacion_criterios_diversidad'] ?></p>
                    </div>
                    <div class="col-md-12 mt-4 d-flex align-items-center">
                        <input type="checkbox" name="derechos_laborales">
                        <p class="ms-2 mb-0"><?php echo $lang['derechos_laborales'] ?></p>
                    </div>
                    <div class="col-md-12 mt-4 d-flex align-items-center">
                        <input type="checkbox" name="evaluacion_proveedores_criterios_sociales_ambientales">
                        <p class="ms-2 mb-0"><?php echo $lang['evaluacion_proveedores_criterios_sociales_ambientales'] ?></p>
                    </div>
                    <div class="col-md-12 mt-4 d-flex align-items-center">
                        <input type="checkbox" name="desarrollo_cadena_suministro_local">
                        <p class="ms-2 mb-0"><?php echo $lang['desarrollo_cadena_suministro_local'] ?></p>
                    </div>
                    <div class="col-md-12 mt-4 d-flex align-items-center">
                        <input type="checkbox" name="inversiones_sostenibles">
                        <p class="ms-2 mb-0"><?php echo $lang['inversiones_sostenibles'] ?></p>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<button id="next_sostenibilidad" class="btn btn-Laft-next"><?php echo $lang['finalizar'] ?></button>
<button id="back_sostenibilidad" class="btn btn-Laft-back"><?php echo $lang['anterior'] ?></button>

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
                textareaElement.value = '';
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