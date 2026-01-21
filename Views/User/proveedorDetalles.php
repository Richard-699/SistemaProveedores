<?php
session_start();

if (empty($_SESSION["id_rol_usuarios"])) {
    header("Location:../../index.php");
}
if ($_SESSION["id_rol_usuarios"] != 2) {
    header("Location:../../index.php");
}

include('../../ConexionBD/conexion.php');

$Id_proveedor = $_GET['id_proveedor'];
$Id_area_usuario = $_SESSION['Id_area_usuario'];

error_reporting(0);
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
    <link rel="stylesheet" href="../../Estilos/User/estilos_registrarPartNumber.css">
    <link rel="stylesheet" href="../../Estilos/Supplier/estilos_costBreakDown.css">
    <link rel="stylesheet" href="../../Estilos/Generals/estilos_nav.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Vinculación Proveedor</title>
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
                <li class="mt-4">
                    <a href="index.php" class="text-decoration-none px-3 py-2 d-block">
                        <i class="material-icons">home</i> Inicio
                    </a>
                </li>
                <?php
                if ($Id_area_usuario == 1) {
                ?>
                    <li class="">
                        <a href="registrarProveedor.php" class="text-decoration-none px-3 py-2 d-block">
                            <i class="material-icons">apartment</i> Agregar Proveedor
                        </a>
                    </li>
                    <li class="">
                        <a href="proveedoresRegistrados.php" class="text-decoration-none px-3 py-2 d-block">
                            <i class="material-icons">groups</i> Proveedores Registrados
                        </a>
                    </li>
                    <li class="">
                        <a href="registrarPartNumber.php" class="text-decoration-none px-3 py-2 d-block">
                            <i class="material-icons">add</i> Agregar PartNumber
                        </a>
                    </li>
                    <li class="">
                        <a href="partNumbers.php" class="text-decoration-none px-3 py-2 d-block">
                            <i class="material-icons">precision_manufacturing</i> PartNumbers
                        </a>
                    </li>
                    <li class="">
                        <a href="resultadoCbd.php" class="text-decoration-none px-3 py-2 d-block">
                            <i class="material-icons">description</i> Resultado Cbd
                        </a>
                    </li>
                <?php
                }
                ?>
                <li class="">
                    <a href="vincularProveedor.php" class="text-decoration-none px-3 py-2 d-block">
                        <i class="material-icons">person_add</i> Vinculación Proveedor
                    </a>
                </li>
                <?php
                if ($Id_area_usuario == 3) {
                ?>
                    <li class="">
                        <a href="historicoLAFT.php" class="text-decoration-none px-3 py-2 d-block">
                            <i class="material-icons">history</i> Histórico LAFT
                        </a>
                    </li>
                <?php } ?>
                <li class="">
                    <a href="proveedores.php" class="text-decoration-none px-3 py-2 d-block">
                        <i class="material-icons">how_to_reg</i> Proveedores Vinculados
                    </a>
                </li>
                <?php
                if ($Id_area_usuario == 1) {
                ?>
                    <li class="">
                        <a href="serviciosSuministros.php" class="text-decoration-none px-3 py-2 d-block">
                            <i class="material-icons">design_services</i> Servicios/Suministros
                        </a>
                    </li>
                <?php
                }
                ?>
            </ul>
        </div>
        <div class="content" id="contenido">

            <div class="franja">
                <div class="datos-sesion">
                    <i class="material-icons icon-nav">person</i>
                    <h2 class="usuario"><?php echo $_SESSION["nombre_usuario"]; ?></h2>
                    <div class="separador"></div>
                    <a href="../../Actions/Generals/cerrarsesion.php" class="cerrar-sesion">
                        <i class="material-icons icon-nav">logout</i>
                        <h2 class="text-cerrarsesion">Cerrar Sesión</h2>
                    </a>
                </div>
            </div>

            <div class="form-container">
                <h2 class="title">Vinculación Proveedor</h2>
                <div class="pdf-preview">
                    <div class="pdf-container">
                        <?php
                        
                        $stmt = $conexion->prepare("SELECT MAX(fecha_actualizacion_historico) AS ultima_fecha
                                                    FROM laft_historico
                                                    WHERE Id_proveedor_laft_historico = ?");
                        $stmt->bind_param("s", $Id_proveedor);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $ultimaActualizacionLAFT = $result->fetch_assoc();

                        $rutaLAFT = '../../documents/' . $Id_proveedor . '/LAFT/' . $ultimaActualizacionLAFT['ultima_fecha'] . '/LAFT.pdf';
                        $rutaAmbiental = '../../documents/' . $Id_proveedor . '/Ambiental.pdf';

                        $consultarTipoPersona = $conexion->prepare("SELECT * FROM laft WHERE Id_proveedor_laft = ?");
                        $consultarTipoPersona->bind_param("s", $Id_proveedor);
                        $consultarTipoPersona->execute();
                        $resultado = $consultarTipoPersona->get_result();

                        if ($resultado && $resultado->num_rows > 0) {
                            $row = $resultado->fetch_assoc();
                            $tipo_persona_laft = $row['tipo_persona_laft'];
                            $Id_laft = $row['Id_laft'];
                        } else {
                            $tipo_persona_laft = null;
                        }

                        ?>
                        <div class="row" style="margin-top: 100px;">
                            <?php
                            if ($Id_area_usuario != 2 && $Id_area_usuario != 4) {
                            ?>
                                <div class="col-md-6 mt-5">
                                    <h3 class="subtitle">Formato de Vinculación</h3>
                                    <iframe width="100%" height="200px" src="<?php echo $rutaLAFT ?>#toolbar=0"></iframe>
                                    <a href="<?php echo $rutaLAFT ?>" target="_blank" class="btn-descarga"> Abrir Documento</a>
                                </div>
                            <?php
                            if ($Id_area_usuario == 1) {
                            ?>
                                <div class="col-md-6 mt-5">
                                    <h3 class="subtitle">Documento Ambiental</h3>
                                    <iframe width="100%" height="200px" src="<?php echo $rutaAmbiental ?>#toolbar=0"></iframe>
                                    <a href="<?php echo $rutaAmbiental ?>" target="_blank" class="btn-descarga"> Abrir Documento</a>
                                </div>

                            <?php } ?>

                                <div class="col-md-6 mt-5">
                                </div>

                                <?php
                                if ($tipo_persona_laft == "Juridica") {
                                    $consultarCertificaciones = $conexion->prepare("SELECT * FROM laft_persona_juridica 
                                                            INNER JOIN laft_certificaciones ON laft_persona_juridica.Id_persona_juridica = laft_certificaciones.Id_laft_persona_juridica_certificacion
                                                            WHERE Id_laft_persona_juridica = ?");
                                    $consultarCertificaciones->bind_param("s", $Id_laft);
                                    $consultarCertificaciones->execute();
                                    $resultado = $consultarCertificaciones->get_result();

                                    $ISO_9001 = false;
                                    $ISO_14001 = false;
                                    $ISO_45001 = false;
                                    $BASC = false;
                                    $OEA = false;
                                    $otro_certificacion = false;
                                    $existenDocumentos = false;
                                    $titleOtro_certificacion = null;

                                    $Inspektor1 = false;
                                    $Inspektor2 = false;

                                    while ($fila = $resultado->fetch_assoc()) {
                                        if ($fila['ISO_9001'] == 1) {
                                            $ISO_9001 = true;
                                            $existenDocumentos = true;
                                        }
                                        if ($fila['ISO_14001'] == 1) {
                                            $ISO_14001 = true;
                                            $existenDocumentos = true;
                                        }
                                        if ($fila['ISO_45001'] == 1) {
                                            $ISO_45001 = true;
                                            $existenDocumentos = true;
                                        }
                                        if ($fila['BASC'] == 1) {
                                            $BASC = true;
                                            $existenDocumentos = true;
                                        }
                                        if ($fila['OEA'] == 1) {
                                            $OEA = true;
                                            $existenDocumentos = true;
                                        }
                                        if ($fila['otro_certificacion'] != null || $fila['otro_certificacion'] != "") {
                                            $otro_certificacion = true;
                                            $titleOtro_certificacion = $fila['otro_certificacion'];
                                            $existenDocumentos = true;
                                        }
                                    }
                                }

                                $consultarDocumentos = $conexion->prepare("SELECT * FROM laft_documentos WHERE Id_laft_documentos = ?");
                                $consultarDocumentos->bind_param("s", $Id_laft);
                                $consultarDocumentos->execute();
                                $resultado = $consultarDocumentos->get_result();

                                $text_certifications = true;
                                $text_documents = true;
                                $text_inspektor = true;

                                while ($fila = $resultado->fetch_assoc()) {
                                    if ($text_certifications && in_array($fila['tipo_documento_laft'], ["ISO_9001", "ISO_14001", "ISO_45001", "BASC", "OEA", "Otro"])) {
                                        $text_certifications = false;
                                ?>
                                        <hr class="mt-5">
                                        <h2 class="mt-4">Certificaciones:</h2>
                                    <?php
                                    }

                                    if ($ISO_9001 && $fila['tipo_documento_laft'] == "ISO_9001") {
                                    ?>
                                        <div class="col-md-6 mt-5">
                                            <h3 class="subtitle">ISO_9001</h3>
                                            <iframe width="100%" height="200px" src="<?php echo $fila['documento_laft'] ?>#toolbar=0"></iframe>
                                            <a href="<?php echo $fila['documento_laft'] ?>" target="_blank" class="btn-descarga">Abrir Documento</a>
                                        </div>
                                    <?php
                                    } elseif ($ISO_14001 && $fila['tipo_documento_laft'] == "ISO_14001") {
                                    ?>
                                        <div class="col-md-6 mt-5">
                                            <h3 class="subtitle">ISO_14001</h3>
                                            <iframe width="100%" height="200px" src="<?php echo $fila['documento_laft'] ?>#toolbar=0"></iframe>
                                            <a href="<?php echo $fila['documento_laft'] ?>" target="_blank" class="btn-descarga">Abrir Documento</a>
                                        </div>
                                    <?php
                                    } elseif ($ISO_45001 && $fila['tipo_documento_laft'] == "ISO_45001") {
                                    ?>
                                        <div class="col-md-6 mt-5">
                                            <h3 class="subtitle">ISO_45001</h3>
                                            <iframe width="100%" height="200px" src="<?php echo $fila['documento_laft'] ?>#toolbar=0"></iframe>
                                            <a href="<?php echo $fila['documento_laft'] ?>" target="_blank" class="btn-descarga">Abrir Documento</a>
                                        </div>
                                    <?php
                                    } elseif ($BASC && $fila['tipo_documento_laft'] == "BASC") {
                                    ?>
                                        <div class="col-md-6 mt-5">
                                            <h3 class="subtitle">BASC</h3>
                                            <iframe width="100%" height="200px" src="<?php echo $fila['documento_laft'] ?>#toolbar=0"></iframe>
                                            <a href="<?php echo $fila['documento_laft'] ?>" target="_blank" class="btn-descarga">Abrir Documento</a>
                                        </div>
                                    <?php
                                    } elseif ($OEA && $fila['tipo_documento_laft'] == "OEA") {
                                    ?>
                                        <div class="col-md-6 mt-5">
                                            <h3 class="subtitle">OEA</h3>
                                            <iframe width="100%" height="200px" src="<?php echo $fila['documento_laft'] ?>#toolbar=0"></iframe>
                                            <a href="<?php echo $fila['documento_laft'] ?>" target="_blank" class="btn-descarga">Abrir Documento</a>
                                        </div>
                                    <?php
                                    } elseif ($otro_certificacion && $fila['tipo_documento_laft'] == "Otro") {
                                    ?>
                                        <div class="col-md-6 mt-5">
                                            <h3 class="subtitle"><?php echo "Otro: " . $titleOtro_certificacion; ?></h3>
                                            <iframe width="100%" height="200px" src="<?php echo $fila['documento_laft'] ?>#toolbar=0"></iframe>
                                            <a href="<?php echo $fila['documento_laft'] ?>" target="_blank" class="btn-descarga">Abrir Documento</a>
                                        </div>
                                    <?php
                                    }
                                }

                                $resultado->data_seek(0);

                                while ($fila = $resultado->fetch_assoc()) {
                                    if ($text_documents && in_array($fila['tipo_documento_laft'], ["RUT", "Cámara de Comercio", "Copia Cédula Rep. Legal"])) {
                                        $text_documents = false;
                                    ?>
                                        <hr class="mt-5">
                                        <h2 class="mt-4">Documentación:</h2>
                                    <?php
                                    }

                                    if ($fila['tipo_documento_laft'] == "RUT") {
                                    ?>
                                        <div class="col-md-6 mt-5">
                                            <h3 class="subtitle"><?php echo "RUT"; ?></h3>
                                            <iframe width="100%" height="200px" src="<?php echo $fila['documento_laft'] ?>#toolbar=0"></iframe>
                                            <a href="<?php echo $fila['documento_laft'] ?>" target="_blank" class="btn-descarga">Abrir Documento</a>
                                        </div>
                                    <?php
                                    } elseif ($fila['tipo_documento_laft'] == "Cámara de Comercio") {
                                    ?>
                                        <div class="col-md-6 mt-5">
                                            <h3 class="subtitle"><?php echo "Cámara de Comercio"; ?></h3>
                                            <iframe width="100%" height="200px" src="<?php echo $fila['documento_laft'] ?>#toolbar=0"></iframe>
                                            <a href="<?php echo $fila['documento_laft'] ?>" target="_blank" class="btn-descarga">Abrir Documento</a>
                                        </div>
                                    <?php
                                    } elseif ($fila['tipo_documento_laft'] == "Copia Cédula Rep. Legal") {
                                    ?>
                                        <div class="col-md-6 mt-5">
                                            <h3 class="subtitle"><?php echo "Copia Cédula Rep. Legal"; ?></h3>
                                            <iframe width="100%" height="200px" src="<?php echo $fila['documento_laft'] ?>#toolbar=0"></iframe>
                                            <a href="<?php echo $fila['documento_laft'] ?>" target="_blank" class="btn-descarga">Abrir Documento</a>
                                        </div>
                                    <?php
                                    } elseif ($fila['tipo_documento_laft'] == "Certificación Bancaria") {
                                    ?>
                                        <div class="col-md-6 mt-5">
                                            <h3 class="subtitle"><?php echo "Certificación Bancaria"; ?></h3>
                                            <iframe width="100%" height="200px" src="<?php echo $fila['documento_laft'] ?>#toolbar=0"></iframe>
                                            <a href="<?php echo $fila['documento_laft'] ?>" target="_blank" class="btn-descarga">Abrir Documento</a>
                                        </div>
                                    <?php
                                    } elseif ($fila['tipo_documento_laft'] == "Certificado de Afiliación") {
                                    ?>
                                        <div class="col-md-6 mt-5">
                                            <h3 class="subtitle"><?php echo "Certificado de Afiliación"; ?></h3>
                                            <iframe width="100%" height="200px" src="<?php echo $fila['documento_laft'] ?>#toolbar=0"></iframe>
                                            <a href="<?php echo $fila['documento_laft'] ?>" target="_blank" class="btn-descarga">Abrir Documento</a>
                                        </div>
                                    <?php
                                    } elseif ($fila['tipo_documento_laft'] == "Carta Beneficiarios Finales") {
                                    ?>
                                        <div class="col-md-6 mt-5">
                                            <h3 class="subtitle"><?php echo "RUB"; ?></h3>
                                            <iframe width="100%" height="200px" src="<?php echo $fila['documento_laft'] ?>#toolbar=0"></iframe>
                                            <a href="<?php echo $fila['documento_laft'] ?>" target="_blank" class="btn-descarga">Abrir Documento</a>
                                        </div>
                                    <?php
                                    } elseif ($fila['tipo_documento_laft'] == "requiere_permiso_licencia") {
                                    ?>
                                        <div class="col-md-6 mt-5">
                                            <h3 class="subtitle"><?php echo "Permiso/Licencia Operación"; ?></h3>
                                            <iframe width="100%" height="200px" src="<?php echo $fila['documento_laft'] ?>#toolbar=0"></iframe>
                                            <a href="<?php echo $fila['documento_laft'] ?>" target="_blank" class="btn-descarga">Abrir Documento</a>
                                        </div>
                                    <?php
                                    } elseif ($fila['tipo_documento_laft'] == "Carta que Certifica no Tiene Personas a Cargo") {
                                    ?>
                                        <div class="col-md-6 mt-5">
                                            <h3 class="subtitle"><?php echo "Certifica NO Tiene Personas a Cargo"; ?></h3>
                                            <iframe width="100%" height="200px" src="<?php echo $fila['documento_laft'] ?>#toolbar=0"></iframe>
                                            <a href="<?php echo $fila['documento_laft'] ?>" target="_blank" class="btn-descarga">Abrir Documento</a>
                                        </div>
                                    <?php
                                    } elseif ($fila['tipo_documento_laft'] == "PDF Portafolio Servicios" && $fila['is_url_documento_laft'] == 0) {
                                        ?>
                                            <div class="col-md-6 mt-5">
                                                <h3 class="subtitle"><?php echo "Pdf Portafolio Servicios/Suministros"; ?></h3>
                                                <iframe width="100%" height="200px" src="<?php echo $fila['documento_laft'] ?>#toolbar=0"></iframe>
                                                <a href="<?php echo $fila['documento_laft'] ?>" target="_blank" class="btn-descarga">Abrir Documento</a>
                                            </div>
                                        <?php
                                    } elseif ($fila['tipo_documento_laft'] == "PDF Portafolio Servicios" && $fila['is_url_documento_laft'] == 1) {
                                        ?>
                                            <div class="col-md-6 mt-5">
                                                <h3 class="subtitle"><?php echo "Url Portafolio Servicios/Suministros"; ?></h3>
                                                <a href="<?php echo $fila['documento_laft'] ?>" target="_blank"><?php echo $fila['documento_laft'] ?></a>
                                            </div>
                                        <?php
                                    }
                                }

                                $resultado->data_seek(0);

                                while ($fila = $resultado->fetch_assoc()) {
                                    if ($text_inspektor && in_array($fila['tipo_documento_laft'], ["Inspektor1", "Inspektor2"])) {
                                        $text_inspektor = false;
                                    ?>
                                        <hr class="mt-5">
                                        <h2 class="mt-4">Inspektor Cumplimiento:</h2>
                                    <?php
                                    }

                                    if ($fila['tipo_documento_laft'] == "Inspektor1") {
                                    ?>
                                        <div class="col-md-6 mt-5">
                                            <h3 class="subtitle"><?php echo "Inspektor 1"; ?></h3>
                                            <iframe width="100%" height="200px" src="<?php echo $fila['documento_laft'] ?>#toolbar=0"></iframe>
                                            <a href="<?php echo $fila['documento_laft'] ?>" target="_blank" class="btn-descarga">Abrir Documento</a>
                                        </div>
                                    <?php
                                    } elseif ($fila['tipo_documento_laft'] == "Inspektor2") {
                                    ?>
                                        <div class="col-md-6 mt-5">
                                            <h3 class="subtitle"><?php echo "Inspektor 2"; ?></h3>
                                            <iframe width="100%" height="200px" src="<?php echo $fila['documento_laft'] ?>#toolbar=0"></iframe>
                                            <a href="<?php echo $fila['documento_laft'] ?>" target="_blank" class="btn-descarga">Abrir Documento</a>
                                        </div>
                                <?php
                                    }
                                }
                            }

                            if ($Id_area_usuario == 2) {
                                ?>
                                <div class="col-md-6">
                                    <h3 class="subtitle">Documento Ambiental</h3>
                                    <iframe width="100%" height="200px" src="<?php echo $rutaAmbiental ?>#toolbar=0"></iframe>
                                    <a href="<?php echo $rutaAmbiental ?>" target="_blank" class="btn-descarga"> Abrir Documento</a>
                                </div>
                            <?php
                            }
                            ?>
                        </div>
                        <hr>
                        <?php

                        $ocultarButtons = false;

                        if ($Id_area_usuario == 1 || $Id_area_usuario == 3) {
                            $consulta = $conexion->prepare("SELECT aprobado_cumplimiento, observaciones_cumplimiento, aprobado_ambiental, observaciones_ambiental FROM vinculacion_proveedor WHERE Id_proveedor_vinculacion_proveedor = ?");
                            $consulta->bind_param("s", $Id_proveedor);
                            $consulta->execute();
                            $resultado = $consulta->get_result();

                            if ($resultado && $resultado->num_rows > 0) {
                                $row = $resultado->fetch_assoc();

                                $aprobado_cumplimiento = $row['aprobado_cumplimiento'];
                                if($aprobado_cumplimiento == 0){
                                    $ocultarButtons = true;
                                }
                                $observaciones_cumplimiento = $row['observaciones_cumplimiento'];

                                $aprobado_ambiental = $row['aprobado_ambiental'];
                                $observaciones_ambiental = $row['observaciones_ambiental'];
                                if($observaciones_cumplimiento != null){
                        ?>
                                <p>Cumplimiento: <?php echo $aprobado_cumplimiento == 1 ? "Aprobado" : "Rechazado"; ?></p>
                                <p>Observaciones: <?php echo htmlspecialchars($observaciones_cumplimiento); ?></p>

                                <?php if ($observaciones_ambiental != null) { ?>
                                    <p>Ambiental: <?php echo $aprobado_ambiental == 1 ? "Aprobado" : "Rechazado"; ?></p>
                                    <p>Observaciones: <?php echo htmlspecialchars($observaciones_ambiental); ?></p>
                                <?php } ?>

                                <hr>
                        <?php
                                }
                            } else {
                                echo "<p>No se encontraron registros para el proveedor seleccionado.</p>";
                            }

                            $resultado->free();
                            $consulta->close();
                        }
                        ?>
                        <div class="buttons">
                        <?php
                            if($Id_area_usuario == 1){
                                if(!$ocultarButtons){
                                    ?>
                                       <button type="button" id="aprobar" class="btn-aprobar">Aprobar</button>
                                       <button type="button" id="rechazar" class="btn btn-danger">Rechazar</button>
                                       <input type="hidden" id="idProveedor" value="<?php echo htmlspecialchars($Id_proveedor); ?>">
                                    <?php
                                }
                            }else{
                                ?>
                                    <button type="button" id="aprobar" class="btn-aprobar">Aprobar</button>
                                    <button type="button" id="rechazar" class="btn btn-danger">Rechazar</button>
                                    <input type="hidden" id="idProveedor" value="<?php echo htmlspecialchars($Id_proveedor); ?>">
                                <?php
                            }
                            ?>
                        </div>
                        <br><br><br><br><br><br><br>
                    </div>
                </div>
            </div>

        </div>
    </div>
</body>

</html>

<?php
if ($Id_area_usuario == 3) {
?>
    <script>
        document.getElementById('aprobar').addEventListener('click', function() {
            var id_proveedor = document.getElementById('idProveedor').value;
            Swal.fire({
                title: 'Confirmar Aprobación',
                html: `
                <br>
                    <label for="file1">Documento de Inspektor 1:</label>
                    <input type="file" name="doc_Inspektor1" id="doc_Inspektor1" class="form-control mt-4" accept=".pdf">
                    <br>
                    <hr>
                    <br>
                    <label for="file2">Documento de Inspektor 2:</label>
                    <input type="file" name="doc_Inspektor2" id="doc_Inspektor2" class="form-control mt-4" accept=".pdf">
                    <br>
                    <hr>
                    <br>
                    <label>Observaciones: </label>
                    <textarea class="form-control mt-4" id="observaciones_cumplimiento" name="observaciones_cumplimiento"></textarea>
                <br>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#072B31',
                confirmButtonText: 'Aprobar',
                cancelButtonText: 'Cancelar',
            }).then((result) => {
                if (result.isConfirmed) {
                    aprobarCumplimiento('Cumplimiento', id_proveedor);
                }
            });
        });

        function aprobarCumplimiento(opcion, id_proveedor) {
            var file1 = document.getElementById('doc_Inspektor1').files[0];
            var file2 = document.getElementById('doc_Inspektor2').files[0];
            var observaciones = document.getElementById('observaciones_cumplimiento').value;

            var formData = new FormData();
            formData.append('opcion', opcion);
            formData.append('id_proveedor', id_proveedor);
            formData.append('doc_Inspektor1', file1);
            formData.append('doc_Inspektor2', file2);
            formData.append('observaciones_cumplimiento', observaciones);

            fetch('../../Actions/User/aprobarProveedor.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
            Swal.fire({
                icon: "success",
                title: "Aprobación Exitosa",
                text: "La aprobación del proveedor ha sido exitosa y notificada a Negociación.",
                showConfirmButton: false,
                timer: 3000,
                allowOutsideClick: false
            });
            setTimeout(function() {
                    window.location.href = "vincularProveedor.php";
                }, 3000)
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        document.getElementById('rechazar').addEventListener('click', function() {
            var id_proveedor = document.getElementById('idProveedor').value;
            Swal.fire({
                title: 'Confirmar Rechazo',
                html: `
                <br>
                    <label for="file1">Documento de Inspektor 1:</label>
                    <input type="file" name="doc_Inspektor1" id="doc_Inspektor1" class="form-control mt-4" accept=".pdf">
                    <br>
                    <hr>
                    <br>
                    <label for="file2">Documento de Inspektor 2:</label>
                    <input type="file" name="doc_Inspektor2" id="doc_Inspektor2" class="form-control mt-4" accept=".pdf">
                    <br>
                    <hr>
                    <br>
                    <label>Observaciones: </label>
                    <textarea class="form-control mt-4" id="observaciones_cumplimiento" name="observaciones_cumplimiento"></textarea>
                <br>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: 'red',
                confirmButtonText: 'Rechazar',
                cancelButtonText: 'Cancelar',
            }).then((result) => {
                if (result.isConfirmed) {
                    rechazarCumplimiento('Cumplimiento', id_proveedor);
                }
            });
        });

        function rechazarCumplimiento(opcion, id_proveedor) {
            var file1 = document.getElementById('doc_Inspektor1').files[0];
            var file2 = document.getElementById('doc_Inspektor2').files[0];
            var observaciones = document.getElementById('observaciones_cumplimiento').value;

            var formData = new FormData();
            formData.append('opcion', opcion);
            formData.append('id_proveedor', id_proveedor);
            formData.append('doc_Inspektor1', file1);
            formData.append('doc_Inspektor2', file2);
            formData.append('observaciones_cumplimiento', observaciones);

            fetch('../../Actions/User/rechazarProveedor.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
            Swal.fire({
                icon: "success",
                title: "Rechazo Exitoso",
                text: "El rechazo del proveedor ha sido exitoso y notificado a Negociación.",
                showConfirmButton: false,
                timer: 3000,
                allowOutsideClick: false
            });
            setTimeout(function() {
                    window.location.href = "vincularProveedor.php";
                }, 3000)
                .catch(error => {
                    console.error('Error:', error);
                });
        }
    </script>
<?php
}
if ($Id_area_usuario == 2) {
?>
    <script>
        document.getElementById('aprobar').addEventListener('click', function() {
            var id_proveedor = document.getElementById('idProveedor').value;
            Swal.fire({
                title: 'Confirmar Aprobación',
                html: `
                <br>
                    <label>Observaciones: </label>
                    <textarea class="form-control mt-4" id="observaciones_ambiental" name="observaciones_ambiental"></textarea>
                <br>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#072B31',
                confirmButtonText: 'Aprobar',
                cancelButtonText: 'Cancelar',
            }).then((result) => {
                if (result.isConfirmed) {
                    aprobarAmbiental('Ambiental', id_proveedor);
                }
            });
        });

        function aprobarAmbiental(opcion, id_proveedor) {
            var observaciones = document.getElementById('observaciones_ambiental').value;

            var formData = new FormData();
            formData.append('opcion', opcion);
            formData.append('id_proveedor', id_proveedor);
            formData.append('observaciones_ambiental', observaciones);

            fetch('../../Actions/User/aprobarProveedor.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
            Swal.fire({
                icon: "success",
                title: "Aprobación Exitosa",
                text: "La aprobación del proveedor en la Gestión Ambiental ha sido exitosa.",
                showConfirmButton: false,
                timer: 3000,
                allowOutsideClick: false
            });
            setTimeout(function() {
                    window.location.href = "vincularProveedor.php";
                }, 3000)
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        document.getElementById('rechazar').addEventListener('click', function() {
            var id_proveedor = document.getElementById('idProveedor').value;
            Swal.fire({
                title: 'Confirmar Rechazo',
                html: `
                <br>
                    <label>Observaciones: </label>
                    <textarea class="form-control mt-4" id="observaciones_ambiental" name="observaciones_ambiental"></textarea>
                <br>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: 'red',
                confirmButtonText: 'Rechazar',
                cancelButtonText: 'Cancelar',
            }).then((result) => {
                if (result.isConfirmed) {
                    rechazarAmbiental('Ambiental', id_proveedor);
                }
            });
        });

        function rechazarAmbiental(opcion, id_proveedor) {
            var observaciones = document.getElementById('observaciones_ambiental').value;

            var formData = new FormData();
            formData.append('opcion', opcion);
            formData.append('id_proveedor', id_proveedor);
            formData.append('observaciones_ambiental', observaciones);

            fetch('../../Actions/User/rechazarProveedor.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
            Swal.fire({
                icon: "success",
                title: "Rechazo Exitoso",
                text: "El rechazo del proveedor por Gestión Ambiental ha sido exitoso.",
                showConfirmButton: false,
                timer: 3000,
                allowOutsideClick: false
            });
            setTimeout(function() {
                    window.location.href = "vincularProveedor.php";
                }, 3000)
                .catch(error => {
                    console.error('Error:', error);
                });
        }
    </script>
<?php
}

if ($Id_area_usuario == 1) {
?>
    <script>
        document.getElementById('aprobar').addEventListener('click', function() {
            var id_proveedor = document.getElementById('idProveedor').value;
            
            $.ajax({
                url: '../../Actions/User/consultarNumeroAcreedor.php',
                type: 'POST',
                data: {
                    id_proveedor: id_proveedor
                },
                success: function(response) {
                    
                    var data = JSON.parse(response);
                    if (data.success) {
                        if (data.numero_acreedor == 0 || data.numero_acreedor == null) {
                            var htmljs = `
                    <br>
                        <label>Número de Acreedor: </label>
                        <input type="number" class="form-control mt-4" id="numero_acreedor" name="numero_acreedor" required></input>
                        <label class="mt-4">Observaciones: </label>
                        <textarea class="form-control mt-4" id="observaciones_negociacion" name="observaciones_negociacion"></textarea>
                    <br>
                    `;
                        } else {
                            var htmljs = `
                    <br>
                        <label>Observaciones: </label>
                        <textarea class="form-control mt-4" id="observaciones_negociacion" name="observaciones_negociacion"></textarea>
                    <br>
                    `;
                        }

                        Swal.fire({
                            title: 'Confirmar Aprobación',
                            html: htmljs,
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonColor: '#072B31',
                            confirmButtonText: 'Aprobar',
                            cancelButtonText: 'Cancelar',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                aprobarNegociacion('Negociacion', id_proveedor, data.numero_acreedor);
                            }
                        });
                    }
                }
            });
        });

        function aprobarNegociacion(opcion, id_proveedor, numero_acreedor) {
            
            if(numero_acreedor == null){
                numero_acreedor = document.getElementById('numero_acreedor').value;
            }
            var observaciones = document.getElementById('observaciones_negociacion').value;

            var formData = new FormData();
            formData.append('opcion', opcion);
            formData.append('id_proveedor', id_proveedor);
            if(numero_acreedor){
                formData.append('numero_acreedor', numero_acreedor);
            }
            formData.append('observaciones_negociacion', observaciones);

            fetch('../../Actions/User/aprobarProveedor.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
            Swal.fire({
                icon: "success",
                title: "Aprobación Exitosa",
                text: "La aprobación del proveedor ha sido exitosa.",
                showConfirmButton: false,
                timer: 3000,
                allowOutsideClick: false
            });
            setTimeout(function() {
                    window.location.href = "vincularProveedor.php";
                }, 3000)
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        document.getElementById('rechazar').addEventListener('click', function() {
            var id_proveedor = document.getElementById('idProveedor').value;
            Swal.fire({
                title: 'Confirmar Rechazo',
                html: `
                    <br>
                        <label>Observaciones: </label>
                        <textarea class="form-control mt-4" id="observaciones_negociacion" name="observaciones_negociacion"></textarea>
                    <br>
                    `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: 'red',
                confirmButtonText: 'Rechazar',
                cancelButtonText: 'Cancelar',
            }).then((result) => {
                if (result.isConfirmed) {
                    rechazarNegociacion('Negociación', id_proveedor);
                }
            });
        });

        function rechazarNegociacion(opcion, id_proveedor) {
            var observaciones = document.getElementById('observaciones_negociacion').value;

            var formData = new FormData();
            formData.append('opcion', opcion);
            formData.append('id_proveedor', id_proveedor);
            formData.append('observaciones_negociacion', observaciones);

            fetch('../../Actions/User/rechazarProveedor.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
            Swal.fire({
                icon: "success",
                title: "Rechazo Exitoso",
                text: "El rechazo del proveedor ha sido exitoso.",
                showConfirmButton: false,
                timer: 3000,
                allowOutsideClick: false
            });
            setTimeout(function() {
                    window.location.href = "vincularProveedor.php";
                }, 3000)
                .catch(error => {
                    console.error('Error:', error);
                });
        }
    </script>
<?php
}
?>