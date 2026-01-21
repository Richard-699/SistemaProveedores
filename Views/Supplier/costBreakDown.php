<?php
session_start();

$ruta = '../../IdiomaConfig/' . $_SESSION['lang'] . '.php';
include($ruta);
$maneja_formato_costbreakdown = $_SESSION['maneja_formato_costbreakdown'];

if (empty($_SESSION["id_rol_usuarios"])) {
    header("Location:../../index.php");
}
if ($_SESSION["id_rol_usuarios"] != 3 && $maneja_formato_costbreakdown == 0) {
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
    <title>Cost Breakdown HWI</title>

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
                        <a href="#" class="text-decoration-none px-3 py-2 d-block">
                            <i class="material-icons">assignment</i> <?php echo $lang['Diligenciar CostBreakDown']; ?>
                        </a>
                    </li>
                <?php } ?>
                <li class="">
                    <a href="documentacion.php" class="text-decoration-none px-3 py-2 d-block">
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

            <?php

            if ($_SESSION['maneja_formato_costbreakdown'] == 1) {
            ?>
                <h2 class="title">Cost Breakdown</h2>
                <div class="nav-container">
                    <div class="nav-item active" tabindex="1">
                        <i class="material-icons">info</i>
                        <p><?php echo str_replace(' ', '<br>', $lang['Informacion General']); ?></p>
                    </div>
                    <div class="nav-item" tabindex="2">
                        <i class="material-icons">inventory</i>
                        <p><?php echo str_replace(' ', '<br>', $lang['Materia Prima']); ?></p>
                    </div>
                    <div class="nav-item" tabindex="3">
                        <i class="material-icons">engineering</i>
                        <p><?php echo str_replace(' ', '<br>', $lang['Proceso Productivo']); ?></p>
                    </div>
                    <div class="nav-item" tabindex="4">
                        <i class="material-icons">price_check</i>
                        <p><?php echo $lang['Amortizacion']; ?></p>
                    </div>
                    <div class="nav-item" tabindex="5">
                        <i class="material-icons">trolley</i>
                        <p><?php echo $lang['CostoEmbalaje']; ?></p>
                    </div>

                    <div class="nav-item" tabindex="6">
                        <i class="material-icons">delete_forever</i>
                        <p>Scrap</p>
                    </div>

                    <div class="nav-item" tabindex="7">
                        <i class="material-icons">check_circle</i>
                        <p>Markup</p>
                    </div>

                    <div class="nav-item" tabindex="8">
                        <i class="material-icons">description</i>
                        <p><?php echo $lang['resumen'] ?></p>
                    </div>

                    <div class="nav-connector">
                        <div class="progress"></div>
                    </div>
                </div>

                <div class="form-container">
                    <br><br><br><br>
                    <form class="" action="../../Actions/Supplier/registrarCostBreakDown.php" method="POST">

                        <div class="contenido-seccion" id="infoGeneral">
                            <div id="form1">
                                <div class="row">
                                    <p class="subtitles-seccions">
                                        <?php echo $lang['TextSeparadorMiles']; ?>
                                    </p>
                                    <div class="col-md-6 mt-5">
                                        <p>Part Number: *</p>

                                        <select required class="form-select select2" name="id_partnumber" id="id_partnumber" onchange="MostrarlimpiarCargarDatos()">
                                            <option value="" disabled selected><?php echo $lang['Seleccione el PartNumber']; ?></option>
                                            <?php
                                            include('../../ConexionBD/conexion.php');

                                            $id_proveedor_usuarios = $_SESSION['id_proveedor_usuarios'];

                                            $consultarPartNumbers = mysqli_query($conexion, "SELECT * FROM proveedor_partnumbers
                                            WHERE id_proveedor_partnumber = '$id_proveedor_usuarios' ORDER BY partnumber ASC");

                                            if (mysqli_num_rows($consultarPartNumbers) > 0) {
                                                while ($MostrarPartNumbers = mysqli_fetch_array($consultarPartNumbers)) {
                                            ?>
                                                    <option value="<?php echo $MostrarPartNumbers['partnumber']; ?>"><?php echo $MostrarPartNumbers['partnumber'] ?></option>
                                            <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <div class="col-md-6 mt-5 prop">
                                        <label class="floating-label"><?php echo $lang['Descripcion PartNumber']; ?></label>
                                        <input name="descripcion_partnumber" id="descripcion_partnumber" type="text" class="form-control custom-input" disabled readonly>
                                    </div>

                                    <div class="col-md-6 mt-5 prop">
                                        <label class="floating-label"><?php echo $lang['Diligenciado Por']; ?></label>
                                        <input required name="diligencio_costbreakdown" id="diligencio_costbreakdown" type="text" class="form-control custom-input" placeholder="<?php echo $lang['Ingrese su Nombre']; ?>">
                                    </div>

                                    <div class="col-md-6 mt-5 prop">
                                        <label class="floating-label"><?php echo $lang['Moneda']; ?></label>
                                        <select required class="form-select custom-input" name="moneda_costbreakdown" id="moneda_costbreakdown" onchange="actualizarLabelMoneda()">
                                            <option value="" disabled selected><?php echo $lang['Seleccione el Tipo de Moneda']; ?></option>
                                            <option value="BRL">BRL</option>
                                            <option value="CNY">CNY</option>
                                            <option value="COP">COP</option>
                                            <option value="EUR">EUR</option>
                                            <option value="INR">INR</option>
                                            <option value="USD">USD</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6 mt-5 prop">
                                        <label class="floating-label">Incoterm: *</label>
                                        <select required class="form-select custom-input" name="incoterm_costbreakdown" id="incoterm_costbreakdown" required>
                                            <option value="" disabled selected><?php echo $lang['Seleccione el Incoterm']; ?></option>
                                            <option value="CIF">CIF</option>
                                            <option value="CIP">CIP</option>
                                            <option value="CFR">CFR</option>
                                            <option value="CPT">CPT</option>
                                            <option value="DAP">DAP</option>
                                            <option value="DDP">DDP</option>
                                            <option value="DPU">DPU</option>
                                            <option value="EXW">EXW</option>
                                            <option value="FAS">FAS</option>
                                            <option value="FCA">FCA</option>
                                            <option value="FOB">FOB</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6 mt-5 prop">
                                        <label class="floating-label"><?php echo $lang['Volumen Anual']; ?></label>
                                        <input required name="volumen_anual_costbreakdown" id="volumen_anual_costbreakdown" type="text" oninput="validarInputNumber(this)" class="form-control custom-input" placeholder="<?php echo $lang['Volumen Anual']; ?>">
                                    </div>
                                </div>
                                <a class="cbd" href="#" onclick="changePanel(1)"><?php echo $lang['siguiente'] ?></a>
                            </div>
                        </div>
                        <div class="contenido-seccion" id="materiaPrima">
                            <div id="form2">
                                <div class="row" id="ContenedorMateriaPrima">

                                </div>

                                <hr class="hr mt-5">

                                <div class="row">
                                    <div class="col-md-6 mt-3">
                                        <label id="label10CopPieza">--- / ---</label>
                                        <input readonly name="total_moneda_pieza_materia_prima" id="total_moneda_pieza_materia_prima" type="text" class="form-control custom-input" value="0">
                                    </div>
                                    <div class="col-md-6 mt-3">
                                    </div>
                                </div>
                                <a class="cbd2" href="#" onclick="changePanel(0)"><?php echo $lang['anterior'] ?></a>
                                <a class="cbd" href="#" onclick="changePanel(2)"><?php echo $lang['siguiente'] ?></a>
                            </div>
                        </div>

                        <div class="contenido-seccion" id="procesoProductivo">
                            <div id="form3">
                                <div class="row" id="ContenedorProcesoProductivo">

                                </div>

                                <hr class="hr mt-5">

                                <div class="row">
                                    <div class="col-md-3 text-totalProcess1">
                                        <label><?php echo $lang['CostoFinalMaquina']; ?></label>
                                    </div>
                                    <div class="col-md-5 mt-3">
                                        <label id="label1CopPieza">--- / ---</label>
                                        <input value="0" readonly name="total_moneda_pieza_costo_maquina" id="total_moneda_pieza_costo_maquina" type="text" class="form-control custom-input">
                                    </div>
                                    <div class="col-md-4 mt-3">
                                    </div>

                                    <div class="col-md-3 text-totalProcess2">
                                        <label><?php echo $lang['ManoObraDirecta']; ?></label>
                                    </div>
                                    <div class="col-md-5 mt-5">
                                        <label id="label2CopPieza">--- / ---</label>
                                        <input value="0" readonly name="total_moneda_pieza_mano_obra_directa" id="total_moneda_pieza_mano_obra_directa" type="text" class="form-control custom-input">
                                    </div>
                                    <div class="col-md-4 mt-5">
                                    </div>

                                    <div class="col-md-3 text-totalProcess2">
                                        <label><?php echo $lang['CostoFinalSetup']; ?></label>
                                    </div>
                                    <div class="col-md-5 mt-5">
                                        <label id="label3CopPieza">--- / ---</label>
                                        <input value="0" readonly name="total_moneda_pieza_costo_setup" id="total_moneda_pieza_costo_setup" type="text" class="form-control custom-input">
                                    </div>
                                    <div class="col-md-4 mt-5">
                                    </div>

                                </div>
                                <a class="cbd2" href="#" onclick="changePanel(1)"><?php echo $lang['anterior'] ?></a>
                                <a class="cbd" href="#" onclick="changePanel(3)"><?php echo $lang['siguiente'] ?></a>
                            </div>
                        </div>

                        <div class="contenido-seccion" id="amortizacion">
                            <div id="form4">
                                <div class="row" id="ContenedorAmortizacion">

                                </div>

                                <hr class="hr mt-5">

                                <div class="row">
                                    <div class="col-md-2 text-totalProcess1">
                                        <label><?php echo $lang['Amortizacion']; ?></label>
                                    </div>
                                    <div class="col-md-5 mt-3">
                                        <label id="label4CopPieza">--- / ---</label>
                                        <input value="0" readonly name="total_moneda_pieza_amortizacion" id="total_moneda_pieza_amortizacion" type="text" class="form-control custom-input">
                                    </div>
                                    <div class="col-md-5 mt-3">
                                    </div>
                                </div>
                                <a class="cbd2" href="#" onclick="changePanel(2)"><?php echo $lang['anterior'] ?></a>
                                <a class="cbd" href="#" onclick="changePanel(4)"><?php echo $lang['siguiente'] ?></a>
                            </div>
                        </div>

                        <div class="contenido-seccion" id="costoEmbalaje">
                            <div id="form5">
                                <div class="row">
                                    <p class="subtitles-seccions">
                                        <?php echo $lang['TextSeparadorMiles']; ?>
                                    </p>
                                    <div class="col-md-2 text-totalProcess1">
                                        <label><?php echo $lang['Embalaje']; ?></label>
                                    </div>
                                    <div class="col-md-10 mt-3">
                                        <label id="label5CopPieza">--- / ---</label>
                                        <input required name="moneda_pieza_embalaje" id="moneda_pieza_embalaje" type="text" class="form-control custom-input" onchange="calcularPrecioNetoTotal()" oninput="validarInputNumber(this)" placeholder="--- / ---">
                                    </div>
                                </div>
                                <a class="cbd2" href="#" onclick="changePanel(3)"><?php echo $lang['anterior'] ?></a>
                                <a class="cbd" href="#" onclick="changePanel(5)"><?php echo $lang['siguiente'] ?></a>
                            </div>
                        </div>

                        <div class="contenido-seccion" id="scrap">
                            <div id="form6">
                                <div class="row">
                                    <p class="subtitles-seccions">
                                        <?php echo $lang['TextSeparadorMiles']; ?>
                                    </p>
                                    <div class="col-md-2 text-totalProcess1">
                                        <label>Scrap</label>
                                    </div>
                                    <div class="col-md-4 mt-3">
                                        <label>%</label>
                                        <input required name="porcentaje_scrap" onchange="calcularScrap()" oninput="validarPorcentaje(this)" id="porcentaje_scrap" type="text" class="form-control custom-input" placeholder="%">
                                    </div>
                                    <div class="col-md-6 mt-3">
                                        <label id="label6CopPieza">--- / ---</label>
                                        <input readonly name="moneda_pieza_scrap" id="moneda_pieza_scrap" type="text" class="form-control custom-input">
                                    </div>
                                </div>
                                <a class="cbd2" href="#" onclick="changePanel(4)"><?php echo $lang['anterior'] ?></a>
                                <a class="cbd" href="#" onclick="changePanel(6)"><?php echo $lang['siguiente'] ?></a>
                            </div>
                        </div>

                        <div class="contenido-seccion" id="markup">
                            <div id="form7">
                                <div class="row">
                                    <p class="subtitles-seccions">
                                        <?php echo $lang['TextSeparadorMiles']; ?>
                                    </p>
                                    <div class="col-md-2 text-totalProcess1">
                                        <label><?php echo $lang['Flete'] . ": *" ?></label>
                                    </div>
                                    <div class="col-md-4 mt-3">
                                        <label>%</label>
                                        <input required name="porcentaje_flete" id="porcentaje_flete" type="text" onchange="calcularPrecioNetoTotal()" oninput="validarPorcentaje(this)" class="form-control custom-input" placeholder="%">
                                    </div>
                                    <div class="col-md-6 mt-3">
                                        <label id="label7CopPieza">--- / ---</label>
                                        <input readonly name="moneda_pieza_flete" id="moneda_pieza_flete" type="text" class="form-control custom-input">
                                    </div>

                                    <div class="col-md-2 text-totalProcess1">
                                        <label>SG&A: *</label>
                                    </div>
                                    <div class="col-md-4 mt-3">
                                        <label>%</label>
                                        <input required name="porcentaje_SGA" id="porcentaje_SGA" type="text" onchange="calcularPrecioNetoTotal()" oninput="validarPorcentaje(this)" class="form-control custom-input" placeholder="%">
                                    </div>
                                    <div class="col-md-6 mt-3">
                                        <label id="label8CopPieza">--- / ---</label>
                                        <input readonly name="moneda_pieza_SGA" id="moneda_pieza_SGA" type="text" class="form-control custom-input">
                                    </div>

                                    <div class="col-md-2 text-totalProcess1">
                                        <label><?php echo $lang['MargenBeneficio'] . ": *" ?></label>
                                    </div>
                                    <div class="col-md-4 mt-3">
                                        <label>%</label>
                                        <input required name="porcentaje_margen_beneficio" id="porcentaje_margen_beneficio" onchange="calcularPrecioNetoTotal()" oninput="validarPorcentaje(this)" type="text" class="form-control custom-input" placeholder="%">
                                    </div>
                                    <div class="col-md-6 mt-3">
                                        <label id="label9CopPieza">--- / ---</label>
                                        <input readonly name="moneda_pieza_margen_beneficio" id="moneda_pieza_margen_beneficio" type="text" class="form-control custom-input">
                                    </div>
                                </div>
                                <a class="cbd2" href="#" onclick="changePanel(5)"><?php echo $lang['anterior'] ?></a>
                                <a class="cbd" href="#" onclick="changePanel(7)"><?php echo $lang['siguiente'] ?></a>
                            </div>
                        </div>

                        <div class="contenido-seccion" id="resumen">
                            <div class="row">
                                <div class="col-md-8">
                                    <h3 class="card-title2"><?php echo "Total " . $lang['Materia Prima']; ?></h3>
                                </div>
                                <div class="col-md-1">
                                    <input readonly type="text" class="precioTotal" id="total_moneda_pieza_materia_prima" name="total_moneda_pieza_materia_prima">
                                </div>
                                <div class="col-md-1">
                                    <input type="text" class="precioTotal" id="porcentaje_final_materia_prima" name="porcentaje_final_materia_prima">
                                </div>

                                <div class="col-md-8">
                                    <h3 class="card-title2"><?php echo "Total " . $lang['CostoFinalMaquina']; ?></h3>
                                </div>
                                <div class="col-md-1">
                                    <input readonly type="text" class="precioTotal" id="total_moneda_pieza_costo_maquina" name="total_moneda_pieza_costo_maquina">
                                </div>
                                <div class="col-md-1">
                                    <input readonly type="text" class="precioTotal" id="porcentaje_final_moneda_pieza_costo_maquina" name="porcentaje_final_moneda_pieza_costo_maquina">
                                </div>

                                <div class="col-md-8">
                                    <h3 class="card-title2"><?php echo "Total " . $lang['ManoObraDirecta']; ?></h3>
                                </div>
                                <div class="col-md-1">
                                    <input readonly type="text" class="precioTotal" id="total_moneda_pieza_mano_obra_directa" name="total_moneda_pieza_mano_obra_directa">
                                </div>
                                <div class="col-md-1">
                                    <input readonly type="text" class="precioTotal" id="porcentaje_final_moneda_pieza_mano_obra_directa" name="porcentaje_final_moneda_pieza_mano_obra_directa">
                                </div>

                                <div class="col-md-8">
                                    <h3 class="card-title2"><?php echo "Total " . $lang['CostoFinalSetup']; ?></h3>
                                </div>
                                <div class="col-md-1">
                                    <input readonly type="text" class="precioTotal" id="total_moneda_pieza_costo_setup" name="total_moneda_pieza_costo_setup">
                                </div>
                                <div class="col-md-1">
                                    <input readonly type="text" class="precioTotal" id="porcentaje_final_moneda_pieza_costo_setup" name="porcentaje_final_moneda_pieza_costo_setup">
                                </div>

                                <div class="col-md-8">
                                    <h3 class="card-title2"><?php echo "Total " . $lang['Amortizacion']; ?></h3>
                                </div>
                                <div class="col-md-1">
                                    <input readonly type="text" class="precioTotal" id="total_moneda_pieza_amortizacion" name="total_moneda_pieza_amortizacion">
                                </div>
                                <div class="col-md-1">
                                    <input readonly type="text" class="precioTotal" id="porcentaje_final_moneda_pieza_amortizacion" name="porcentaje_final_moneda_pieza_amortizacion">
                                </div>

                                <div class="col-md-8">
                                    <h3 class="card-title2"><?php echo "Total " . $lang['Embalaje']; ?></h3>
                                </div>
                                <div class="col-md-1">
                                    <input readonly type="text" class="precioTotal" id="moneda_pieza_embalaje" name="moneda_pieza_embalaje">
                                </div>
                                <div class="col-md-1">
                                    <input readonly type="text" class="precioTotal" id="porcentaje_total_embalaje" name="porcentaje_total_embalaje">
                                </div>

                                <div class="col-md-8">
                                    <h3 class="card-title2">Total Scrap</h3>
                                </div>
                                <div class="col-md-1">
                                    <input readonly type="text" class="precioTotal" id="moneda_pieza_scrap" name="moneda_pieza_scrap">
                                </div>
                                <div class="col-md-1">
                                    <input readonly type="text" class="precioTotal" id="porcentaje_total_scrap" name="porcentaje_total_scrap">
                                </div>

                                <div class="col-md-8">
                                    <h3 class="card-title2"><?php echo "Total " . $lang['Flete']; ?></h3>
                                </div>
                                <div class="col-md-1">
                                    <input readonly type="text" class="precioTotal" id="moneda_pieza_flete" name="moneda_pieza_flete">
                                </div>
                                <div class="col-md-1">
                                    <input readonly type="text" class="precioTotal" id="porcentaje_final_flete" name="porcentaje_final_flete">
                                </div>

                                <div class="col-md-8">
                                    <h3 class="card-title2">Total SG&A</h3>
                                </div>
                                <div class="col-md-1">
                                    <input readonly type="text" class="precioTotal" id="moneda_pieza_SGA" name="moneda_pieza_SGA">
                                </div>
                                <div class="col-md-1">
                                    <input readonly type="text" class="precioTotal" id="porcentaje_final_SGA" name="porcentaje_final_SGA">
                                </div>

                                <div class="col-md-8">
                                    <h3 class="card-title2"><?php echo "Total " . $lang['MargenBeneficio']; ?></h3>
                                </div>
                                <div class="col-md-1">
                                    <input readonly type="text" class="precioTotal" id="moneda_pieza_margen_beneficio" name="moneda_pieza_margen_beneficio">
                                </div>
                                <div class="col-md-1">
                                    <input readonly type="text" class="precioTotal" id="porcentaje_final_margen_beneficio" name="porcentaje_final_margen_beneficio">
                                </div>


                                <div class="col-md-8">
                                    <h3 class="card-title2"><?php echo $lang['PrecioNetoTotal']; ?></h3>
                                </div>
                                <div class="col-md-1">
                                    <input readonly type="text" class="precioTotal" id="precio_neto_total" name="precio_neto_total">
                                </div>
                                <div class="col-md-1">
                                    <input readonly type="text" class="precioTotal" id="porcentajeFinal" name="porcentajeFinal">
                                </div>

                            </div>
                            <a class="cbd2" href="#" onclick="changePanel(6)"><?php echo $lang['anterior'] ?></a>
                            <a class="cbd" href="#" onclick="finalizarcbd()"><?php echo $lang['finalizar'] ?></a>
                        </div>
                    </form>
                    <br><br><br>
                </div>

                <!--  -->

                <div class="fixed-button-container">
                    <button disabled type="button" class="btn btn-primary addMateriaPrima" onclick="addMateriaPrima()"><?php echo $lang['Agregar Materia Prima']; ?></button>
                </div>
                <div class="fixed-button-container">
                    <button disabled type="button" class="btn btn-success addProcesoProductivo" onclick="addProcesoProductivo()"><?php echo $lang['Agregar Proceso Productivo']; ?></button>
                </div>

                <div class="fixed-button-container">
                    <button disabled type="button" class="addAmortizacion" onclick="addAmortizacion()"><?php echo $lang['Agregar Amortizacion']; ?></button>
                </div>

                <br><br><br>

            <?php

            } else {
<<<<<<< HEAD
    ?>
        <div class="form-container">
=======
            ?>
                <div class="form-container">
>>>>>>> 8fe25a02a378af3db1c5f09c74bddd125a144800
                    <br><br>
                    <h2 class="title">Cost BreakDown</h2>
                    <br><br>
                    <form action="../../Actions/Supplier/registrarCostBreakDown2.php" method="POST">
                        <div class="row mt-5">
                            <!-- Selector de Part Number -->
                            <div class="col-md-6">
                                <p>Part Number: *</p>
                                <select class="form-select" name="id_partnumber" onchange="Mostrarcb2()">
                                    <option value="" disabled selected><?php echo $lang['Seleccione el PartNumber']; ?></option>
                                    <?php
                                    include('../../ConexionBD/conexion.php');

                                    $id_proveedor_usuarios = $_SESSION['id_proveedor_usuarios'];
                                    $consultarPartNumbers = mysqli_query($conexion, "SELECT * FROM proveedor_partnumbers
                            WHERE id_proveedor_partnumber = '$id_proveedor_usuarios' ORDER BY partnumber ASC");

                                    if (mysqli_num_rows($consultarPartNumbers) > 0) {
                                        while ($MostrarPartNumbers = mysqli_fetch_array($consultarPartNumbers)) {
                                    ?>
                                            <option value="<?php echo $MostrarPartNumbers['partnumber']; ?>"><?php echo $MostrarPartNumbers['partnumber'] ?></option>
                                    <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="col-md-6 hidden">
                                <label class="floating-label"><?php echo $lang['Descripcion PartNumber']; ?></label>
                                <input id="desc_partnumber" type="text" class="form-control custom-input" disabled readonly>
                            </div>

<<<<<<< HEAD
                            <div class="col-md-6 mt-5 hidden">
                                        <label class="floating-label"><?php echo $lang['Moneda']; ?></label>
                                        <select required class="form-select custom-input" name="moneda_costbreakdown" id="moneda_costbreakdown" onchange="calcularTotalCbdSimplified()">
                                            <option value="" disabled selected><?php echo $lang['Seleccione el Tipo de Moneda']; ?></option>
                                            <option value="BRL">BRL</option>
                                            <option value="CNY">CNY</option>
                                            <option value="COP">COP</option>
                                            <option value="EUR">EUR</option>
                                            <option value="INR">INR</option>
                                            <option value="USD">USD</option>
                                        </select>
                                    </div>

                            <div class="col-md-6 mt-5 hidden">
                                <label class="floating-label"><?php echo $lang['precio simplified']; ?></label>
                                <input type="text" name="precio_costbreakdown_simplified" id="precio_costbreakdown_simplified" class="form-control custom-input" oninput="validarInputNumber(this)" onchange="calcularTotalCbdSimplified()" required>
                            </div>

                            <div id="ContenedorCostbreakDownSimplified"></div>

                            <div class="col-md-6 mt-5 hidden">
=======
                            <div id="ContenedorCostbreakDownSimplified"></div>

                            <div class="col-md-6 mt-5 hidden">
>>>>>>> 8fe25a02a378af3db1c5f09c74bddd125a144800
                                <label class="floating-label">Total %</label>
                                <input id="total_final_cbd" type="text" class="form-control custom-input" disabled readonly>
                            </div>
                            <div class="col-md-6 hidden">
                                <button type="submit" disabled class="btn btn-success" id="cargarDatosCbd2">
                                    <i class="material-icons">cloud_upload</i> <?php echo $lang['CargarDatos']; ?>
                                </button>
                            </div>
                        </div>
                    </form>
                    <br><br><br>
                </div>

<<<<<<< HEAD
        <div class="fixed-button-container">
            <button disabled type="button" class="btn btn-primary addSeccion" onclick="addSeccion()"><?php echo $lang['AgregarSeccion']; ?></button>
        </div>

    <?php
=======
                <div class="fixed-button-container">
                    <button disabled type="button" class="btn btn-primary addSeccion" onclick="addSeccion()"><?php echo $lang['AgregarSeccion']; ?></button>
                </div>

            <?php
>>>>>>> 8fe25a02a378af3db1c5f09c74bddd125a144800
            };
            ?>

        </div>
    </div>

</body>

<script>
    var contadorFormulariosMateriaPrima = 0;
    var contadorFormulariosProcesoProductivo = 0;
    var contadorFormulariosAmortizacion = 0;
    var contadorFormulariosCostBreakDownSimplified = 0;

    function formatearValor(valor) {
        
        if (typeof valor !== 'string' || !valor.trim() || !/^(\d+(\.\d*)?|\.\d+)$/.test(valor)) {
            return valor;
        }

        var numero = parseFloat(valor);
        if (isNaN(numero)) {
            return 'Valor no válido';
        }

        var numeroFormateado = numero.toFixed(2);

        var partes = numeroFormateado.split('.');
        var parteEntera = partes[0];
        var parteDecimal = partes[1];

        var parteEnteraFormateada = parteEntera.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

        return parteEnteraFormateada + ',' + parteDecimal;
    }

    function MostrarlimpiarCargarDatos() {

        contadorFormulariosMateriaPrima = 0;
        contadorFormulariosProcesoProductivo = 0;
        contadorFormulariosAmortizacion = 0;

        $('.prop').removeClass('prop');
        $('#ContenedorProcesoProductivo').empty();
        $('#ContenedorAmortizacion').empty();

        var tipoCbd = 1;
        var partnumberSeleccionado = $("select[name='id_partnumber']").val();

        $.ajax({
            type: 'POST',
            url: '../../Actions/Supplier/consultarCostBreakDown.php',
            data: {
                partnumber: partnumberSeleccionado,
                tipoCbd: tipoCbd
            },
            dataType: 'json',
            success: function(response) {

                if (response.success) {

                    var data = response.data;
                    var rowCount;

                    if (Array.isArray(data)) {
                        rowCount = data.length;
                    } else {
                        rowCount = Object.keys(data).length;
                    }

                    $('#descripcion_partnumber').val(response.data[0].descripcion_partnumber);

                    if (response.data[0].moneda_costbreakdown !== null) {

                        $('#diligencio_costbreakdown').val(response.data[0].diligencio_costbreakdown);
                        $('#moneda_costbreakdown').val(response.data[0].moneda_costbreakdown);
                        actualizarLabelMoneda();
                        $('#incoterm_costbreakdown').val(response.data[0].incoterm_costbreakdown);
                        $('#volumen_anual_costbreakdown').val(formatearValor(response.data[0].volumen_anual_costbreakdown));
                    }
                } else {
                    $('#diligencio_costbreakdown').val('');
                    $('#moneda_costbreakdown').val('');
                    $('#incoterm_costbreakdown').val('');
                    $('#volumen_anual_costbreakdown').val('');
                    $('#descripcion_partnumber').val(response.data[0].descripcion_partnumber);
                }
            },
        });
    }

    function addMateriaPrima() {

        $('#nombre_materia_prima' + contadorFormulariosMateriaPrima).attr('readonly', true);
        $('#moneda_unidad_materia_prima' + contadorFormulariosMateriaPrima).attr('readonly', true);
        $('#unidad_materia_prima' + contadorFormulariosMateriaPrima).attr('readonly', true);
        $('#unidad_pieza_materia_prima' + contadorFormulariosMateriaPrima).attr('readonly', true);

        contadorFormulariosMateriaPrima++;

        var nuevoFormularioMateriaPrima = `
        
            <div class="row mt-3">
            <hr class="mt-5">
                <div class="col-md-6 mt-5">
                    <label for="nombre_materia_prima" class="floating-label"><?php echo $lang['Nombre']; ?></label>
                    <input required name="nombre_materia_prima[]" type="text" id="nombre_materia_prima${contadorFormulariosMateriaPrima}" onchange="materiaPrima()" class="form-control custom-input" placeholder="<?php echo $lang['Nombre de la materia prima']; ?>">
                </div>
                <div class="col-md-6 mt-5">
                    <label for="moneda_unidad_materia_prima" class="floating-label" id="labelMonedaUnid${contadorFormulariosMateriaPrima}">--- / ---</label>
                    <div class="input-group">
                        <input required name="moneda_unidad_materia_prima[]" id="moneda_unidad_materia_prima${contadorFormulariosMateriaPrima}" onchange="materiaPrima()" oninput="validarInputNumber(this)" type="text" class="form-control custom-input" placeholder="COP / UNID">
                    </div>
                </div>
                <div class="col-md-6 mt-5">
                    <label for="unidad_materia_prima" class="floating-label"><?php echo $lang['Unidad']; ?></label>
                    <div class="input-group">
                    <select required name="unidad_materia_prima[]" id="unidad_materia_prima${contadorFormulariosMateriaPrima}" class="form-select custom-input" onchange="materiaPrima()">
                            <option value"" selected disabled><?php echo $lang['Ingrese la Unidad']; ?></option>
                            <option value"Centigramo">Centigramo</option>
                            <option value"Decagramo">Decagramo</option>
                            <option value"Decigramo">Decigramo</option>
                            <option value"Gramo">Gramo</option>
                            <option value"Hectogramo">Hectogramo</option>
                            <option value"Kilogramo">Kilogramo</option>
                            <option value"Miligramo">Miligramo</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6 mt-5">
                    <label for="unidad_pieza_materia_prima" class="floating-label"><?php echo $lang['Unid_Pieza']; ?></label>
                    <div class="input-group">
                        <input required name="unidad_pieza_materia_prima[]" id="unidad_pieza_materia_prima${contadorFormulariosMateriaPrima}" onchange="materiaPrima()" oninput="validarInputNumber(this)" type="text" class="form-control custom-input" placeholder="<?php echo $lang['Ingrese Unid_Pieza']; ?>">
                    </div>
                </div>

                <div class="col-md-5 mt-5">
                    <label for="moneda_pieza_materia_prima" class="floating-label" id="label_moneda_pieza_materia_prima${contadorFormulariosMateriaPrima}">--- / ---</label>
                    <div class="input-group">
                        <input readonly name="moneda_pieza_materia_prima[]" id="moneda_pieza_materia_prima${contadorFormulariosMateriaPrima}" onchange="materiaPrima()" type="text" class="form-control custom-input">
                    </div>
                </div>

                <div class="col-md-1 mt-5">
                    <button type="button" class="btn btn-danger removemateriaprima" onclick="removemateriaprima(this)"><i class="material-icons">delete</i></button>
                </div>
            </div>
            <br>
        `;

        $('#ContenedorMateriaPrima').append(nuevoFormularioMateriaPrima);
        var addMateriaPrimaButton = document.querySelector('.addMateriaPrima');
        addMateriaPrimaButton.setAttribute('disabled', 'disabled');
        actualizarLabelMoneda();
    }

    function addProcesoProductivo() {

        $('#etapa_proceso_productivo' + contadorFormulariosProcesoProductivo).attr('readonly', true);
        $('#nombre_maquina_proceso_productivo' + contadorFormulariosProcesoProductivo).attr('readonly', true);
        $('#cantidad_cavidades_proceso_productivo' + contadorFormulariosProcesoProductivo).attr('readonly', true);
        $('#tiempo_ciclo_proceso_productivo' + contadorFormulariosProcesoProductivo).attr('readonly', true);
        $('#eficiencia_proceso_productivo' + contadorFormulariosProcesoProductivo).attr('readonly', true);
        $('#costo_maquina_hora_proceso_productivo' + contadorFormulariosProcesoProductivo).attr('readonly', true);
        $('#cantidad_mano_obra_directa_proceso_productivo' + contadorFormulariosProcesoProductivo).attr('readonly', true);
        $('#mano_obra_directa_proceso_productivo' + contadorFormulariosProcesoProductivo).attr('readonly', true);
        $('#tiempo_setup_proceso_productivo' + contadorFormulariosProcesoProductivo).attr('readonly', true);
        $('#costo_setup_hora_proceso_productivo' + contadorFormulariosProcesoProductivo).attr('readonly', true);
        $('#cantidad_mano_obra_directa_proceso_productivo' + contadorFormulariosProcesoProductivo).attr('readonly', true);
        $('#lote_setup_proceso_productivo' + contadorFormulariosProcesoProductivo).attr('readonly', true);

        contadorFormulariosProcesoProductivo++;

        var nuevoFormularioProcesoProductivo = `
        <div class="row mt-3">
            <hr class="mt-5">
                <div class="col-md-6 mt-5">
                    <label for="etapa_proceso_productivo" class="floating-label"><?php echo $lang['Etapa de Proceso']; ?></label>
                    <input required name="etapa_proceso_productivo[]" type="text" id="etapa_proceso_productivo${contadorFormulariosProcesoProductivo}" onchange="procesoProductivo()" class="form-control custom-input" placeholder="<?php echo $lang['Ingrese la Etapa del Proceso']; ?>">
                </div>
                <div class="col-md-6 mt-5">
                    <label for="nombre_maquina_proceso_productivo" class="floating-label"><?php echo $lang['Nombre de la Maquina']; ?></label>
                    <input required name="nombre_maquina_proceso_productivo[]" type="text" id="nombre_maquina_proceso_productivo${contadorFormulariosProcesoProductivo}" onchange="procesoProductivo()" class="form-control custom-input" placeholder="<?php echo $lang['Ingrese el Nombre de la Maquina']; ?>">
                </div>
                <div class="col-md-6 mt-5">
                    <label for="cantidad_cavidades_proceso_productivo" class="floating-label"><?php echo $lang['Cantidad de Cavidades']; ?></label>
                    <div class="input-group">
                        <input required name="cantidad_cavidades_proceso_productivo[]" id="cantidad_cavidades_proceso_productivo${contadorFormulariosProcesoProductivo}" onchange="procesoProductivo()" oninput="validarInputNumber(this)" type="text" class="form-control custom-input" placeholder="<?php echo $lang['Ingrese la Cantidad de Cavidades']; ?>">
                    </div>
                </div>
                <div class="col-md-6 mt-5">
                    <label for="tiempo_ciclo_proceso_productivo" class="floating-label"><?php echo $lang['Tiempo del Ciclo']; ?></label>
                    <div class="input-group">
                        <input required name="tiempo_ciclo_proceso_productivo[]" id="tiempo_ciclo_proceso_productivo${contadorFormulariosProcesoProductivo}" onchange="procesoProductivo()" oninput="validarInputNumber(this)" type="text" class="form-control custom-input" placeholder="<?php echo $lang['Ingrese el Tiempo del Ciclo']; ?>">
                    </div>
                </div>
                <div class="col-md-6 mt-5">
                    <label for="eficiencia_proceso_productivo" class="floating-label"><?php echo $lang['Eficiencia']; ?></label>
                    <div class="input-group">
                        <input required name="eficiencia_proceso_productivo[]" id="eficiencia_proceso_productivo${contadorFormulariosProcesoProductivo}" onchange="procesoProductivo()" oninput="validarPorcentaje(this)" type="text" class="form-control custom-input" placeholder="<?php echo $lang['Ingrese la Eficiencia']; ?>">
                    </div>
                </div>

                <div class="col-md-6 mt-5">
                    <label for="costo_maquina_hora_proceso_productivo" class="floating-label" id="labelCostoMaquinaHora${contadorFormulariosProcesoProductivo}">---</label>
                    <div class="input-group">
                        <input required name="costo_maquina_hora_proceso_productivo[]" id="costo_maquina_hora_proceso_productivo${contadorFormulariosProcesoProductivo}" onchange="procesoProductivo()" oninput="validarInputNumber(this)" type="text" class="form-control custom-input" placeholder="---">
                    </div>
                </div>

                <div class="col-md-6 mt-5">
                    <label for="cantidad_mano_obra_directa_proceso_productivo" class="floating-label"><?php echo $lang['CantidadManoObraDir']; ?></label>
                    <div class="input-group">
                        <input required name="cantidad_mano_obra_directa_proceso_productivo[]" id="cantidad_mano_obra_directa_proceso_productivo${contadorFormulariosProcesoProductivo}" onchange="procesoProductivo()" oninput="validarInputNumber(this)" type="text" class="form-control custom-input" placeholder="<?php echo $lang['IngreseCantidadManoObraDir']; ?>">
                    </div>
                </div>

                <div class="col-md-6 mt-5">
                    <label for="mano_obra_directa_proceso_productivo" class="floating-label" id="labelManoObraDirecta${contadorFormulariosProcesoProductivo}">---</label>
                    <div class="input-group">
                        <input required name="mano_obra_directa_proceso_productivo[]" id="mano_obra_directa_proceso_productivo${contadorFormulariosProcesoProductivo}" onchange="procesoProductivo()" oninput="validarInputNumber(this)" type="text" class="form-control custom-input" placeholder="---">
                    </div>
                </div>

                <div class="col-md-6 mt-5">
                    <label for="tiempo_setup_proceso_productivo" class="floating-label"><?php echo $lang['TiempoSetUp']; ?></label>
                    <div class="input-group">
                        <input required name="tiempo_setup_proceso_productivo[]" id="tiempo_setup_proceso_productivo${contadorFormulariosProcesoProductivo}" onchange="procesoProductivo()" oninput="validarInputNumber(this)" type="text" class="form-control custom-input" placeholder="<?php echo $lang['IngreseTiempoSetUp']; ?>">
                    </div>
                </div>

                <div class="col-md-6 mt-5">
                    <label for="costo_setup_hora_proceso_productivo" class="floating-label" id="labelCostoSetUp${contadorFormulariosProcesoProductivo}">---</label>
                    <div class="input-group">
                        <input required name="costo_setup_hora_proceso_productivo[]" id="costo_setup_hora_proceso_productivo${contadorFormulariosProcesoProductivo}" onchange="procesoProductivo()" oninput="validarInputNumber(this)" type="text" class="form-control custom-input" placeholder="---">
                    </div>
                </div>

                <div class="col-md-6 mt-5">
                    <label for="lote_setup_proceso_productivo" class="floating-label"><?php echo $lang['LoteSetUp']; ?></label>
                    <div class="input-group">
                        <input required name="lote_setup_proceso_productivo[]" id="lote_setup_proceso_productivo${contadorFormulariosProcesoProductivo}" onchange="procesoProductivo()" oninput="validarInputNumber(this)" type="text" class="form-control custom-input" placeholder="<?php echo $lang['IngreseLoteSetUp']; ?>">
                    </div>
                </div>

                <div class="col-md-6 mt-5">
                    <label for="costo_final_maquina_proceso_productivo" class="floating-label" id="labelCostoFinalMaquina${contadorFormulariosProcesoProductivo}">---</label>
                    <div class="input-group">
                        <input readonly name="costo_final_maquina_proceso_productivo[]" id="costo_final_maquina_proceso_productivo${contadorFormulariosProcesoProductivo}" onchange="totalProcesoProductivo()" oninput="validarInputNumber(this)" type="text" class="form-control custom-input">
                    </div>
                </div>

                <div class="col-md-6 mt-5">
                    <label for="mano_obra_directa_final_proceso_productivo" class="floating-label" id="labelManoObraDirectaFinal${contadorFormulariosProcesoProductivo}">---</label>
                    <div class="input-group">
                        <input readonly name="mano_obra_directa_final_proceso_productivo[]" id="mano_obra_directa_final_proceso_productivo${contadorFormulariosProcesoProductivo}" onchange="procesoProductivo()" oninput="validarInputNumber(this)" type="text" class="form-control custom-input">
                    </div>
                </div>

                <div class="col-md-6 mt-5">
                    <label for="costo_final_setup_hora_proceso_productivo" class="floating-label" id="labelCostoFinalSetup${contadorFormulariosProcesoProductivo}">---</label>
                    <div class="input-group">
                        <input readonly name="costo_final_setup_hora_proceso_productivo[]" id="costo_final_setup_hora_proceso_productivo${contadorFormulariosProcesoProductivo}" onchange="procesoProductivo()" oninput="validarInputNumber(this)" type="text" class="form-control custom-input">
                    </div>
                </div>

                <div class="col-md-5 mt-5">
                    <label for="maquina_mano_obra_directa_setup_proceso_productivo" class="floating-label" id="labelMaquinaManoObraDirectaSetup${contadorFormulariosProcesoProductivo}">---</label>
                    <div class="input-group">
                        <input readonly name="maquina_mano_obra_directa_setup_proceso_productivo[]" id="maquina_mano_obra_directa_setup_proceso_productivo${contadorFormulariosProcesoProductivo}" onchange="procesoProductivo()" oninput="validarInputNumber(this)" type="text" class="form-control custom-input">
                    </div>
                </div>
                <div class="col-md-1 mt-5">
                    <button type="button" class="btn btn-danger removeprocesoproductivo" onclick="removeprocesoproductivo(this)"><i class="material-icons">delete</i></button>
                </div>
            </div>
        `;

        $('#ContenedorProcesoProductivo').append(nuevoFormularioProcesoProductivo);
        var addProcesoProductivoButton = document.querySelector('.addProcesoProductivo');
        addProcesoProductivoButton.setAttribute('disabled', 'disabled');
        actualizarLabelMoneda();
    }

    function calcularTotalAmortizacion(delete_with_contadorFormulariosAmortizacion) {

        var total_moneda_pieza_amortizacion = 0;
        var n = contadorFormulariosAmortizacion;

        for (var i = 0; i <= n; i++) {
            var moneda_pieza_amrtz = document.getElementById('moneda_pieza_amortizacion' + i);

            if (moneda_pieza_amrtz) {
                var moneda_pieza_amortizacion = parseFloat(moneda_pieza_amrtz.value.replace(/\./g, '').replace(',', '.'));
                total_moneda_pieza_amortizacion += isNaN(moneda_pieza_amortizacion) ? 0 : moneda_pieza_amortizacion;
            }
        }

        if (delete_with_contadorFormulariosAmortizacion !== '') {
            var moneda_pieza_amortizacion = parseFloat(document.getElementById('moneda_pieza_amortizacion' + delete_with_contadorFormulariosAmortizacion).value.replace(/\./g, '').replace(',', '.'));
            if (!isNaN(moneda_pieza_amortizacion)) {
                total_moneda_pieza_amortizacion -= moneda_pieza_amortizacion;
            }
        }

        if (!isNaN(total_moneda_pieza_amortizacion)) {
            var total_moneda_pieza_amortizacion_formateada = total_moneda_pieza_amortizacion.toLocaleString('es-ES', {
                minimumFractionDigits: 3,
                maximumFractionDigits: 3
            });
            $('#total_moneda_pieza_amortizacion').val(total_moneda_pieza_amortizacion_formateada);
        } else {
            $('#total_moneda_pieza_amortizacion').val('');
        }

        calcularPrecioNetoTotal();
    }

    function calcularTotalFinalCostoMaquina(delete_with_contadorFormulariosProcesoProductivo) {

        var total_moneda_pieza_costo_maquina = 0;
        var total_moneda_pieza_mano_obra_directa = 0;
        var total_moneda_pieza_costo_setup = 0;
        var n = contadorFormulariosProcesoProductivo;

        for (var i = 0; i <= n; i++) {

            var costFinalMaquina = document.getElementById('costo_final_maquina_proceso_productivo' + i);
            var totManoObraDir = document.getElementById('mano_obra_directa_final_proceso_productivo' + i);
            var costSetUp = document.getElementById('costo_final_setup_hora_proceso_productivo' + i);

            if (costFinalMaquina) {
                var costo_final_maquina_proceso_productivo = parseFloat(costFinalMaquina.value.replace(/\./g, '').replace(',', '.'));
                total_moneda_pieza_costo_maquina += isNaN(costo_final_maquina_proceso_productivo) ? 0 : costo_final_maquina_proceso_productivo;
            }
            if (totManoObraDir) {
                var mano_obra_directa_final_proceso_productivo = parseFloat(totManoObraDir.value.replace(/\./g, '').replace(',', '.'));
                total_moneda_pieza_mano_obra_directa += isNaN(mano_obra_directa_final_proceso_productivo) ? 0 : mano_obra_directa_final_proceso_productivo;
            }
            if (costSetUp) {
                var costo_final_setup_hora_proceso_productivo = parseFloat(costSetUp.value.replace(/\./g, '').replace(',', '.'));
                total_moneda_pieza_costo_setup += isNaN(costo_final_setup_hora_proceso_productivo) ? 0 : costo_final_setup_hora_proceso_productivo;
            }
        }

        if (delete_with_contadorFormulariosProcesoProductivo !== '') {

            var costo_final_maquina_proceso_productivo = parseFloat(document.getElementById('costo_final_maquina_proceso_productivo' + delete_with_contadorFormulariosProcesoProductivo).value.replace(/\./g, '').replace(',', '.'));
            if (!isNaN(costo_final_maquina_proceso_productivo)) {
                total_moneda_pieza_costo_maquina -= costo_final_maquina_proceso_productivo;
            }

            var mano_obra_directa_final_proceso_productivo = parseFloat(document.getElementById('mano_obra_directa_final_proceso_productivo' + delete_with_contadorFormulariosProcesoProductivo).value.replace(/\./g, '').replace(',', '.'));
            if (!isNaN(mano_obra_directa_final_proceso_productivo)) {
                total_moneda_pieza_mano_obra_directa -= mano_obra_directa_final_proceso_productivo;
            }

            var costo_final_setup_hora_proceso_productivo = parseFloat(document.getElementById('costo_final_setup_hora_proceso_productivo' + delete_with_contadorFormulariosProcesoProductivo).value.replace(/\./g, '').replace(',', '.'));
            if (!isNaN(costo_final_setup_hora_proceso_productivo)) {
                total_moneda_pieza_costo_setup -= costo_final_setup_hora_proceso_productivo;
            }
        }

        if (!isNaN(total_moneda_pieza_costo_maquina)) {
            var total_moneda_pieza_costo_maquina_formateada = total_moneda_pieza_costo_maquina.toLocaleString('es-ES', {
                minimumFractionDigits: 3,
                maximumFractionDigits: 3
            });
            $('#total_moneda_pieza_costo_maquina').val(total_moneda_pieza_costo_maquina_formateada);
        } else {
            $('#total_moneda_pieza_costo_maquina').val('');
        }

        if (!isNaN(total_moneda_pieza_mano_obra_directa)) {
            var total_moneda_pieza_mano_obra_directa_formateada = total_moneda_pieza_mano_obra_directa.toLocaleString('es-ES', {
                minimumFractionDigits: 3,
                maximumFractionDigits: 3
            });
            $('#total_moneda_pieza_mano_obra_directa').val(total_moneda_pieza_mano_obra_directa_formateada);
        } else {
            $('#total_moneda_pieza_mano_obra_directa').val('');
        }

        if (!isNaN(total_moneda_pieza_costo_setup)) {
            var total_moneda_pieza_costo_setup_formateada = total_moneda_pieza_costo_setup.toLocaleString('es-ES', {
                minimumFractionDigits: 3,
                maximumFractionDigits: 3
            });
            $('#total_moneda_pieza_costo_setup').val(total_moneda_pieza_costo_setup_formateada);
        } else {
            $('#total_moneda_pieza_costo_setup').val('');
        }

        calcularPrecioNetoTotal();
    }

    function materiaPrima() {

        var moneda_unidad_materia_prima = parseFloat($('#moneda_unidad_materia_prima' + contadorFormulariosMateriaPrima).val().replace(/\./g, '').replace(',', '.'));
        var unidad_pieza_materia_prima = parseFloat($('#unidad_pieza_materia_prima' + contadorFormulariosMateriaPrima).val().replace(/\./g, '').replace(',', '.'));
        var nombre_materia_prima = $('#nombre_materia_prima' + contadorFormulariosMateriaPrima).val();
        var unidad_materia_prima = $('#unidad_materia_prima' + contadorFormulariosMateriaPrima).val();

        if (nombre_materia_prima !== '' && unidad_materia_prima !== null && !isNaN(moneda_unidad_materia_prima) && !isNaN(unidad_pieza_materia_prima)) {
            var moneda_pieza = moneda_unidad_materia_prima * unidad_pieza_materia_prima;
            var moneda_pieza_formateada = moneda_pieza.toLocaleString('es-ES', {
                minimumFractionDigits: 3,
                maximumFractionDigits: 3
            });
            $('#moneda_pieza_materia_prima' + contadorFormulariosMateriaPrima).val(moneda_pieza_formateada);
            var delete_with_contadorFormulariosMateriaPrima = '';
            calcularTotalMateriaPrima(delete_with_contadorFormulariosMateriaPrima);
        }

        if (isNaN(moneda_unidad_materia_prima) || isNaN(unidad_pieza_materia_prima)) {
            $('#moneda_pieza_materia_prima' + contadorFormulariosMateriaPrima).val(0);
            calcularTotalMateriaPrima()
        }

        var addMateriaPrimaButton = document.querySelector('.addMateriaPrima');

        if (!isNaN(moneda_unidad_materia_prima) && !isNaN(unidad_pieza_materia_prima) && nombre_materia_prima !== '' && unidad_materia_prima !== null) {
            addMateriaPrimaButton.removeAttribute('disabled');
        } else {
            addMateriaPrimaButton.setAttribute('disabled', 'disabled');
        }
    };

    function calcularTotalMateriaPrima(delete_with_contadorFormulariosMateriaPrima) {

        var totalMateriaPrima = 0;
        var n = contadorFormulariosMateriaPrima;

        for (var i = 0; i <= n; i++) {
            var moneda_pieza_materia_prima = parseFloat($('#moneda_pieza_materia_prima' + i).val().replace(/\./g, '').replace(',', '.'));

            if (!isNaN(moneda_pieza_materia_prima)) {
                totalMateriaPrima += moneda_pieza_materia_prima;
            }
        }

        if (delete_with_contadorFormulariosMateriaPrima !== '' && delete_with_contadorFormulariosMateriaPrima !== undefined) {
            var moneda_pieza_materia_prima = parseFloat($('#moneda_pieza_materia_prima' + delete_with_contadorFormulariosMateriaPrima).val().replace(/\./g, '').replace(',', '.'));
            totalMateriaPrima -= isNaN(moneda_pieza_materia_prima) ? 0 : moneda_pieza_materia_prima;
        }

        if (!isNaN(totalMateriaPrima)) {
            var totalMateriaPrima_formateada = totalMateriaPrima.toLocaleString('es-ES', {
                minimumFractionDigits: 3,
                maximumFractionDigits: 3
            });
            $('#total_moneda_pieza_materia_prima').val(totalMateriaPrima_formateada);
        } else {
            $('#total_moneda_pieza_materia_prima').val('');
        }


        calcularPrecioNetoTotal();
    }

    function procesoProductivo() {

        var costo_maquina_hora_proceso_productivo = parseFloat($('#costo_maquina_hora_proceso_productivo' + contadorFormulariosProcesoProductivo).val().replace(/\./g, '').replace(',', '.'));
        var tiempo_ciclo_proceso_productivo = parseFloat($('#tiempo_ciclo_proceso_productivo' + contadorFormulariosProcesoProductivo).val().replace(/\./g, '').replace(',', '.'));
        var cantidad_cavidades_proceso_productivo = parseFloat($('#cantidad_cavidades_proceso_productivo' + contadorFormulariosProcesoProductivo).val().replace(/\./g, '').replace(',', '.'));
        var eficiencia_proceso_productivo = parseFloat($('#eficiencia_proceso_productivo' + contadorFormulariosProcesoProductivo).val().replace(/\./g, '').replace(',', '.'));
        var cantidad_mano_obra_directa_proceso_productivo = parseFloat($('#cantidad_mano_obra_directa_proceso_productivo' + contadorFormulariosProcesoProductivo).val().replace(/\./g, '').replace(',', '.'));
        var mano_obra_directa_proceso_productivo = parseFloat($('#mano_obra_directa_proceso_productivo' + contadorFormulariosProcesoProductivo).val().replace(/\./g, '').replace(',', '.'));
        var tiempo_setup_proceso_productivo = parseFloat($('#tiempo_setup_proceso_productivo' + contadorFormulariosProcesoProductivo).val().replace(/\./g, '').replace(',', '.'));
        var costo_setup_hora_proceso_productivo = parseFloat($('#costo_setup_hora_proceso_productivo' + contadorFormulariosProcesoProductivo).val().replace(/\./g, '').replace(',', '.'));
        var lote_setup_proceso_productivo = parseFloat($('#lote_setup_proceso_productivo' + contadorFormulariosProcesoProductivo).val().replace(/\./g, '').replace(',', '.'));

        var porcent_eficiencia_proceso_productivo = (eficiencia_proceso_productivo / 100);

        if (!isNaN(costo_maquina_hora_proceso_productivo) && !isNaN(tiempo_ciclo_proceso_productivo) &&
            !isNaN(cantidad_cavidades_proceso_productivo) && !isNaN(eficiencia_proceso_productivo)) {

            var costoMaquinaFinal = costo_maquina_hora_proceso_productivo / (3600 / tiempo_ciclo_proceso_productivo * cantidad_cavidades_proceso_productivo * porcent_eficiencia_proceso_productivo);

            if (isNaN(costoMaquinaFinal)) {
                $('#costo_final_maquina_proceso_productivo' + contadorFormulariosProcesoProductivo).val(0);
            } else {
                var costoMaquinaFinalFormateado = costoMaquinaFinal.toLocaleString('es-ES', {
                    minimumFractionDigits: 3,
                    maximumFractionDigits: 3
                });
                $('#costo_final_maquina_proceso_productivo' + contadorFormulariosProcesoProductivo).val(costoMaquinaFinalFormateado);
            }

            var delete_with_contadorFormulariosProcesoProductivo = '';
            calcularTotalFinalCostoMaquina(delete_with_contadorFormulariosProcesoProductivo);

        }

        if (!isNaN(costo_maquina_hora_proceso_productivo) && !isNaN(tiempo_ciclo_proceso_productivo) &&
            !isNaN(cantidad_cavidades_proceso_productivo) && !isNaN(eficiencia_proceso_productivo) &&
            !isNaN(cantidad_mano_obra_directa_proceso_productivo) && !isNaN(mano_obra_directa_proceso_productivo)) {

            var manoObraDirectaFinal = cantidad_mano_obra_directa_proceso_productivo * mano_obra_directa_proceso_productivo / (3600 / tiempo_ciclo_proceso_productivo * porcent_eficiencia_proceso_productivo * cantidad_cavidades_proceso_productivo);

            if (isNaN(manoObraDirectaFinal)) {
                $('#mano_obra_directa_final_proceso_productivo' + contadorFormulariosProcesoProductivo).val(0);
            } else {
                var manoObraDirectaFinalFormateado = manoObraDirectaFinal.toLocaleString('es-ES', {
                    minimumFractionDigits: 3,
                    maximumFractionDigits: 3
                });
                $('#mano_obra_directa_final_proceso_productivo' + contadorFormulariosProcesoProductivo).val(manoObraDirectaFinalFormateado);
            }

            var delete_with_contadorFormulariosProcesoProductivo = '';
            calcularTotalFinalCostoMaquina(delete_with_contadorFormulariosProcesoProductivo);

        }

        if (!isNaN(tiempo_setup_proceso_productivo) && !isNaN(costo_setup_hora_proceso_productivo) &&
            !isNaN(lote_setup_proceso_productivo)) {

            var costoFinalSetup = tiempo_setup_proceso_productivo * costo_setup_hora_proceso_productivo / lote_setup_proceso_productivo;

            if (isNaN(costoFinalSetup)) {
                $('#costo_final_setup_hora_proceso_productivo' + contadorFormulariosProcesoProductivo).val(0);
            } else {
                var costoFinalSetupFormateado = costoFinalSetup.toLocaleString('es-ES', {
                    minimumFractionDigits: 3,
                    maximumFractionDigits: 3
                });
                $('#costo_final_setup_hora_proceso_productivo' + contadorFormulariosProcesoProductivo).val(costoFinalSetupFormateado);
            }

            var delete_with_contadorFormulariosProcesoProductivo = '';
            calcularTotalFinalCostoMaquina(delete_with_contadorFormulariosProcesoProductivo);

        }

        if (!isNaN(costoMaquinaFinal) || !isNaN(manoObraDirectaFinal) || !isNaN(costoFinalSetup)) {

            var maquinaManoObraDirectaSetup = 0;

            if (!isNaN(manoObraDirectaFinal) && !isNaN(costoFinalSetup) && !isNaN(costoMaquinaFinal)) {
                maquinaManoObraDirectaSetup = manoObraDirectaFinal + costoFinalSetup + costoMaquinaFinal;
            }

            if (isNaN(costoMaquinaFinal)) {
                maquinaManoObraDirectaSetup = manoObraDirectaFinal + costoFinalSetup;
            }

            if (isNaN(costoFinalSetup)) {
                maquinaManoObraDirectaSetup = manoObraDirectaFinal + costoMaquinaFinal;
            }

            if (isNaN(manoObraDirectaFinal)) {
                maquinaManoObraDirectaSetup = costoMaquinaFinal + costoFinalSetup;
            }

            if (isNaN(maquinaManoObraDirectaSetup)) {
                $('#maquina_mano_obra_directa_setup_proceso_productivo' + contadorFormulariosProcesoProductivo).val(0);
            } else {
                var maquinaManoObraDirectaSetupFormateado = maquinaManoObraDirectaSetup.toLocaleString('es-ES', {
                    minimumFractionDigits: 3,
                    maximumFractionDigits: 3
                });
                $('#maquina_mano_obra_directa_setup_proceso_productivo' + contadorFormulariosProcesoProductivo).val(maquinaManoObraDirectaSetupFormateado);
            }
        } else {
            $('#maquina_mano_obra_directa_setup_proceso_productivo' + contadorFormulariosProcesoProductivo).val(0);
        }

        var addProcesoProductivoButton = document.querySelector('.addProcesoProductivo');

        var etapa_proceso_productivo = $('#etapa_proceso_productivo' + contadorFormulariosProcesoProductivo).val();
        var nombre_maquina_proceso_productivo = $('#nombre_maquina_proceso_productivo' + contadorFormulariosProcesoProductivo).val();

        if (etapa_proceso_productivo !== '' && nombre_maquina_proceso_productivo !== '' && costo_maquina_hora_proceso_productivo !== '' && tiempo_ciclo_proceso_productivo !== '' && cantidad_cavidades_proceso_productivo !== '' && eficiencia_proceso_productivo !== '' && cantidad_mano_obra_directa_proceso_productivo !== '' && mano_obra_directa_proceso_productivo !== '' && tiempo_setup_proceso_productivo !== '' && costo_setup_hora_proceso_productivo !== '' && lote_setup_proceso_productivo !== '') {
            addProcesoProductivoButton.removeAttribute('disabled');
        } else {
            addProcesoProductivoButton.setAttribute('disabled', 'disabled');
        }
    };

    function removemateriaprima(button) {

        var delete_with_contadorFormulariosMateriaPrima = contadorFormulariosMateriaPrima;
        calcularTotalMateriaPrima(delete_with_contadorFormulariosMateriaPrima);

        $(button).closest('.row').remove();

        contadorFormulariosMateriaPrima--;

        var moneda_unidad_materia_prima = $('#moneda_unidad_materia_prima' + contadorFormulariosMateriaPrima).val();
        var unidad_pieza_materia_prima = $('#unidad_pieza_materia_prima' + contadorFormulariosMateriaPrima).val();
        var nombre_materia_prima = $('#nombre_materia_prima' + contadorFormulariosMateriaPrima).val();
        var unidad_materia_prima = $('#unidad_materia_prima' + contadorFormulariosMateriaPrima).val();

        $('#moneda_unidad_materia_prima' + contadorFormulariosMateriaPrima).removeAttr('readonly');
        $('#unidad_pieza_materia_prima' + contadorFormulariosMateriaPrima).removeAttr('readonly');
        $('#nombre_materia_prima' + contadorFormulariosMateriaPrima).removeAttr('readonly');
        $('#unidad_materia_prima' + contadorFormulariosMateriaPrima).removeAttr('readonly');

        var addMateriaPrimaButton = document.querySelector('.addMateriaPrima');

        if (moneda_unidad_materia_prima !== '' && unidad_pieza_materia_prima !== '' && nombre_materia_prima !== '' && unidad_materia_prima !== '') {
            addMateriaPrimaButton.removeAttribute('disabled');
        } else {
            addMateriaPrimaButton.setAttribute('disabled', 'disabled');
        }
    }

    function removeprocesoproductivo(button) {

        var delete_with_contadorFormulariosProcesoProductivo = contadorFormulariosProcesoProductivo;
        calcularTotalFinalCostoMaquina(delete_with_contadorFormulariosProcesoProductivo);

        $(button).closest('.row').remove();

        contadorFormulariosProcesoProductivo--;

        $('#etapa_proceso_productivo' + contadorFormulariosProcesoProductivo).removeAttr('readonly');
        $('#nombre_maquina_proceso_productivo' + contadorFormulariosProcesoProductivo).removeAttr('readonly');
        $('#cantidad_cavidades_proceso_productivo' + contadorFormulariosProcesoProductivo).removeAttr('readonly');
        $('#tiempo_ciclo_proceso_productivo' + contadorFormulariosProcesoProductivo).removeAttr('readonly');
        $('#eficiencia_proceso_productivo' + contadorFormulariosProcesoProductivo).removeAttr('readonly');
        $('#costo_maquina_hora_proceso_productivo' + contadorFormulariosProcesoProductivo).removeAttr('readonly');
        $('#cantidad_mano_obra_directa_proceso_productivo' + contadorFormulariosProcesoProductivo).removeAttr('readonly');
        $('#mano_obra_directa_proceso_productivo' + contadorFormulariosProcesoProductivo).removeAttr('readonly');
        $('#tiempo_setup_proceso_productivo' + contadorFormulariosProcesoProductivo).removeAttr('readonly');
        $('#costo_setup_hora_proceso_productivo' + contadorFormulariosProcesoProductivo).removeAttr('readonly');
        $('#cantidad_mano_obra_directa_proceso_productivo' + contadorFormulariosProcesoProductivo).removeAttr('readonly');
        $('#lote_setup_proceso_productivo' + contadorFormulariosProcesoProductivo).removeAttr('readonly');

        var costo_maquina_hora_proceso_productivo = $('#costo_maquina_hora_proceso_productivo' + contadorFormulariosProcesoProductivo).val();
        var tiempo_ciclo_proceso_productivo = $('#tiempo_ciclo_proceso_productivo' + contadorFormulariosProcesoProductivo).val();
        var cantidad_cavidades_proceso_productivo = $('#cantidad_cavidades_proceso_productivo' + contadorFormulariosProcesoProductivo).val();
        var eficiencia_proceso_productivo = $('#eficiencia_proceso_productivo' + contadorFormulariosProcesoProductivo).val();
        var cantidad_mano_obra_directa_proceso_productivo = $('#cantidad_mano_obra_directa_proceso_productivo' + contadorFormulariosProcesoProductivo).val();
        var mano_obra_directa_proceso_productivo = $('#mano_obra_directa_proceso_productivo' + contadorFormulariosProcesoProductivo).val();
        var tiempo_setup_proceso_productivo = $('#tiempo_setup_proceso_productivo' + contadorFormulariosProcesoProductivo).val();
        var costo_setup_hora_proceso_productivo = $('#costo_setup_hora_proceso_productivo' + contadorFormulariosProcesoProductivo).val();
        var lote_setup_proceso_productivo = $('#lote_setup_proceso_productivo' + contadorFormulariosProcesoProductivo).val();
        var etapa_proceso_productivo = $('#etapa_proceso_productivo' + contadorFormulariosProcesoProductivo).val();
        var nombre_maquina_proceso_productivo = $('#nombre_maquina_proceso_productivo' + contadorFormulariosProcesoProductivo).val();

        var addProcesoProductivoButton = document.querySelector('.addProcesoProductivo');

        if (etapa_proceso_productivo != '' && nombre_maquina_proceso_productivo != '' && costo_maquina_hora_proceso_productivo !== '' && tiempo_ciclo_proceso_productivo !== '' && cantidad_cavidades_proceso_productivo != '' && eficiencia_proceso_productivo != '' && cantidad_mano_obra_directa_proceso_productivo != '' && mano_obra_directa_proceso_productivo != '' && tiempo_setup_proceso_productivo != '' && costo_setup_hora_proceso_productivo != '' && lote_setup_proceso_productivo != '') {
            addProcesoProductivoButton.removeAttribute('disabled');
        } else {
            addProcesoProductivoButton.setAttribute('disabled', 'disabled');
        }
    }

    function validarInputNumber(input) {

        var valor = input.value;


        valor = valor.replace(/[^\d,.]/g, '');

        const tieneComa = valor.includes(',');

        if (!tieneComa) {
            valor = valor.replace(/\./g, '');
            valor = valor.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        input.value = valor;

    }

    function validarPorcentaje(input) {

        var valor = input.value;
        valor = valor.replace(/[^\d,]/g, '');

        if (valor > 100) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "El porcentaje no puede ser mayor a 100, intenta nuevamente",
                showConfirmButton: false,
                timer: 2000
            });
            input.value = '';
        } else {
            input.value = valor;
        }
    }

    function actualizarLabelMoneda() {

        var monedaSeleccionada = $('select[name="moneda_costbreakdown"]').val();

        var monedaunid = monedaSeleccionada + " / UND: *";
        $('#labelMonedaUnid' + contadorFormulariosMateriaPrima).text(monedaunid);
        $('#moneda_unidad_materia_prima' + contadorFormulariosMateriaPrima).attr('placeholder', monedaunid);


        var copPieza = "<?php echo $lang['Cop_Pieza']; ?>";
        var monedapiece = monedaSeleccionada + copPieza;
        $('#label_moneda_pieza_materia_prima' + contadorFormulariosMateriaPrima).text(monedapiece);
        $('#label1CopPieza').text(monedapiece);
        $('#label2CopPieza').text(monedapiece);
        $('#label3CopPieza').text(monedapiece);
        $('#label4CopPieza').text(monedapiece);
        $('#label5CopPieza').text(monedapiece);
        $('#label6CopPieza').text(monedapiece);
        $('#label7CopPieza').text(monedapiece);
        $('#label8CopPieza').text(monedapiece);
        $('#label9CopPieza').text(monedapiece);
        $('#label10CopPieza').text(monedapiece);

        $('#moneda_pieza_embalaje').attr('placeholder', monedapiece);
        $('#moneda_pieza_materia_prima').attr('placeholder', monedapiece);

        var costoMaquinaHora = "<?php echo $lang['CostoMaquinaHora']; ?>"
        var hora = "<?php echo $lang['Hora']; ?>"
        var labelCostoMaquinaHora = costoMaquinaHora + " (" + monedaSeleccionada + " / " + hora + "): *";
        $('#labelCostoMaquinaHora' + contadorFormulariosProcesoProductivo).text(labelCostoMaquinaHora);
        var placeholderCostoMaquinaHora = "<?php echo $lang['IngreseCostoMaquinaHora']; ?>";
        $('#costo_maquina_hora_proceso_productivo' + contadorFormulariosProcesoProductivo).attr('placeholder', placeholderCostoMaquinaHora);

        var manoObraDirecta = "<?php echo $lang['ManoObraDirecta']; ?>";
        $('#labelManoObraDirecta' + contadorFormulariosProcesoProductivo).text(manoObraDirecta + " (" + monedaSeleccionada + " / " + hora + "): *");
        var IngreseManoObraDirecta = "<?php echo $lang['IngreseManoObraDirecta']; ?>";
        var placeholderManoObraDirecta = IngreseManoObraDirecta + " (" + monedaSeleccionada + " / " + hora + ")";
        $('#mano_obra_directa_proceso_productivo' + contadorFormulariosProcesoProductivo).attr('placeholder', placeholderManoObraDirecta);

        var costoSetUp = "<?php echo $lang['CostoSetUp']; ?>";
        $('#labelCostoSetUp' + contadorFormulariosProcesoProductivo).text(costoSetUp + " (" + monedaSeleccionada + " / " + hora + "): *");
        var IngreseCostoSetUp = "<?php echo $lang['IngreseCostoSetUp']; ?>";
        var placeholderCostSetUp = IngreseCostoSetUp + " (" + monedaSeleccionada + " / " + hora + ")";
        $('#costo_setup_hora_proceso_productivo' + contadorFormulariosProcesoProductivo).attr('placeholder', placeholderCostSetUp);

        var costoFinalMaquina = "<?php echo $lang['CostoFinalMaquina']; ?>";
        $('#labelCostoFinalMaquina' + contadorFormulariosProcesoProductivo).text(costoFinalMaquina + " (" + monedaSeleccionada + copPieza + ")");

        var manoObraDirectaFinal = "<?php echo $lang['ManoObraDirectaFinal'] ?>";
        $('#labelManoObraDirectaFinal' + contadorFormulariosProcesoProductivo).text(manoObraDirectaFinal + " (" + monedaSeleccionada + copPieza + ")");

        var costoFinalSetup = "<?php echo $lang['CostoFinalSetup'] ?>";
        $('#labelCostoFinalSetup' + contadorFormulariosProcesoProductivo).text(costoFinalSetup + " (" + monedaSeleccionada + copPieza + ")");

        var maquinaManoObraDirectaSetup = "<?php echo $lang['MaquinaManoObraDirectaSetup'] ?>";
        $('#labelMaquinaManoObraDirectaSetup' + contadorFormulariosProcesoProductivo).text(maquinaManoObraDirectaSetup + " (" + monedaSeleccionada + copPieza + ")");

        var inversion = "<?php echo $lang['Inversion'] ?>";
        $('#label_inversion_amortizacion' + contadorFormulariosAmortizacion).text(inversion + " (" + monedaSeleccionada + "): *");
        var placeholderInversion = "<?php echo $lang['IngreseInversion'] ?>";
        $('#inversion_amortizacion' + contadorFormulariosAmortizacion).attr('placeholder', placeholderInversion + " (" + monedaSeleccionada + ")");

        $('#label_moneda_pieza_Amortizacion' + contadorFormulariosAmortizacion).text(monedapiece);
    }

    function amortizacion() {

        var descripcion_amortizacion = parseFloat($('#descripcion_amortizacion' + contadorFormulariosAmortizacion).val().replace(/\./g, '').replace(',', '.'));
        var inversion_amortizacion = parseFloat($('#inversion_amortizacion' + contadorFormulariosAmortizacion).val().replace(/\./g, '').replace(',', '.'));
        var piezas_amortizadas = parseFloat($('#piezas_amortizadas' + contadorFormulariosAmortizacion).val().replace(/\./g, '').replace(',', '.'));

        var moneda_pieza_amortizacion = 0;

        if (!isNaN(inversion_amortizacion) && !isNaN(piezas_amortizadas)) {

            moneda_pieza_amortizacion = (inversion_amortizacion / piezas_amortizadas);

            if (isNaN(moneda_pieza_amortizacion)) {
                $('#moneda_pieza_amortizacion' + contadorFormulariosAmortizacion).val(0);
            } else {
                var moneda_pieza_amortizacion_formateada = moneda_pieza_amortizacion.toLocaleString('es-ES', {
                    minimumFractionDigits: 3,
                    maximumFractionDigits: 3
                });
                $('#moneda_pieza_amortizacion' + contadorFormulariosAmortizacion).val(moneda_pieza_amortizacion_formateada);
            }

            var delete_with_contadorFormulariosAmortizacion = '';
            calcularTotalAmortizacion(delete_with_contadorFormulariosAmortizacion);
        } else {
            $('#moneda_pieza_amortizacion' + contadorFormulariosAmortizacion).val();
        }

        if (inversion_amortizacion === '' || piezas_amortizadas === '') {
            $('#moneda_pieza_amortizacion' + contadorFormulariosAmortizacion).val('');
        }

        var addAmortizacionButton = document.querySelector('.addAmortizacion');

        if (descripcion_amortizacion !== '' && inversion_amortizacion !== '' && piezas_amortizadas !== '') {
            addAmortizacionButton.removeAttribute('disabled');
            addAmortizacionButton.style.backgroundColor = '#0093b2';
        } else {
            addAmortizacionButton.setAttribute('disabled', 'disabled');
            addAmortizacionButton.style.backgroundColor = '#00a8cc';
        }
    }

    function addAmortizacion() {
        contadorFormulariosAmortizacion++;

        var nuevoFormularioAmortizacion = `

            <div class="row mt-3">
                <hr class="mt-5">
                <div class="col-md-6 mt-5">
                    <label for="descripcion_amortizacion" class="floating-label"><?php echo $lang['Descripcion Amortizacion']; ?></label>
                    <input required name="descripcion_amortizacion[]" type="text" id="descripcion_amortizacion${contadorFormulariosAmortizacion}" onchange="amortizacion()" class="form-control custom-input" placeholder="<?php echo $lang['Ingrese Descripcion Amortizacion']; ?>">
                </div>
                <div class="col-md-6 mt-5">
                    <label for="inversion_amortizacion" class="floating-label" id="label_inversion_amortizacion${contadorFormulariosAmortizacion}">---</label>
                    <input required name="inversion_amortizacion[]" type="text" id="inversion_amortizacion${contadorFormulariosAmortizacion}" onchange="amortizacion()" oninput="validarInputNumber(this)" class="form-control custom-input" placeholder="---">
                </div>
                <div class="col-md-6 mt-5">
                    <label for="piezas_amortizadas" class="floating-label"><?php echo $lang['PiezasAmortizadas']; ?></label>
                    <input required name="piezas_amortizadas[]" type="text" id="piezas_amortizadas${contadorFormulariosAmortizacion}" onchange="amortizacion()" oninput="validarInputNumber(this)" class="form-control custom-input" placeholder="<?php echo $lang['IngresePiezasAmortizadas']; ?>">
                </div>
                <div class="col-md-5 mt-5">
                    <label for="moneda_pieza_amortizacion" class="floating-label" id="label_moneda_pieza_Amortizacion${contadorFormulariosAmortizacion}">--- / ---</label>
                    <input readonly name="moneda_pieza_amortizacion[]" type="text" id="moneda_pieza_amortizacion${contadorFormulariosAmortizacion}" onchange="amortizacion()" oninput="validarInputNumber(this)" class="form-control custom-input">
                </div>
                <div class="col-md-1 mt-5">
                    <button type="button" class="btn btn-danger removeamortizacion" onclick="removeAmortizacion(this)"><i class="material-icons">delete</i></button>
                </div>
            </div>
        `;

        $('#ContenedorAmortizacion').append(nuevoFormularioAmortizacion);
        var addAmortizacionButton = document.querySelector('.addAmortizacion');
        addAmortizacionButton.setAttribute('disabled', 'disabled');
        actualizarLabelMoneda();
    }

    function removeAmortizacion(button) {

        var delete_with_contadorFormulariosAmortizacion = contadorFormulariosAmortizacion;
        calcularTotalAmortizacion(delete_with_contadorFormulariosAmortizacion);

        $(button).closest('.row').remove();

        contadorFormulariosAmortizacion--;

        var descripcion_amortizacion = $('#descripcion_amortizacion' + contadorFormulariosAmortizacion).val();
        var inversion_amortizacion = $('#inversion_amortizacion' + contadorFormulariosAmortizacion).val();
        var piezas_amortizadas = $('#piezas_amortizadas' + contadorFormulariosAmortizacion).val();
        var addAmortizacionButton = document.querySelector('.addAmortizacion');

        if (descripcion_amortizacion !== '' && inversion_amortizacion !== '' && piezas_amortizadas !== '') {
            addAmortizacionButton.removeAttribute('disabled');
            addAmortizacionButton.style.backgroundColor = '#0093b2';
        } else {
            addAmortizacionButton.setAttribute('disabled', 'disabled');
            addAmortizacionButton.style.backgroundColor = '#00a8cc';
        }
    }

    function calcularScrap() {

        var porcentaje_scrap = parseFloat($('#porcentaje_scrap').val().replace(',', '.'));
        var new_porcentaje_scrap = porcentaje_scrap / 100;

        var total_moneda_pieza_materia_prima = parseFloat($('#total_moneda_pieza_materia_prima').val().replace(/\./g, '').replace(',', '.'));
        var total_moneda_pieza_costo_maquina = parseFloat($('#total_moneda_pieza_costo_maquina').val().replace(/\./g, '').replace(',', '.'));
        var total_moneda_pieza_mano_obra_directa = parseFloat($('#total_moneda_pieza_mano_obra_directa').val().replace(/\./g, '').replace(',', '.'));
        var total_moneda_pieza_costo_setup = parseFloat($('#total_moneda_pieza_costo_setup').val().replace(/\./g, '').replace(',', '.'));

        var suma_moneda_pieza_scrap = (total_moneda_pieza_materia_prima + total_moneda_pieza_costo_maquina + total_moneda_pieza_mano_obra_directa + total_moneda_pieza_costo_setup);
        var moneda_pieza_scrap = (new_porcentaje_scrap * suma_moneda_pieza_scrap);

        if (!isNaN(moneda_pieza_scrap)) {
            var moneda_pieza_scrap_formateada = moneda_pieza_scrap.toLocaleString('es-ES', {
                minimumFractionDigits: 3,
                maximumFractionDigits: 3
            });
            $('#moneda_pieza_scrap').val(moneda_pieza_scrap_formateada);
        } else {
            $('#moneda_pieza_scrap').val('');
        }

        calcularPrecioNetoTotal();
    }

    function calcularMarkup() {

        var porcentaje_flete = parseFloat($('#porcentaje_flete').val().replace(/\./g, '').replace(',', '.'));
        var porcentaje_SGA = parseFloat($('#porcentaje_SGA').val().replace(/\./g, '').replace(',', '.'));
        var porcentaje_margen_beneficio = parseFloat($('#porcentaje_margen_beneficio').val().replace(/\./g, '').replace(',', '.'));

        var precio_neto_total = parseFloat($('#precio_neto_total').val().replace(/\./g, '').replace(',', '.'));

        if (!isNaN(porcentaje_flete)) {

            var new_porcentaje_flete = porcentaje_flete / 100;
            var moneda_pieza_flete = (new_porcentaje_flete * precio_neto_total);
            var moneda_pieza_flete_formateada = moneda_pieza_flete.toLocaleString('es-ES', {
                minimumFractionDigits: 3,
                maximumFractionDigits: 3
            });

            $('#moneda_pieza_flete').val(moneda_pieza_flete_formateada);

        } else {

            $('#moneda_pieza_flete').val(0);

        }


        if (!isNaN(porcentaje_SGA)) {

            var new_porcentaje_SGA = porcentaje_SGA / 100;
            var moneda_pieza_SGA = (new_porcentaje_SGA * precio_neto_total);

            var moneda_pieza_SGA_formateada = moneda_pieza_SGA.toLocaleString('es-ES', {
                minimumFractionDigits: 3,
                maximumFractionDigits: 3
            });

            $('#moneda_pieza_SGA').val(moneda_pieza_SGA_formateada);

        } else {

            $('#moneda_pieza_SGA').val(0);

        }


        if (!isNaN(porcentaje_margen_beneficio)) {

            var new_porcentaje_margen_beneficio = porcentaje_margen_beneficio / 100;
            var moneda_pieza_margen_beneficio = (new_porcentaje_margen_beneficio * precio_neto_total);

            var moneda_pieza_margen_beneficio_formateada = moneda_pieza_margen_beneficio.toLocaleString('es-ES', {
                minimumFractionDigits: 3,
                maximumFractionDigits: 3
            });

            $('#moneda_pieza_margen_beneficio').val(moneda_pieza_margen_beneficio_formateada);

        } else {

            $('#moneda_pieza_margen_beneficio').val(0);

        }

    }

    function calcularPrecioNetoTotal() {

        var precio_neto_total = 0;

        var total_moneda_pieza_materia_prima = parseFloat($('#total_moneda_pieza_materia_prima').val().replace(/\./g, '').replace(',', '.'));
        var total_moneda_pieza_costo_maquina = parseFloat($('#total_moneda_pieza_costo_maquina').val().replace(/\./g, '').replace(',', '.'));
        var total_moneda_pieza_mano_obra_directa = parseFloat($('#total_moneda_pieza_mano_obra_directa').val().replace(/\./g, '').replace(',', '.'));
        var total_moneda_pieza_costo_setup = parseFloat($('#total_moneda_pieza_costo_setup').val().replace(/\./g, '').replace(',', '.'));
        var total_moneda_pieza_amortizacion = parseFloat($('#total_moneda_pieza_amortizacion').val().replace(/\./g, '').replace(',', '.'));
        var moneda_pieza_embalaje = parseFloat($('#moneda_pieza_embalaje').val().replace(/\./g, '').replace(',', '.'));
        var moneda_pieza_scrap = parseFloat($('#moneda_pieza_scrap').val().replace(/\./g, '').replace(',', '.'));
        var porcentaje_flete = parseFloat($('#porcentaje_flete').val().replace(/\./g, '').replace(',', '.')) / 100;
        var porcentaje_SGA = parseFloat($('#porcentaje_SGA').val().replace(/\./g, '').replace(',', '.')) / 100;
        var porcentaje_margen_beneficio = parseFloat($('#porcentaje_margen_beneficio').val().replace(/\./g, '').replace(',', '.')) / 100;

        if (!isNaN(total_moneda_pieza_materia_prima) && !isNaN(total_moneda_pieza_costo_maquina) &&
            !isNaN(total_moneda_pieza_mano_obra_directa) && !isNaN(total_moneda_pieza_costo_setup) &&
            !isNaN(total_moneda_pieza_amortizacion) && !isNaN(moneda_pieza_embalaje) &&
            !isNaN(moneda_pieza_scrap) && !isNaN(porcentaje_flete) &&
            !isNaN(porcentaje_SGA) && !isNaN(porcentaje_margen_beneficio)) {

            precio_neto_total = ((total_moneda_pieza_materia_prima + total_moneda_pieza_costo_maquina +
                total_moneda_pieza_mano_obra_directa + total_moneda_pieza_costo_setup + total_moneda_pieza_amortizacion +
                moneda_pieza_embalaje + moneda_pieza_scrap) / (1 - porcentaje_flete -
                porcentaje_SGA - porcentaje_margen_beneficio));
        }

        if (isNaN(precio_neto_total)) {
            $('#precio_neto_total').val(0);
        } else {
            var precio_neto_total_formateado = precio_neto_total.toLocaleString('es-ES', {
                minimumFractionDigits: 3,
                maximumFractionDigits: 3
            });

            $('#precio_neto_total').val(precio_neto_total_formateado);

            calcularMarkup();
            calcularPorcentajesTotales();
        }
    }

    function calcularPorcentajesTotales() {

        var precio_neto_total = parseFloat($('#precio_neto_total').val().replace(/\./g, '').replace(',', '.'));

        if (!isNaN(precio_neto_total)) {

            //Materia Prima

            var porcentaje_total_materia_prima_formateado = parseFloat(0, 0);
            $('#porcentaje_total_materia_prima' + i).val(porcentaje_total_materia_prima_formateado + " %");

            for (var i = 0; i <= contadorFormulariosMateriaPrima; i++) {

                var moneda_pieza_materia_prima = $('#moneda_pieza_materia_prima' + i).val();
                if (moneda_pieza_materia_prima) {
                    moneda_pieza_materia_prima = moneda_pieza_materia_prima.replace(/\./g, '').replace(',', '.');
                    moneda_pieza_materia_prima = parseFloat(moneda_pieza_materia_prima);
                    var porcentaje_total_materia_prima = (moneda_pieza_materia_prima / precio_neto_total) * 100;
                }

                if (isNaN(porcentaje_total_materia_prima)) {
                    $('#porcentaje_total_materia_prima' + i).val(0);
                } else {
                    var porcentaje_total_materia_prima_formateado = porcentaje_total_materia_prima.toLocaleString('es-ES', {
                        minimumFractionDigits: 1,
                        maximumFractionDigits: 1
                    });
                    $('#porcentaje_total_materia_prima' + i).val(porcentaje_total_materia_prima_formateado + " %");
                }
            }

            var total_moneda_pieza_materia_prima = parseFloat($('#total_moneda_pieza_materia_prima').val().replace(/\./g, '').replace(',', '.'));
            var porcentaje_final_materia_prima = (total_moneda_pieza_materia_prima / precio_neto_total) * 100;

            if (isNaN(porcentaje_final_materia_prima)) {
                $('#porcentaje_final_materia_prima').val(0);
            } else {
                var porcentaje_final_materia_prima_formateado = porcentaje_final_materia_prima.toLocaleString('es-ES', {
                    minimumFractionDigits: 1,
                    maximumFractionDigits: 1
                });
                $('#porcentaje_final_materia_prima').val(porcentaje_final_materia_prima_formateado + " %");
            }

            //Proceso Productivo

            for (var i = 0; i <= contadorFormulariosProcesoProductivo; i++) {

                var maquina_mano_obra_directa_setup_proceso_productivo = $('#maquina_mano_obra_directa_setup_proceso_productivo' + i).val();

                if (maquina_mano_obra_directa_setup_proceso_productivo) {
                    var maquina_mano_obra_directa_setup_proceso_productivo = parseFloat($('#maquina_mano_obra_directa_setup_proceso_productivo' + i).val().replace(/\./g, '').replace(',', '.'));
                    var porcentaje_total_proceso_productivo = (maquina_mano_obra_directa_setup_proceso_productivo / precio_neto_total) * 100;

                    if (isNaN(porcentaje_total_proceso_productivo)) {
                        $('#porcentaje_total_proceso_productivo' + i).val(0);
                    } else {
                        var porcentaje_total_proceso_productivo_formateado = porcentaje_total_proceso_productivo.toLocaleString('es-ES', {
                            minimumFractionDigits: 1,
                            maximumFractionDigits: 1
                        });
                        $('#porcentaje_total_proceso_productivo' + i).val(porcentaje_total_proceso_productivo_formateado);
                    }
                }
            }

            var total_moneda_pieza_costo_maquina = parseFloat($('#total_moneda_pieza_costo_maquina').val().replace(/\./g, '').replace(',', '.'));

            if (total_moneda_pieza_costo_maquina !== 0 && total_moneda_pieza_costo_maquina !== '') {
                var porcentaje_final_moneda_pieza_costo_maquina = (total_moneda_pieza_costo_maquina / precio_neto_total) * 100;

                if (isNaN(porcentaje_final_moneda_pieza_costo_maquina)) {
                    $('#porcentaje_final_moneda_pieza_costo_maquina').val(0);
                } else {
                    var porcentaje_final_moneda_pieza_costo_maquina_formateado = porcentaje_final_moneda_pieza_costo_maquina.toLocaleString('es-ES', {
                        minimumFractionDigits: 1,
                        maximumFractionDigits: 1
                    });
                    $('#porcentaje_final_moneda_pieza_costo_maquina').val(porcentaje_final_moneda_pieza_costo_maquina_formateado + " %");
                }
            }

            var porcentaje_final_moneda_pieza_mano_obra_directa_formateado = "0,0";
            $('#porcentaje_final_moneda_pieza_mano_obra_directa').val(porcentaje_final_moneda_pieza_mano_obra_directa_formateado + " %");

            var total_moneda_pieza_mano_obra_directa = parseFloat($('#total_moneda_pieza_mano_obra_directa').val().replace(/\./g, '').replace(',', '.'));

            if (total_moneda_pieza_mano_obra_directa !== 0 && total_moneda_pieza_mano_obra_directa !== '') {
                var porcentaje_final_moneda_pieza_mano_obra_directa = (total_moneda_pieza_mano_obra_directa / precio_neto_total) * 100;

                if (isNaN(porcentaje_final_moneda_pieza_mano_obra_directa)) {
                    $('#porcentaje_final_moneda_pieza_mano_obra_directa').val(0);
                } else {
                    var porcentaje_final_moneda_pieza_mano_obra_directa_formateado = porcentaje_final_moneda_pieza_mano_obra_directa.toLocaleString('es-ES', {
                        minimumFractionDigits: 1,
                        maximumFractionDigits: 1
                    });
                    $('#porcentaje_final_moneda_pieza_mano_obra_directa').val(porcentaje_final_moneda_pieza_mano_obra_directa_formateado + " %");
                }
            }

            var total_moneda_pieza_costo_setup = parseFloat($('#total_moneda_pieza_costo_setup').val().replace(/\./g, '').replace(',', '.'));

            if (total_moneda_pieza_costo_setup !== 0 && total_moneda_pieza_costo_setup !== '') {
                var porcentaje_final_moneda_pieza_costo_setup = (total_moneda_pieza_costo_setup / precio_neto_total) * 100;

                if (isNaN(porcentaje_final_moneda_pieza_costo_setup)) {
                    $('#porcentaje_final_moneda_pieza_costo_setup').val(0);
                } else {
                    var porcentaje_final_moneda_pieza_costo_setup_formateado = porcentaje_final_moneda_pieza_costo_setup.toLocaleString('es-ES', {
                        minimumFractionDigits: 1,
                        maximumFractionDigits: 1
                    });
                    $('#porcentaje_final_moneda_pieza_costo_setup').val(porcentaje_final_moneda_pieza_costo_setup_formateado + " %");
                }
            }

            //Amortizacion

            var porcentaje_final_moneda_pieza_amortizacion_formateado = "0,0";
            $('#porcentaje_final_moneda_pieza_amortizacion').val(porcentaje_final_moneda_pieza_amortizacion_formateado + " %");

            for (var i = 0; i <= contadorFormulariosAmortizacion; i++) {

                var moneda_pieza_amortizacion = $('#moneda_pieza_amortizacion' + i).val();
                if (moneda_pieza_amortizacion) {
                    moneda_pieza_amortizacion = moneda_pieza_amortizacion.replace(/\./g, '').replace(',', '.');
                    var moneda_pieza_amortizacion = parseFloat(moneda_pieza_amortizacion);
                    var porcentaje_total_amortizacion = (moneda_pieza_amortizacion / precio_neto_total) * 100;
                }
                if (isNaN(porcentaje_total_amortizacion)) {
                    $('#porcentaje_total_amortizacion' + i).val("0,0");
                } else {
                    var porcentaje_total_amortizacion_formateado = porcentaje_total_amortizacion.toLocaleString('es-ES', {
                        minimumFractionDigits: 1,
                        maximumFractionDigits: 1
                    });
                    $('#porcentaje_total_amortizacion' + i).val(porcentaje_total_amortizacion_formateado);
                }
            }

            var total_moneda_pieza_amortizacion = parseFloat($('#total_moneda_pieza_amortizacion').val().replace(/\./g, '').replace(',', '.'));

            if (total_moneda_pieza_amortizacion !== 0 && total_moneda_pieza_amortizacion !== 0) {
                var porcentaje_final_moneda_pieza_amortizacion = (total_moneda_pieza_amortizacion / precio_neto_total) * 100;

                if (isNaN(porcentaje_final_moneda_pieza_amortizacion)) {
                    $('#porcentaje_final_moneda_pieza_amortizacion').val(0);
                } else {
                    var porcentaje_final_moneda_pieza_amortizacion_formateado = porcentaje_final_moneda_pieza_amortizacion.toLocaleString('es-ES', {
                        minimumFractionDigits: 1,
                        maximumFractionDigits: 1
                    });
                    $('#porcentaje_final_moneda_pieza_amortizacion').val(porcentaje_final_moneda_pieza_amortizacion_formateado + " %");
                }
            }

            //Costo Embalaje

            var porcentaje_total_embalaje_formateado = "0,0";
            $('#porcentaje_total_embalaje').val(porcentaje_total_embalaje_formateado + " %");

            var moneda_pieza_embalaje = parseFloat($('#moneda_pieza_embalaje').val().replace(/\./g, '').replace(',', '.'));

            if (moneda_pieza_embalaje !== 0 && moneda_pieza_embalaje !== '') {
                var porcentaje_total_embalaje = (moneda_pieza_embalaje / precio_neto_total) * 100;

                if (isNaN(porcentaje_total_embalaje)) {
                    $('#porcentaje_total_embalaje').val(0);
                } else {
                    var porcentaje_total_embalaje_formateado = porcentaje_total_embalaje.toLocaleString('es-ES', {
                        minimumFractionDigits: 1,
                        maximumFractionDigits: 1
                    });
                    $('#porcentaje_total_embalaje').val(porcentaje_total_embalaje_formateado + " %");
                }
            }

            //Scrap

            var porcentaje_total_scrap_formateado = "0,0";
            $('#porcentaje_total_scrap').val(porcentaje_total_scrap_formateado + " %");

            var moneda_pieza_scrap = parseFloat($('#moneda_pieza_scrap').val().replace(/\./g, '').replace(',', '.'));

            if (moneda_pieza_scrap !== 0 && moneda_pieza_scrap !== '') {
                var porcentaje_total_scrap = (moneda_pieza_scrap / precio_neto_total) * 100;

                if (isNaN(porcentaje_total_scrap)) {
                    $('#porcentaje_total_scrap').val(0);
                } else {
                    var porcentaje_total_scrap_formateado = porcentaje_total_scrap.toLocaleString('es-ES', {
                        minimumFractionDigits: 1,
                        maximumFractionDigits: 1
                    });
                    $('#porcentaje_total_scrap').val(porcentaje_total_scrap_formateado + " %");
                }
            }

            //Markup

            var porcentaje_final_flete_formateado = "0,0";
            $('#porcentaje_final_flete').val(porcentaje_final_flete_formateado + " %");

            var moneda_pieza_flete = parseFloat($('#moneda_pieza_flete').val().replace(/\./g, '').replace(',', '.'));

            if (moneda_pieza_flete !== 0 && moneda_pieza_flete !== '') {
                var porcentaje_final_flete = (moneda_pieza_flete / precio_neto_total) * 100;

                if (isNaN(porcentaje_final_flete)) {
                    $('#porcentaje_final_flete').val(0);
                } else {
                    var porcentaje_final_flete_formateado = porcentaje_final_flete.toLocaleString('es-ES', {
                        minimumFractionDigits: 1,
                        maximumFractionDigits: 1
                    });
                    $('#porcentaje_final_flete').val(porcentaje_final_flete_formateado + " %");
                }
            }

            var porcentaje_final_SGA_formateado = "0,0";
            $('#porcentaje_final_SGA').val(porcentaje_final_SGA_formateado + " %");

            var moneda_pieza_SGA = parseFloat($('#moneda_pieza_SGA').val().replace(/\./g, '').replace(',', '.'));

            if (moneda_pieza_SGA !== 0 && moneda_pieza_SGA !== '') {
                var porcentaje_final_SGA = (moneda_pieza_SGA / precio_neto_total) * 100;

                if (isNaN(porcentaje_final_SGA)) {
                    $('#porcentaje_final_SGA').val(0);
                } else {
                    var porcentaje_final_SGA_formateado = porcentaje_final_SGA.toLocaleString('es-ES', {
                        minimumFractionDigits: 1,
                        maximumFractionDigits: 1
                    });
                    $('#porcentaje_final_SGA').val(porcentaje_final_SGA_formateado + " %");
                }
            }

            var porcentaje_final_margen_beneficio_formateado = "0,0";
            $('#porcentaje_final_margen_beneficio').val(porcentaje_final_margen_beneficio_formateado + " %");

            var moneda_pieza_margen_beneficio = parseFloat($('#moneda_pieza_margen_beneficio').val().replace(/\./g, '').replace(',', '.'));

            if (moneda_pieza_margen_beneficio !== 0 && moneda_pieza_margen_beneficio !== '') {
                var porcentaje_final_margen_beneficio = (moneda_pieza_margen_beneficio / precio_neto_total) * 100;

                if (isNaN(porcentaje_final_margen_beneficio)) {
                    $('#porcentaje_final_margen_beneficio').val(0);
                } else {
                    var porcentaje_final_margen_beneficio_formateado = porcentaje_final_margen_beneficio.toLocaleString('es-ES', {
                        minimumFractionDigits: 1,
                        maximumFractionDigits: 1
                    });
                    $('#porcentaje_final_margen_beneficio').val(porcentaje_final_margen_beneficio_formateado + " %");
                }
            }



            porcentaje_total_materia_prima_formateado = parseFloat(String(porcentaje_total_materia_prima_formateado).replace(',', '.'));
            porcentaje_final_moneda_pieza_mano_obra_directa_formateado = parseFloat(String(porcentaje_final_moneda_pieza_mano_obra_directa_formateado).replace(',', '.'));
            porcentaje_final_moneda_pieza_costo_maquina_formateado = parseFloat(String(porcentaje_final_moneda_pieza_costo_maquina_formateado).replace(',', '.'));
            porcentaje_final_moneda_pieza_costo_setup_formateado = parseFloat(String(porcentaje_final_moneda_pieza_costo_setup_formateado).replace(',', '.'));
            porcentaje_final_moneda_pieza_amortizacion_formateado = parseFloat(String(porcentaje_final_moneda_pieza_amortizacion_formateado).replace(',', '.'));
            porcentaje_total_embalaje_formateado = parseFloat(String(porcentaje_total_embalaje_formateado).replace(',', '.'));
            porcentaje_total_scrap_formateado = parseFloat(String(porcentaje_total_scrap_formateado).replace(',', '.'));
            porcentaje_final_SGA_formateado = parseFloat(String(porcentaje_final_SGA_formateado).replace(',', '.'));
            porcentaje_final_flete_formateado = parseFloat(String(porcentaje_final_flete_formateado).replace(',', '.'));
            porcentaje_final_margen_beneficio_formateado = parseFloat(String(porcentaje_final_margen_beneficio_formateado).replace(',', '.'));

            var porcentajeFinal = (porcentaje_total_materia_prima_formateado +
                porcentaje_final_moneda_pieza_mano_obra_directa_formateado +
                porcentaje_final_moneda_pieza_costo_maquina_formateado +
                porcentaje_final_moneda_pieza_costo_setup_formateado +
                porcentaje_final_moneda_pieza_amortizacion_formateado +
                porcentaje_total_embalaje_formateado +
                porcentaje_total_scrap_formateado +
                porcentaje_final_SGA_formateado +
                porcentaje_final_flete_formateado +
                porcentaje_final_margen_beneficio_formateado)
            $('#porcentajeFinal').val(porcentajeFinal.toFixed(1) + " %");

        }


    }

    function Mostrarcb2() {

        contadorFormulariosCostBreakDownSimplified = 0;

        var partnumberSeleccionado = $("select[name='id_partnumber']").val();
        var tipoCbd = 2;
        $('.hidden').removeClass('hidden');

        $.ajax({
            type: 'POST',
            url: '../../Actions/Supplier/consultarCostBreakDown.php',
            data: {
                partnumber: partnumberSeleccionado,
                tipoCbd: tipoCbd
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    var data = response.data;
                    var rowCount;
                    if (Array.isArray(data)) {
                        rowCount = data.length;
                    } else {
                        rowCount = Object.keys(data).length;
                    }

                    if (response.data[0].porcentaje_costbreakdown_simplified !== null) {
                        for (var i = 0; i < rowCount; i++) {

                            if (i > 0) {
                                addSeccion();
                            }

                            $('#moneda_costbreakdown').val(response.data[i].moneda_costbreakdown_simplified);
                            $('#precio_costbreakdown_simplified').val(response.data[i].precio_costbreakdown_simplified);
                            $('#descripcion_costbreakdown_simplified' + i).val(response.data[i].descripcion_costbreakdown_simplified);
                            $('#porcentaje_costbreakdown_simplified' + i).val(response.data[i].porcentaje_costbreakdown_simplified);
                            var deletewithcb2 = '';
                            calcularTotalCbd2(deletewithcb2);

                        }
                        $('#cargarDatosCbd2').attr('disabled', 'disabled');
                    } else {
                        $('#total_final_cbd').val('');
                    }

                    $('#desc_partnumber').val(response.data[0].descripcion_partnumber);
                }
            },
        });

        $('#ContenedorCostbreakDownSimplified').empty();

        var formularioCostBreakDownSimplified = `
            <div class="row mt-5">
                <div class="col-md-6">
                    <label for="descripcion_costbreakdown_simplified" class="floating-label"><?php echo $lang['Descripcion']; ?></label>
                    <input autocompvare="off" name="descripcion_costbreakdown_simplified[]" type="text" id="descripcion_costbreakdown_simplified${contadorFormulariosCostBreakDownSimplified}" onchange="calcularTotalCbdSimplified()" class="form-control custom-input" placeholder="<?php echo $lang['IngreseDescripcion']; ?>">
                </div>
                <div class="col-md-5">
                    <label for="porcentaje_costbreakdown_simplified" class="floating-label"><?php echo $lang['Porcentaje']; ?></label>
                    <div class="input-group">
                        <input autocompvare="off" name="porcentaje_costbreakdown_simplified[]" id="porcentaje_costbreakdown_simplified${contadorFormulariosCostBreakDownSimplified}" onchange="calcularTotalCbdSimplified()" oninput="validarPorcentaje(this)" type="text" class="form-control custom-input" placeholder="<?php echo $lang['IngresePorcentaje']; ?>">
                    </div>
                </div>
                <div class="col-md-1" style="margin-top: 35px">
                    <button disabled type="button" class="btn btn-danger" id="removecbd2${contadorFormulariosCostBreakDownSimplified}"><i class="material-icons">delete</i></button>
                </div>
            </div>
         `;

        $('#ContenedorCostbreakDownSimplified').append(formularioCostBreakDownSimplified);
    }

    function calcularTotalCbdSimplified() {

        var n = contadorFormulariosCostBreakDownSimplified;
        var total_final_cbd = 0;
        var descripcion_costbreakdown_simplified = $('#descripcion_costbreakdown_simplified' + contadorFormulariosCostBreakDownSimplified).val();
        var porcentaje_costbreakdown_simplified = $('#porcentaje_costbreakdown_simplified' + contadorFormulariosCostBreakDownSimplified).val();
        var addSeccion = document.querySelector('.addSeccion');
        var precio_costbreakdown_simplified = document.getElementById("precio_costbreakdown_simplified").value;
        var moneda_costbreakdown = document.getElementById("moneda_costbreakdown").value;

        if (descripcion_costbreakdown_simplified !== '' && porcentaje_costbreakdown_simplified !== '') {

            var sumaPorcentajesAnteriores = 0;
            for (var i = 0; i < n; i++) {
                sumaPorcentajesAnteriores += parseFloat($('#porcentaje_costbreakdown_simplified' + i).val());
            }

            var valideTotalCbd = 100 - sumaPorcentajesAnteriores;

            if (porcentaje_costbreakdown_simplified > valideTotalCbd) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "El porcentaje no puede ser mayor a " + valideTotalCbd + ", intenta nuevamente.",
                    showConfirmButton: false,
                    timer: 2000
                });
                $('#porcentaje_costbreakdown_simplified' + contadorFormulariosCostBreakDownSimplified).val("");
                $('#total_final_cbd').val('');
            } else {
                if (precio_costbreakdown_simplified !== '' && moneda_costbreakdown !== '') {
                    addSeccion.removeAttribute('disabled');
                    var deletewithcb2 = '';
                    calcularTotalCbd2(deletewithcb2);
                }else{
                    addSeccion.setAttribute('disabled', 'disabled');
                }
            }

        } else {
            $('#total_final_cbd').val('');
            addSeccion.setAttribute('disabled', 'disabled');
        }
    }

    function addSeccion() {

        $('#descripcion_costbreakdown_simplified' + contadorFormulariosCostBreakDownSimplified).attr('readonly', 'readonly');
        $('#porcentaje_costbreakdown_simplified' + contadorFormulariosCostBreakDownSimplified).attr('readonly', 'readonly');
        $('#removecbd2' + contadorFormulariosCostBreakDownSimplified).prop('disabled', true);

        contadorFormulariosCostBreakDownSimplified++;

        var nuevoFormularioCostBreakDownSimplified = `
            <div class="row mt-5">
                <div class="col-md-6">
                    <label for="descripcion_costbreakdown_simplified" class="floating-label"><?php echo $lang['Descripcion']; ?></label>
                    <input autocompvare="off" name="descripcion_costbreakdown_simplified[]" type="text" id="descripcion_costbreakdown_simplified${contadorFormulariosCostBreakDownSimplified}" onchange="calcularTotalCbdSimplified()" class="form-control custom-input" placeholder="<?php echo $lang['IngreseDescripcion']; ?>">
                </div>
                <div class="col-md-5">
                    <label for="porcentaje_costbreakdown_simplified" class="floating-label"><?php echo $lang['Porcentaje']; ?></label>
                    <div class="input-group">
                        <input autocompvare="off" name="porcentaje_costbreakdown_simplified[]" id="porcentaje_costbreakdown_simplified${contadorFormulariosCostBreakDownSimplified}" onchange="calcularTotalCbdSimplified()" oninput="validarPorcentaje(this)" type="text" class="form-control custom-input" placeholder="<?php echo $lang['IngresePorcentaje']; ?>">
                    </div>
                </div>
                <div class="col-md-1 mt-5">
                    <button type="button" class="btn btn-danger removecbd2" id="removecbd2${contadorFormulariosCostBreakDownSimplified}" onclick="removecbd2(this)"><i class="material-icons">delete</i></button>
                </div>
            </div>
         `;

        $('#ContenedorCostbreakDownSimplified').append(nuevoFormularioCostBreakDownSimplified);
        var addSeccion = document.querySelector('.addSeccion');
        addSeccion.setAttribute('disabled', 'disabled');
    }

    function calcularTotalCbd2(deletewithcb2) {

        var n = contadorFormulariosCostBreakDownSimplified;
        var total_final_cbd = 0;
        var addSeccion = document.querySelector('.addSeccion');
        var precio_costbreakdown_simplified = document.getElementById("precio_costbreakdown_simplified").value;
        var moneda_costbreakdown = document.getElementById("moneda_costbreakdown").value;

        for (var i = 0; i <= n; i++) {
            var porcentaje_costbreakdown_simplified = parseFloat($('#porcentaje_costbreakdown_simplified' + i).val());
            total_final_cbd += porcentaje_costbreakdown_simplified;
        }

        if (deletewithcb2 !== '') {
            var porcentaje_costbreakdown_simplified = parseFloat($('#porcentaje_costbreakdown_simplified' + deletewithcb2).val());
            total_final_cbd -= porcentaje_costbreakdown_simplified;
        }

        if (total_final_cbd !== 0 && total_final_cbd !== '') {
            if (total_final_cbd == 100 && precio_costbreakdown_simplified !== '' && moneda_costbreakdown !== '') {
                addSeccion.setAttribute('disabled', 'disabled');
                $('#cargarDatosCbd2').removeAttr('disabled');
            } else {
                $('#cargarDatosCbd2').attr('disabled', 'disabled');
            }
        }

        $('#total_final_cbd').val(total_final_cbd);
    }

    function removecbd2(button) {

        var deletewithcb2 = contadorFormulariosCostBreakDownSimplified;
        calcularTotalCbd2(deletewithcb2);

        contadorFormulariosCostBreakDownSimplified--;

        $(button).closest('.row').remove();

        $('#descripcion_costbreakdown_simplified' + contadorFormulariosCostBreakDownSimplified).removeAttr('readonly');
        $('#porcentaje_costbreakdown_simplified' + contadorFormulariosCostBreakDownSimplified).removeAttr('readonly');

        if (contadorFormulariosCostBreakDownSimplified > 0) {
            $('#removecbd2' + contadorFormulariosCostBreakDownSimplified).prop('disabled', false);
        }

        var descripcion_costbreakdown_simplified = $('#descripcion_costbreakdown_simplified' + contadorFormulariosCostBreakDownSimplified).val();
        var porcentaje_costbreakdown_simplified = $('#porcentaje_costbreakdown_simplified' + contadorFormulariosCostBreakDownSimplified).val();

        if (descripcion_costbreakdown_simplified !== '' && porcentaje_costbreakdown_simplified !== '') {
            var addSeccion = document.querySelector('.addSeccion');
            addSeccion.removeAttribute('disabled');
        }
    }

    window.addEventListener('load', function() {
        changePanel(0);
    });

    function validateForm(fieldSelector) {
        var form = $(fieldSelector);
        $(form).find('.is-invalid').removeClass('is-invalid');
        $(form).find('.is-valid').removeClass('is-valid');
        $(form).find('.error-message').hide();

        var isValid = true;

        $(form).find('input, select').each(function() {
            var $this = $(this);
            if ($this.prop('required') && (!$this.val() || $this.val() === "")) {
                $this.addClass('is-invalid');
                $this.siblings('.error-message').show();
                isValid = false;
            } else {
                $this.addClass('is-valid');
            }
        });
        return isValid;
    }


    function changePanel(index) {

        var continuar = true;
        const items = document.querySelectorAll('.nav-item');
        const contentSections = document.querySelectorAll('.contenido-seccion');

        if (index == 0) {

            contentSections.forEach(section => section.style.display = 'none');

            document.getElementById('infoGeneral').style.display = 'block';
            document.querySelector('.addMateriaPrima').style.display = 'none';
            document.querySelector('.addProcesoProductivo').style.display = 'none';
            document.querySelector('.addAmortizacion').style.display = 'none';
        }
        if (index == 1) {

            if (validateForm('#form1')) {

                continuar = true;

                var formData = $('#form1').find('input, select, textarea').serialize();

                $.ajax({
                    url: '../../Actions/Supplier/registrarCostBreakDown.php',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
<<<<<<< HEAD
                        
=======

>>>>>>> 8fe25a02a378af3db1c5f09c74bddd125a144800
                        var data = JSON.parse(response);
                        if (data.success) {

                            contadorFormulariosMateriaPrima = 0;

                            contentSections.forEach(section => section.style.display = 'none');

                            document.getElementById('materiaPrima').style.display = 'block';
                            $('#ContenedorMateriaPrima').empty();

                            var formularioMateriaPrima = `
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <p class="subtitles-seccions">
                                    <?php echo $lang['TextMateriaPrima']; ?>
                                    </p>
                                    <p class="subtitles-seccions">
                                    <?php echo $lang['TextSeparadorMiles']; ?>
                                    </p>
                                </div>
                                <div class="col-md-6 mt-5">
                                    <label for="nombre_materia_prima" class="floating-label"><?php echo $lang['Nombre']; ?></label>
                                    <input required name="nombre_materia_prima[]" type="text" id="nombre_materia_prima${contadorFormulariosMateriaPrima}" onchange="materiaPrima()" class="form-control custom-input" placeholder="<?php echo $lang['Nombre de la materia prima']; ?>">
                                </div>
                                <div class="col-md-6 mt-5">
                                    <label for="moneda_unidad_materia_prima" class="floating-label" id="labelMonedaUnid${contadorFormulariosMateriaPrima}">--- / ---</label>
                                    <div class="input-group">
                                        <input required name="moneda_unidad_materia_prima[]" id="moneda_unidad_materia_prima${contadorFormulariosMateriaPrima}" onchange="materiaPrima()" oninput="validarInputNumber(this)" type="text" class="form-control custom-input" placeholder="--- / ---">
                                    </div>
                                </div>
                                <div class="col-md-6 mt-5">
                                    <label for="unidad_materia_prima" class="floating-label"><?php echo $lang['Unidad']; ?></label>
                                    <div class="input-group">
                                        <select required name="unidad_materia_prima[]" id="unidad_materia_prima${contadorFormulariosMateriaPrima}" class="form-select custom-input" onchange="materiaPrima()">
                                            <option value"" selected disabled><?php echo $lang['Ingrese la Unidad']; ?></option>
                                            <option value"Centigramo">Centigramo</option>
                                            <option value"Decagramo">Decagramo</option>
                                            <option value"Decigramo">Decigramo</option>
                                            <option value"Gramo">Gramo</option>
                                            <option value"Hectogramo">Hectogramo</option>
                                            <option value"Kilogramo">Kilogramo</option>
                                            <option value"Miligramo">Miligramo</option>
                                            <option value"N/A">N/A</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 mt-5">
                                    <label for="unidad_pieza_materia_prima" class="floating-label"><?php echo $lang['Unid_Pieza']; ?></label>
                                    <div class="input-group">
                                        <input required name="unidad_pieza_materia_prima[]" id="unidad_pieza_materia_prima${contadorFormulariosMateriaPrima}" onchange="materiaPrima()" oninput="validarInputNumber(this)" type="text" class="form-control custom-input" placeholder="<?php echo $lang['Ingrese Unid_Pieza']; ?>">
                                    </div>
                                </div>

                                <div class="col-md-5 mt-5">
                                    <label for="moneda_pieza_materia_prima" class="floating-label" id="label_moneda_pieza_materia_prima${contadorFormulariosMateriaPrima}">--- / ---</label>
                                    <div class="input-group">
                                        <input name="moneda_pieza_materia_prima[]" id="moneda_pieza_materia_prima${contadorFormulariosMateriaPrima}" onchange="materiaPrima()" type="text" class="form-control custom-input">
                                    </div>
                                </div>

                                <div class="col-md-1 mt-5">
                                    <button type="button" disabled class="btn btn-danger removemateriaprima"><i class="material-icons">delete</i></button>
                                </div>
                            </div>
                        `;

                            $('#ContenedorMateriaPrima').append(formularioMateriaPrima);
                            actualizarLabelMoneda();

                            var isconsulta = true;
                            var idPartnumberValue = $('#id_partnumber').val();

                            $.ajax({
                                url: '../../Actions/Supplier/registrarMateriaPrima.php',
                                type: 'POST',
                                data: {
                                    id_partnumber: idPartnumberValue,
                                    isconsulta: isconsulta
                                },
                                success: function(response) {
                                    var data = JSON.parse(response);
                                    if (data.success) {
                                        var contadorMateriaPrima = 0;

                                        data.materiaPrima.forEach(function(item) {
                                            if (contadorMateriaPrima > 0) {
                                                addMateriaPrima();
                                            }

                                            Object.keys(item).forEach(function(key) {
                                                var value = item[key];
                                                var newvalue = formatearValor(value);

                                                $('[id="' + key + contadorMateriaPrima + '"]').val(newvalue);

                                                if (key == 'total_moneda_pieza_materia_prima') {
                                                    $('[id="' + key + '"]').val(newvalue);
                                                }
                                            });

                                            contadorMateriaPrima++;
                                        });

                                        var addMateriaPrimaButton = document.querySelector('.addMateriaPrima');
                                        addMateriaPrimaButton.removeAttribute('disabled');
                                    }
                                }

                            });

                            $('#total_moneda_pieza_materia_prima').val('');

                            document.querySelector('.addMateriaPrima').style.display = 'block';
                            document.querySelector('.addProcesoProductivo').style.display = 'none';
                            document.querySelector('.addAmortizacion').style.display = 'none';
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Error en la solicitud AJAX: ", error);
                    }
                });

            } else {
                continuar = false;
            }
        }
        if (index == 2) {
            if (validateForm('#form2')) {

                continuar = true;

                var formData = $('#form2').find('input, select, textarea').serialize();
                var idPartnumberValue = $('#id_partnumber').val();
                formData += '&id_partnumber=' + encodeURIComponent(idPartnumberValue);

                $.ajax({
                    url: '../../Actions/Supplier/registrarMateriaPrima.php',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
<<<<<<< HEAD
                        
=======

>>>>>>> 8fe25a02a378af3db1c5f09c74bddd125a144800
                        var data = JSON.parse(response);
                        if (data.success) {
                            contadorFormulariosProcesoProductivo = 0;
                            contentSections.forEach(section => section.style.display = 'none');

                            document.getElementById('procesoProductivo').style.display = 'block';

                            $('#ContenedorProcesoProductivo').empty();

                            var formularioProcesoProductivo = `
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <p class="subtitles-seccions">
                                        <?php echo $lang['TextProcesoProductivo']; ?>
                                    </p>
                                    <p class="subtitles-seccions">
                                    <?php echo $lang['TextSeparadorMiles']; ?>
                                    </p>
                                </div>
                                
                                <div class="col-md-6 mt-5">
                                    <label for="etapa_proceso_productivo" class="floating-label"><?php echo $lang['Etapa de Proceso']; ?></label>
                                    <input required name="etapa_proceso_productivo[]" type="text" id="etapa_proceso_productivo${contadorFormulariosProcesoProductivo}" onchange="procesoProductivo()" class="form-control custom-input" placeholder="<?php echo $lang['Ingrese la Etapa del Proceso']; ?>">
                                </div>
                                <div class="col-md-6 mt-5">
                                    <label for="nombre_maquina_proceso_productivo" class="floating-label"><?php echo $lang['Nombre de la Maquina']; ?></label>
                                    <input required name="nombre_maquina_proceso_productivo[]" type="text" id="nombre_maquina_proceso_productivo${contadorFormulariosProcesoProductivo}" onchange="procesoProductivo()" class="form-control custom-input" placeholder="<?php echo $lang['Ingrese el Nombre de la Maquina']; ?>">
                                </div>
                                <div class="col-md-6 mt-5">
                                    <label for="cantidad_cavidades_proceso_productivo" class="floating-label"><?php echo $lang['Cantidad de Cavidades']; ?></label>
                                    <div class="input-group">
                                        <input required name="cantidad_cavidades_proceso_productivo[]" id="cantidad_cavidades_proceso_productivo${contadorFormulariosProcesoProductivo}" onchange="procesoProductivo()" oninput="validarInputNumber(this)" type="text" class="form-control custom-input" placeholder="<?php echo $lang['Ingrese la Cantidad de Cavidades']; ?>">
                                    </div>
                                </div>
                                <div class="col-md-6 mt-5">
                                    <label for="tiempo_ciclo_proceso_productivo" class="floating-label"><?php echo $lang['Tiempo del Ciclo']; ?></label>
                                    <div class="input-group">
                                        <input required name="tiempo_ciclo_proceso_productivo[]" id="tiempo_ciclo_proceso_productivo${contadorFormulariosProcesoProductivo}" onchange="procesoProductivo()" oninput="validarInputNumber(this)" type="text" class="form-control custom-input" placeholder="<?php echo $lang['Ingrese el Tiempo del Ciclo']; ?>">
                                    </div>
                                </div>
                                <div class="col-md-6 mt-5">
                                    <label for="eficiencia_proceso_productivo" class="floating-label"><?php echo $lang['Eficiencia']; ?></label>
                                    <div class="input-group">
                                        <input required name="eficiencia_proceso_productivo[]" id="eficiencia_proceso_productivo${contadorFormulariosProcesoProductivo}" onchange="procesoProductivo()" oninput="validarPorcentaje(this)" type="text" class="form-control custom-input" placeholder="<?php echo $lang['Ingrese la Eficiencia']; ?>">
                                    </div>
                                </div>

                                <div class="col-md-6 mt-5">
                                    <label for="costo_maquina_hora_proceso_productivo" class="floating-label" id="labelCostoMaquinaHora${contadorFormulariosProcesoProductivo}">---</label>
                                    <div class="input-group">
                                        <input required name="costo_maquina_hora_proceso_productivo[]" id="costo_maquina_hora_proceso_productivo${contadorFormulariosProcesoProductivo}" onchange="procesoProductivo()" oninput="validarInputNumber(this)" type="text" class="form-control custom-input" placeholder="---">
                                    </div>
                                </div>

                                <div class="col-md-6 mt-5">
                                    <label for="cantidad_mano_obra_directa_proceso_productivo" class="floating-label"><?php echo $lang['CantidadManoObraDir']; ?></label>
                                    <div class="input-group">
                                        <input required name="cantidad_mano_obra_directa_proceso_productivo[]" id="cantidad_mano_obra_directa_proceso_productivo${contadorFormulariosProcesoProductivo}" onchange="procesoProductivo()" oninput="validarInputNumber(this)" type="text" class="form-control custom-input" placeholder="<?php echo $lang['IngreseCantidadManoObraDir']; ?>">
                                    </div>
                                </div>

                                <div class="col-md-6 mt-5">
                                    <label for="mano_obra_directa_proceso_productivo" class="floating-label" id="labelManoObraDirecta${contadorFormulariosProcesoProductivo}">---</label>
                                    <div class="input-group">
                                        <input required name="mano_obra_directa_proceso_productivo[]" id="mano_obra_directa_proceso_productivo${contadorFormulariosProcesoProductivo}" onchange="procesoProductivo()" oninput="validarInputNumber(this)" type="text" class="form-control custom-input" placeholder="---">
                                    </div>
                                </div>

                                <div class="col-md-6 mt-5">
                                    <label for="tiempo_setup_proceso_productivo" class="floating-label"><?php echo $lang['TiempoSetUp']; ?></label>
                                    <div class="input-group">
                                        <input required name="tiempo_setup_proceso_productivo[]" id="tiempo_setup_proceso_productivo${contadorFormulariosProcesoProductivo}" onchange="procesoProductivo()" oninput="validarInputNumber(this)" type="text" class="form-control custom-input" placeholder="<?php echo $lang['IngreseTiempoSetUp']; ?>">
                                    </div>
                                </div>

                                <div class="col-md-6 mt-5">
                                    <label for="costo_setup_hora_proceso_productivo" class="floating-label" id="labelCostoSetUp${contadorFormulariosProcesoProductivo}">---</label>
                                    <div class="input-group">
                                        <input required name="costo_setup_hora_proceso_productivo[]" id="costo_setup_hora_proceso_productivo${contadorFormulariosProcesoProductivo}" onchange="procesoProductivo()" oninput="validarInputNumber(this)" type="text" class="form-control custom-input" placeholder="---">
                                    </div>
                                </div>

                                <div class="col-md-6 mt-5">
                                    <label for="lote_setup_proceso_productivo" class="floating-label"><?php echo $lang['LoteSetUp']; ?></label>
                                    <div class="input-group">
                                        <input required name="lote_setup_proceso_productivo[]" id="lote_setup_proceso_productivo${contadorFormulariosProcesoProductivo}" onchange="procesoProductivo()" oninput="validarInputNumber(this)" type="text" class="form-control custom-input" placeholder="<?php echo $lang['IngreseLoteSetUp']; ?>">
                                    </div>
                                </div>

                                <div class="col-md-6 mt-5">
                                    <label for="costo_final_maquina_proceso_productivo" class="floating-label" id="labelCostoFinalMaquina${contadorFormulariosProcesoProductivo}">---</label>
                                    <div class="input-group">
                                    <input readonly name="costo_final_maquina_proceso_productivo[]" id="costo_final_maquina_proceso_productivo${contadorFormulariosProcesoProductivo}" type="text" class="form-control custom-input">
                                    </div>
                                </div>

                                <div class="col-md-6 mt-5">
                                    <label for="mano_obra_directa_final_proceso_productivo" class="floating-label" id="labelManoObraDirectaFinal${contadorFormulariosProcesoProductivo}">---</label>
                                    <div class="input-group">
                                        <input readonly name="mano_obra_directa_final_proceso_productivo[]" id="mano_obra_directa_final_proceso_productivo${contadorFormulariosProcesoProductivo}" type="text" class="form-control custom-input">
                                    </div>
                                </div>

                                <div class="col-md-6 mt-5">
                                    <label for="costo_final_setup_hora_proceso_productivo" class="floating-label" id="labelCostoFinalSetup${contadorFormulariosProcesoProductivo}">---</label>
                                    <div class="input-group">
                                        <input readonly name="costo_final_setup_hora_proceso_productivo[]" id="costo_final_setup_hora_proceso_productivo${contadorFormulariosProcesoProductivo}" onchange="procesoProductivo()" type="text" class="form-control custom-input">
                                    </div>
                                </div>

                                <div class="col-md-5 mt-5">
                                    <label for="maquina_mano_obra_directa_setup_proceso_productivo" class="floating-label" id="labelMaquinaManoObraDirectaSetup${contadorFormulariosProcesoProductivo}">---</label>
                                    <div class="input-group">
                                        <input readonly name="maquina_mano_obra_directa_setup_proceso_productivo[]" id="maquina_mano_obra_directa_setup_proceso_productivo${contadorFormulariosProcesoProductivo}" onchange="procesoProductivo()" type="text" class="form-control custom-input">
                                    </div>
                                </div>

                                <div class="col-md-1 mt-5">
                                    <button type="button" disabled class="btn btn-danger removeprocesoproductivo"><i class="material-icons">delete</i></button>
                                </div>
                            </div>
                    
                            `;

                            $('#ContenedorProcesoProductivo').append(formularioProcesoProductivo);
                            actualizarLabelMoneda();

                            var isconsulta = true;
                            var idPartnumberValue = $('#id_partnumber').val();

                            $.ajax({
                                url: '../../Actions/Supplier/registrarProcesoProductivo.php',
                                type: 'POST',
                                data: {
                                    id_partnumber: idPartnumberValue,
                                    isconsulta: isconsulta
                                },
                                success: function(response) {
                                    var data = JSON.parse(response);
                                    if (data.success) {
                                        var contadorProcesoProductivo = 0;

                                        data.procesoProductivo.forEach(function(item) {
                                            if (contadorProcesoProductivo > 0) {
                                                addProcesoProductivo();
                                            }

                                            Object.keys(item).forEach(function(key) {
                                                var value = item[key];
                                                var newvalue = formatearValor(value);

                                                $('[id="' + key + contadorProcesoProductivo + '"]').val(newvalue);

                                                if (key == 'total_moneda_pieza_costo_maquina') {
                                                    $('[id="' + key + '"]').val(newvalue);
                                                }
                                                if (key == 'total_moneda_pieza_mano_obra_directa') {
                                                    $('[id="' + key + '"]').val(newvalue);
                                                }
                                                if (key == 'total_moneda_pieza_costo_setup') {
                                                    $('[id="' + key + '"]').val(newvalue);
                                                }
                                            });

                                            contadorProcesoProductivo++;
                                        });

                                        var addProcesoProductivoButton = document.querySelector('.addProcesoProductivo');
                                        addProcesoProductivoButton.removeAttribute('disabled');
                                    }
                                }

                            });

                            document.querySelector('.addMateriaPrima').style.display = 'none';
                            document.querySelector('.addProcesoProductivo').style.display = 'block';
                            document.querySelector('.addAmortizacion').style.display = 'none';
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Error en la solicitud AJAX: ", error);
                    }
                });
            } else {
                continuar = false;
            }
        }
        if (index == 3) {

            if (validateForm('#form3')) {

                continuar = true;

                var formData = $('#form3').find('input, select, textarea').serialize();
                var idPartnumberValue = $('#id_partnumber').val();
                formData += '&id_partnumber=' + encodeURIComponent(idPartnumberValue);

                $.ajax({
                    url: '../../Actions/Supplier/registrarProcesoProductivo.php',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
<<<<<<< HEAD
                        
=======

>>>>>>> 8fe25a02a378af3db1c5f09c74bddd125a144800
                        var data = JSON.parse(response);
                        if (data.success) {

                            contentSections.forEach(section => section.style.display = 'none');
                            document.getElementById('amortizacion').style.display = 'block';

                            $('#ContenedorAmortizacion').empty();

                            var formularioAmortizacion = `

                            <div class="row mt-2">
                                <p class="subtitles-seccions">
                                    <?php echo $lang['TextAmortizacion']; ?>
                                </p>
                                <p class="subtitles-seccions">
                                <?php echo $lang['TextSeparadorMiles']; ?>
                                </p>
                                <div class="col-md-6 mt-5">
                                    <label for="descripcion_amortizacion" class="floating-label"><?php echo $lang['Descripcion Amortizacion']; ?></label>
                                    <input required name="descripcion_amortizacion[]" type="text" id="descripcion_amortizacion${contadorFormulariosAmortizacion}" onchange="amortizacion()" class="form-control custom-input" placeholder="<?php echo $lang['Ingrese Descripcion Amortizacion']; ?>">
                                </div>
                                <div class="col-md-6 mt-5">
                                    <label for="inversion_amortizacion" class="floating-label" id="label_inversion_amortizacion${contadorFormulariosAmortizacion}">---</label>
                                    <input required name="inversion_amortizacion[]" type="text" id="inversion_amortizacion${contadorFormulariosAmortizacion}" onchange="amortizacion()" oninput="validarInputNumber(this)" class="form-control custom-input" placeholder="---">
                                </div>
                                <div class="col-md-6 mt-5">
                                    <label for="piezas_amortizadas" class="floating-label"><?php echo $lang['PiezasAmortizadas']; ?></label>
                                    <input required name="piezas_amortizadas[]" type="text" id="piezas_amortizadas${contadorFormulariosAmortizacion}" onchange="amortizacion()" oninput="validarInputNumber(this)" class="form-control custom-input" placeholder="<?php echo $lang['IngresePiezasAmortizadas']; ?>">
                                </div>
                                <div class="col-md-5 mt-5">
                                    <label for="moneda_pieza_amortizacion" class="floating-label" id="label_moneda_pieza_Amortizacion${contadorFormulariosAmortizacion}">--- / ---</label>
                                    <input readonly name="moneda_pieza_amortizacion[]" type="text" id="moneda_pieza_amortizacion${contadorFormulariosAmortizacion}" onchange="amortizacion()" class="form-control custom-input">
                                </div>
                                <div class="col-md-1 mt-5">
                                    <button type="button" disabled class="btn btn-danger removeamortizacion"><i class="material-icons">delete</i></button>
                                </div>
                            </div>
                        `;

                            $('#ContenedorAmortizacion').append(formularioAmortizacion);
                            actualizarLabelMoneda();

                            var isconsulta = true;
                            var idPartnumberValue = $('#id_partnumber').val();

                            $.ajax({
                                url: '../../Actions/Supplier/registrarAmortizacion.php',
                                type: 'POST',
                                data: {
                                    id_partnumber: idPartnumberValue,
                                    isconsulta: isconsulta
                                },
                                success: function(response) {
                                    var data = JSON.parse(response);
                                    if (data.success) {
                                        var contadorAmortizacion = 0;

                                        data.amortizacion.forEach(function(item) {
                                            if (contadorAmortizacion > 0) {
                                                addAmortizacion();
                                            }

                                            Object.keys(item).forEach(function(key) {
                                                var value = item[key];
                                                var newvalue = formatearValor(value);

                                                $('[id="' + key + contadorAmortizacion + '"]').val(newvalue);

                                                if (key == 'total_moneda_pieza_amortizacion') {
                                                    $('[id="' + key + '"]').val(newvalue);
                                                }
                                            });

                                            contadorAmortizacion++;
                                        });

                                        var addAmortizacionButton = document.querySelector('.addAmortizacion');
                                        addAmortizacionButton.removeAttribute('disabled');
                                    }
                                }

                            });

                            document.querySelector('.addMateriaPrima').style.display = 'none';
                            document.querySelector('.addProcesoProductivo').style.display = 'none';
                            document.querySelector('.addAmortizacion').style.display = 'block';
                        }
                    }
                });
            } else {
                continuar = false;
            }
        }
        if (index == 4) {
            if (validateForm('#form4')) {

                continuar = true;

                var formData = $('#form4').find('input, select, textarea').serialize();
                var idPartnumberValue = $('#id_partnumber').val();
                formData += '&id_partnumber=' + encodeURIComponent(idPartnumberValue);


                $.ajax({
                    url: '../../Actions/Supplier/registrarAmortizacion.php',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
<<<<<<< HEAD
                        
=======

>>>>>>> 8fe25a02a378af3db1c5f09c74bddd125a144800
                        var data = JSON.parse(response);
                        if (data.success) {

                            contadorFormulariosAmortizacion = 0;

                            contentSections.forEach(section => section.style.display = 'none');
                            document.getElementById('costoEmbalaje').style.display = 'block';

                            var isconsulta = true;
                            var idPartnumberValue = $('#id_partnumber').val();

                            $.ajax({
                                url: '../../Actions/Supplier/registrarEmbalaje.php',
                                type: 'POST',
                                data: {
                                    id_partnumber: idPartnumberValue,
                                    isconsulta: isconsulta
                                },
                                success: function(response) {
<<<<<<< HEAD
                                    
=======

>>>>>>> 8fe25a02a378af3db1c5f09c74bddd125a144800
                                    var data = JSON.parse(response);
                                    if (data.success) {

                                        if (data.embalaje != null) {
                                            var moneda_pieza_embalaje = formatearValor(data.embalaje.moneda_pieza_embalaje);
                                            $('#moneda_pieza_embalaje').val(moneda_pieza_embalaje);
                                        }
                                    }
                                }
                            });

                            actualizarLabelMoneda();

                            document.querySelector('.addMateriaPrima').style.display = 'none';
                            document.querySelector('.addProcesoProductivo').style.display = 'none';
                            document.querySelector('.addAmortizacion').style.display = 'none';
                        }
                    }
                });
            } else {
                continuar = false;
            }
        }
        if (index == 5) {

            if (validateForm('#form5')) {

                continuar = true;

                var formData = $('#form5').find('input, select, textarea').serialize();
                var idPartnumberValue = $('#id_partnumber').val();
                formData += '&id_partnumber=' + encodeURIComponent(idPartnumberValue);

                $.ajax({
                    url: '../../Actions/Supplier/registrarEmbalaje.php',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
<<<<<<< HEAD
                        
=======

>>>>>>> 8fe25a02a378af3db1c5f09c74bddd125a144800
                        var data = JSON.parse(response);
                        if (data.success) {
                            contentSections.forEach(section => section.style.display = 'none');

                            document.getElementById('scrap').style.display = 'block';

                            var isconsulta = true;
                            var idPartnumberValue = $('#id_partnumber').val();

                            $.ajax({
                                url: '../../Actions/Supplier/registrarScrap.php',
                                type: 'POST',
                                data: {
                                    id_partnumber: idPartnumberValue,
                                    isconsulta: isconsulta
                                },
                                success: function(response) {
<<<<<<< HEAD
                                    
=======

>>>>>>> 8fe25a02a378af3db1c5f09c74bddd125a144800
                                    var data = JSON.parse(response);
                                    if (data.success) {

                                        if (data.scrap != null) {
                                            var porcentaje_scrap = formatearValor(data.scrap.porcentaje_scrap);
                                            $('#porcentaje_scrap').val(porcentaje_scrap);
                                            var moneda_pieza_scrap = formatearValor(data.scrap.moneda_pieza_scrap);
                                            $('#moneda_pieza_scrap').val(moneda_pieza_scrap);
                                        }
                                    }
                                }
                            });

                            actualizarLabelMoneda();

                            document.querySelector('.addMateriaPrima').style.display = 'none';
                            document.querySelector('.addProcesoProductivo').style.display = 'none';
                            document.querySelector('.addAmortizacion').style.display = 'none';

                        }
                    }
                });
            } else {
                continuar = false;
            }
        }
        if (index == 6) {

            if (validateForm('#form6')) {
                
                continuar = true;

                var formData = $('#form6').find('input, select, textarea').serialize();

                var idPartnumberValue = $('#id_partnumber').val();
                formData += '&id_partnumber=' + encodeURIComponent(idPartnumberValue);

                $.ajax({
                    url: '../../Actions/Supplier/registrarScrap.php',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
<<<<<<< HEAD
                        
=======

>>>>>>> 8fe25a02a378af3db1c5f09c74bddd125a144800
                        var data = JSON.parse(response);
                        if (data.success) {

                            contentSections.forEach(section => section.style.display = 'none');

                            document.getElementById('markup').style.display = 'block';

                            var isconsulta = true;
                            var idPartnumberValue = $('#id_partnumber').val();

                            $.ajax({
                                url: '../../Actions/Supplier/registrarMarkup.php',
                                type: 'POST',
                                data: {
                                    id_partnumber: idPartnumberValue,
                                    isconsulta: isconsulta
                                },
                                success: function(response) {
<<<<<<< HEAD
                                    
=======

>>>>>>> 8fe25a02a378af3db1c5f09c74bddd125a144800
                                    var data = JSON.parse(response);
                                    if (data.success) {

                                        if (data.markup != null) {

                                            for (let key in data.markup) {
                                                if (data.markup.hasOwnProperty(key)) {
                                                    var value = data.markup[key];
                                                    var newvalue = formatearValor(value);
                                                    $('[id="' + key + '"]').val(newvalue);
                                                }
                                            }
                                        }
                                    }
                                }
                            });
                        } else {
                            continuar = false;
                        }
                    }
                });

            }
            actualizarLabelMoneda();

            document.querySelector('.addMateriaPrima').style.display = 'none';
            document.querySelector('.addProcesoProductivo').style.display = 'none';
            document.querySelector('.addAmortizacion').style.display = 'none';
        }

        if (index == 7) {

            if (validateForm('#form7')) {

                calcularPrecioNetoTotal();

                continuar = true;

                var porcentaje_final_materia_prima = $('#porcentaje_final_materia_prima').val();
                var porcentaje_final_moneda_pieza_costo_maquina = $('#porcentaje_final_moneda_pieza_costo_maquina').val();
                var porcentaje_final_moneda_pieza_mano_obra_directa = $('#porcentaje_final_moneda_pieza_mano_obra_directa').val();
                var porcentaje_final_moneda_pieza_costo_setup = $('#porcentaje_final_moneda_pieza_costo_setup').val();

                var formData = $('#form7').find('input, select, textarea').add($('#resumen').find('input')).serialize();
                var moneda_pieza_flete_valor = $('#moneda_pieza_flete').val().replace(',', '.');
                var moneda_pieza_SGA_valor = $('#moneda_pieza_SGA').val().replace(',', '.');
                var moneda_pieza_margen_beneficio_valor = $('#moneda_pieza_margen_beneficio').val().replace(',', '.');

                formData += '&moneda_pieza_flete=' + encodeURIComponent(moneda_pieza_flete_valor);
                formData += '&moneda_pieza_SGA=' + encodeURIComponent(moneda_pieza_SGA_valor);
                formData += '&moneda_pieza_margen_beneficio=' + encodeURIComponent(moneda_pieza_margen_beneficio_valor);

                var idPartnumberValue = $('#id_partnumber').val();
                formData += '&id_partnumber=' + encodeURIComponent(idPartnumberValue);
                formData += '&porcentaje_final_materia_prima=' + encodeURIComponent(porcentaje_final_materia_prima);
                formData += '&porcentaje_final_moneda_pieza_costo_maquina=' + encodeURIComponent(porcentaje_final_moneda_pieza_costo_maquina);
                formData += '&porcentaje_final_moneda_pieza_mano_obra_directa=' + encodeURIComponent(porcentaje_final_moneda_pieza_mano_obra_directa);
                formData += '&porcentaje_final_moneda_pieza_costo_setup=' + encodeURIComponent(porcentaje_final_moneda_pieza_costo_setup);

                console.log(formData);

                $.ajax({
                    url: '../../Actions/Supplier/registrarMarkup.php',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
<<<<<<< HEAD
                        
=======

>>>>>>> 8fe25a02a378af3db1c5f09c74bddd125a144800
                        var data = JSON.parse(response);
                        if (data.success) {

                            contentSections.forEach(section => section.style.display = 'none');

                            document.getElementById('resumen').style.display = 'block';
                            document.querySelector('.addMateriaPrima').style.display = 'none';
                            document.querySelector('.addProcesoProductivo').style.display = 'none';
                            document.querySelector('.addAmortizacion').style.display = 'none';
                        }
                    }
                });
            } else {
                continuar = false;
            }
        }

        if (continuar) {

            const connector = document.querySelector('.progress');

            const numItems = items.length;
            var percentage = index * (100 / (numItems - 1)) + (50 / (numItems - 1));

            if (index === numItems - 1) {
                percentage = 100;
            }

            connector.style.width = `${percentage}%`;

            items.forEach(item => item.classList.remove('active'));
            items[index].classList.add('active');
        }
    }

    function finalizarcbd() {
        <?php

        if ($_SESSION['lang'] == "Es") {
        ?>
            var partnumber = $("select[name='id_partnumber']").val();
            var desc_partnumber = $('#descripcion_partnumber').val();
            Swal.fire({
                icon: "success",
                title: "Registro Exitoso",
                text: "Se ha registrado el Cost Breakdown para el Part Number " + partnumber + " - " + desc_partnumber,
                showConfirmButton: false,
                timer: 3000,
                allowOutsideClick: false
            });
            setTimeout(function() {
                window.location.href = "costBreakDown.php";
            }, 3000);
        <?php
        }
        if ($_SESSION['lang'] == "En") {
        ?>
            var partnumber = $("select[name='id_partnumber']").val();
            var desc_partnumber = $('#descripcion_partnumber').val();
            Swal.fire({
                icon: "success",
                title: "Successful Register",
                text: "Cost Breakdown has been registered for the Part Number " + partnumber + " - " + desc_partnumber,
                showConfirmButton: false,
                timer: 3000,
                allowOutsideClick: false
            });
            setTimeout(function() {
                window.location.href = "costBreakDown.php";
            }, 3000);
        <?php
        }
        ?>
    }
</script>

</html>