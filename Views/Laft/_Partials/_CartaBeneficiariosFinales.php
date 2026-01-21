<?php
session_start();

$ruta = '../../../IdiomaConfig/' . $_SESSION['lang'] . '.php';
include($ruta);

?>
<section>
    <div class="card card-principal">
        <div class="card-header">
            <?php echo $lang['title_beneficiarios_finales'] ?>
        </div>
        <div class="card-body">
            <p class="medium"><?php echo $lang['text_beneficiarios_finales'] ?></p>
        </div>
    </div>
    <div class="card card-secondary">
        <div class="card-header">
        </div>
        <div class="card-body">
            <form id="formCartaBeneficiariosFinales" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-12">
                        <p><?php echo $lang['carta_beneficiarios_finales'] ?></p>
                    </div>
                    <div class="col-md-12" id="file_carta_beneficiarios_finales">
                        <input type="file" class="form-control file-input mt-4" name="carta_beneficiarios_finales" id="carta_beneficiarios_finales" accept=".pdf" required>
                        <div style="color: red; margin-top: 15px;" id="error_carta_beneficiarios_finales" style="color: red; display: none;"></div>
                    </div>
                    <div class="col-md-12" style="display: none;" id="filePreviewContainercarta_beneficiarios_finales">
                        <div id="filePreviewContentcarta_beneficiarios_finales" class="mt-4"></div>
                        <button class="btn btn-success" id="modifyFileButtoncarta_beneficiarios_finales" type="button" style="display: none; margin-top: 10px;" onclick="showFileInputIdBF(this)">Modificar archivo</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</section>

<button id="next_cartaBeneficiariosFinales" class="btn btn-Laft-next"><?php echo $lang['siguiente'] ?></button>
<button id="back_cartaBeneficiariosFinales" class="btn btn-Laft-back"><?php echo $lang['anterior'] ?></button>

<script>
    $(document).ready(function() {

        const fileInputs = document.querySelectorAll('.file-input');

        fileInputs.forEach(function(input) {
            input.addEventListener('change', function(event) {
                const file = event.target.files[0];
                const fileExtension = file ? file.name.split('.').pop().toLowerCase() : '';
                const errorContainer = document.getElementById('error_' + event.target.id);

                if (fileExtension !== 'pdf') {
                    event.target.value = '';
                    errorContainer.style.display = 'block';
                    errorContainer.textContent = 'Solo se permiten archivos PDF.';
                } else {
                    errorContainer.style.display = 'none';
                    errorContainer.textContent = '';

                }
            });
        });
    });

    function showFileInputIdBF(fileId) {
        var newfileid = fileId.id.replace('modifyFileButton', '');

        var fileFieldContainer = document.getElementById("file_" + newfileid);

        var filePreviewContainer = document.getElementById("filePreviewContainer" + newfileid);
        var modifyFileButton = document.getElementById("modifyFileButton" + newfileid);

        fileFieldContainer.style.display = "block";
        filePreviewContainer.innerHTML = '';
        filePreviewContainer.style.display = "none";
        modifyFileButton.style.display = "none";
        $('#' + newfileid).attr("required", "required");
    }
</script>