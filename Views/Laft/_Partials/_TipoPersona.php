<?php
session_start();

$ruta = '../../../IdiomaConfig/' . $_SESSION['lang'] . '.php';
include($ruta);
include '../../spinner.html';
?>
<section>
    <div class="card card-principal">
        <div class="card-header">
            <?php echo $lang['title_form_laft'] ?>
        </div>  
        <div class="card-body">
            <p><?php echo $lang['tipo_persona'] ?></p>
            <select name="tipo_persona" id="tipo_persona" class="form-select">
                <option selected disabled><?php echo $lang['seleccionar_opcion'] ?></option>
                <option value="Natural"><?php echo $lang['persona_natural'] ?></option>
                <option value="Juridica"><?php echo $lang['persona_juridica'] ?></option>
            </select>
            <span class="error-message" style="display:none; color:red">Requerido.</span>
        </div>
    </div>
    <button id="next_tipoPersona" class="btn btn-Laft-next"><?php echo $lang['siguiente'] ?></button>
</section>