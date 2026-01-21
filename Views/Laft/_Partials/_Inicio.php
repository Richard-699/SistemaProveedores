<?php
session_start();
$ruta = '../../../IdiomaConfig/' . $_SESSION['lang'] . '.php';
include($ruta);
?>
<section>
    <div class="card card-principal">
        <div class="card-header">
            <?php echo $lang['title_form_laft'] ?>
        </div>
        <div class="card-body">
            <p class="medium">Version: 05</p>
            <p class="medium"><?php echo $lang['text_form_laft'] ?></p>
        </div>
    </div>
    <div class="card card-secondary">
        <div class="card-header">
        </div>
        <div class="card-body">
            <p class="medium"><?php echo $lang['tiempo_minimo'] ?></p>
            <button id="then" class="btn btn-Laft then"><?php echo $lang['then'] ?></button>
            <button id="continue" class="btn btn-Laft"><?php echo $lang['continuar'] ?></button>
        </div>
    </div>
    <br><br><br>
</section>