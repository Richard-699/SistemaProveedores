<?php
session_start();

$ruta = '../../IdiomaConfig/' . $_SESSION['lang'] . '.php';
include($ruta);
$maneja_formato_costbreakdown = $_SESSION['maneja_formato_costbreakdown'];

if (empty($_SESSION["id_rol_usuarios"])) {
    header("Location:../../index.php");
}
if ($_SESSION["id_rol_usuarios"] != 3) {
    header("Location:../../index.php");
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="shortcut icon" href="../../img/LogoBlanco.png" type="image/x-icon">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.5/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.5/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../../Estilos/Supplier/estilos_costBreakDown.css">
    <link rel="stylesheet" href="../../Estilos/Generals/estilos_nav.css">
    <title>Cost Break Down HWI</title>

    <style>
        .hidden {
            display: none;
        }

        iframe {
            border: none;
            box-shadow: none;
            overflow: hidden;
        }

        .file-container {
            margin-bottom: 20px;
        }

        .file-container h3 {
            margin: 0;
        }
    </style>
</head>

<body>

    <div class="main-container d-flex">
        <div class="sidebar" id="side_nav">
            <div class="header-box px-2 pt-3 pb-4 d-flex justify-content-between">
            </div>

            <ul class="list-unstyled px-2">
                <li>
                    <a class="fondo-img" href="index.php">
                        <img src="../../img/hwiLogo.png" class="img-logo">
                    </a>
                </li>
                <li class="mt-5">
                    <a href="index.php" class="text-decoration-none px-3 py-2 d-block">
                        <i class="material-icons">home</i> <?php echo $lang['Inicio']; ?>
                    </a>
                </li>
                <?php if ($maneja_formato_costbreakdown == 1) { ?>
                    <li class="">
                        <a href="costBreakDown.php" class="text-decoration-none px-3 py-2 d-block">
                            <i class="material-icons">assignment</i> <?php echo $lang['Diligenciar CostBreakDown']; ?>
                        </a>
                    </li>
                <?php } ?>
                <li class="">
                    <a href="#" class="text-decoration-none px-3 py-2 d-block">
                        <i class="material-icons">file_copy</i> <?php echo $lang['Documentacion']; ?>
                    </a>
                </li>
            </ul>
        </div>
        <div class="content" id="contenido">

            <div class="franja">
                <div class="datos-sesion">
                    <i class="material-icons icon-nav">person</i>
                    <h2 class="usuario"><?php echo $_SESSION["nombre_proveedor"]; ?></h2>
                    <div class="separador"></div>
                    <a href="../../Actions/Generals/cerrarsesion.php" class="cerrar-sesion">
                        <i class="material-icons icon-nav">logout</i>
                        <h2 class="text-cerrarsesion"><?php echo $lang['cerrarsesion']; ?></h2>
                    </a>
                </div>
            </div>

            <div class="form-container">
                <h2 class="title"><?php echo $lang['consultar_documentacion'] ?></h2>
                <div class="row docum">
                    <div class="col-md-3 mt-5">
                        <p><?php echo $lang['seleccionar_ano'] ?></p>
                        <input class="form-control" name="ano_documento" id="ano_documento" type="number" id="year" name="year" min="1900" max="2100" step="1" required>
                    </div>
                    <div class="col-md-4 mt-5">
                        <p><?php echo $lang['seleccionar_tipo_doc'] ?></p>
                        <select class="form-select" id="tipo_documento" name="tipo_documento">
                            <option value="" selected disabled><?php echo $lang['seleccionar_opcion'] ?></option>
                            <option value="Rete. Fuente">Rete. Fuente</option>
                            <option value="Rete. Ica">Rete. Ica</option>
                            <option value="Rete. Iva">Rete. Iva</option>
                        </select>
                    </div>
                    <div class="col-md-3 mt-5 hidden" id="vigencia_container">
                        <p><?php echo $lang['seleccionar_vigencia'] ?></p>
                        <select class="form-select" id="vigencia" name="vigencia">
                            <option value="" selected disabled><?php echo $lang['seleccionar_opcion'] ?></option>
                            <option value="01. Bimestre I">Bimestre I</option>
                            <option value="02. Bimestre II">Bimestre II</option>
                            <option value="03. Bimestre III">Bimestre III</option>
                            <option value="04. Bimestre IV">Bimestre IV</option>
                            <option value="05. Bimestre V">Bimestre V</option>
                            <option value="06. Bimestre VI">Bimestre VI</option>
                        </select>
                    </div>
                    <div class="col-md-2 mt-5">
                        <button class="btn btn-primary btn-buscar">
                            <?php echo $lang['consultar'] ?>
                            <span class="material-icons">search</span>
                        </button>
                    </div>
                </div>
                <div id="documentos-container"></div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const yearInput = document.getElementById('ano_documento');
            const tipoDocumentoSelect = document.getElementById('tipo_documento');
            const vigenciaContainer = document.getElementById('vigencia_container');
            const btnBuscar = document.querySelector('.btn-buscar');
            const resultadosDiv = document.getElementById('resultados');

            yearInput.addEventListener('input', function() {
                const value = yearInput.value;
                if (value.length > 4) {
                    yearInput.value = value.slice(0, 4);
                }
            });

            const currentYear = new Date().getFullYear();
            yearInput.value = currentYear;

            tipoDocumentoSelect.addEventListener('change', function() {
                const selectedValue = tipoDocumentoSelect.value;
                if (selectedValue === 'Rete. Ica' || selectedValue === 'Rete. Iva') {
                    vigenciaContainer.classList.remove('hidden');
                    document.getElementById('vigencia').setAttribute('required', 'required');
                } else {
                    vigenciaContainer.classList.add('hidden');
                    document.getElementById('vigencia').removeAttribute('required');
                }
            });

            $('.btn-buscar').on('click', function() {
                var ano_documento = $('#ano_documento').val();
                var tipo_documento = $('#tipo_documento').val();
                var vigencia = $('#vigencia').val();

                var overlay = document.createElement('div');
                overlay.classList.add('overlay');
                document.body.appendChild(overlay);

                var spinnerContainer = document.createElement('div');
                spinnerContainer.classList.add('spinner-container');
                overlay.appendChild(spinnerContainer);

                var spinner = document.createElement('div');
                spinner.classList.add('spinner-border', 'text-primary');
                spinner.setAttribute('role', 'status');
                spinnerContainer.appendChild(spinner);

                $.ajax({
                    url: '../../Actions/Supplier/buscar_documentos.php',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        ano_documento: ano_documento,
                        tipo_documento: tipo_documento,
                        vigencia: vigencia
                    },
                    success: function(response) {
                        if (overlay) {
                            overlay.parentNode.removeChild(overlay);
                        }

                        if (response.success) {
                            var files = response.files;
                            var documentosContainer = $('#documentos-container');
                            documentosContainer.empty();

                            $.each(files, function(index, file) {
                                var fileName = file.name;
                                var filePreviewUrl = file.previewUrl;
                                var fileDownloadUrl = file.downloadUrl;

                                var fileContainer = $('<div class="file-container"></div>');

                                if (tipo_documento != 'Rete. Fuente') {
                                    var vigencia2 = vigencia.substr(4);
                                    var fileTitle = $('<h2 class="title"></h2>').text(tipo_documento + ' ' + vigencia2 + ' ' + ano_documento);
                                } else {
                                    var fileTitle = $('<h2 class="title"></h2>').text(tipo_documento + ' ' + ano_documento);
                                }

                                var iframe = $('<iframe width="450" height="300" src="' + filePreviewUrl + '" class="custom-iframe"></iframe>');
                                var downloadButton = $('<a href="' + fileDownloadUrl + '" target="_blank"><button class="btn-buscar">Descargar</button></a>');

                                fileContainer.append(fileTitle, iframe, $('<br>'), downloadButton);
                                documentosContainer.append(fileContainer);
                            });
                        } else {
                            $('#documentos-container').html('<p>' + response.message + '</p>');
                        }
                    },
                    error: function(xhr, status, error) {
                        // Eliminar el spinner y overlay al finalizar la solicitud con error
                        if (overlay) {
                            overlay.parentNode.removeChild(overlay);
                        }

                        console.error('Error en la solicitud AJAX:', status, error);
                        $('#documentos-container').html('<p>Ocurrió un error al buscar los documentos.</p>');
                    }
                });
            });
        });
    </script>

</body>

</html>