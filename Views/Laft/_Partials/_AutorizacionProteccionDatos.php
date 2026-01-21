<?php
session_start();

$ruta = '../../../IdiomaConfig/' . $_SESSION['lang'] . '.php';
include($ruta);
?>

<head>
    <link rel="stylesheet" href="../../Estilos/Supplier/estilos_costBreakDown.css">
</head>
<section>
    <div class="card card-principal">
        <div class="card-header">
            <?php echo $lang['title_autorizacion_proteccion_datos'] ?>
        </div>
        <div class="card-body">
            <p class="medium"><?php echo $lang['text_autorizacion_proteccion_datos'] ?></p>
        </div>
    </div>
    <div class="card card-document mt-5">
        <div class="card-header">
            <?php echo $lang['title_documento_autorizacion_proteccion_datos'] ?>
        </div>
        <div class="card-body">
            <div class="col-md-12">
                <iframe src="../../documents/LAFT/PL-GSA-02.pdf#toolbar=0&navpanes=0&scrollbar=0" width="50%" height="300px"></iframe>
            </div>
            <a href="../../documents/LAFT/PL-GSA-02.pdf" target="_blank" class="btn-descarga"> Abrir Documento</a>
        </div>
    </div>
    <div class="card card-secondary">
        <div class="card-header">
        </div>
        <div class="card-body">
            <form id="formAutorizacionProteccionDatos">
                <div class="row">
                    <div class="col-md-12">
                        <p><?php echo $lang['autorizacion_proteccion_datos'] ?></p>
                    </div>
                    <div class="col-md-12 d-flex align-items-center checkbox-group-1">
                        <p class="mb-0 custom-margin"><?php echo $lang['Si'] ?></p>
                        <input type="checkbox" value="1" name="autorizacion_proteccion_datos" id="autorizacion_proteccion_datos_chk1">
                    </div>
                    <div class="col-md-12 d-flex align-items-center checkbox-group-1">
                        <p class="mb-0 custom-margin"><?php echo $lang['No'] ?></p>
                        <input type="checkbox" value="0" name="autorizacion_proteccion_datos" id="autorizacion_proteccion_datos_chk2">
                    </div>
                </div>
                <div class="col-md-12 mt-4 error-message-general" style="display:none; color:red">Debe chequear una opción.</div>
            </form>
        </div>
    </div>

</section>

<button id="next_autorizacionProteccionDatos" class="btn btn-Laft-next"><?php echo $lang['siguiente'] ?></button>
<button id="back_autorizacionProteccionDatos" class="btn btn-Laft-back"><?php echo $lang['anterior'] ?></button>

<script>
    $(document).ready(function() {
        var autorizacion_proteccion_datos_chk1 = document.getElementById("autorizacion_proteccion_datos_chk1");
        var autorizacion_proteccion_datos_chk2 = document.getElementById("autorizacion_proteccion_datos_chk2");

        autorizacion_proteccion_datos_chk1.addEventListener("change", function() {
            if (autorizacion_proteccion_datos_chk1.checked) {
                autorizacion_proteccion_datos_chk2.checked = false;
            }
        });

        autorizacion_proteccion_datos_chk2.addEventListener("change", function() {
            if (autorizacion_proteccion_datos_chk2.checked) {
                autorizacion_proteccion_datos_chk1.checked = false;
            }
        });
    });
</script>