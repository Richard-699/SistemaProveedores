<?php
session_start();
$ruta = '../../IdiomaConfig/' . $_SESSION['lang'] . '.php';
include($ruta);
include '../spinner.html';
if (empty($_SESSION["id_rol_usuarios"])) {
    header("Location:../../index.php");
}
if ($_SESSION["id_rol_usuarios"] != 3) {
    header("Location:../../index.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../../Estilos/Laft/estilos_index.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="shortcut icon" href="../../img/LogoBlanco.png" type="image/x-icon">
    <link rel="stylesheet" href="../../Estilos/Generals/estilos_nav_laft.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.5/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.5/dist/sweetalert2.min.js"></script>
    <script src="../../Js/Laft/index.js"></script>
    <title>LAFT</title>

    <div class="franja">
        <div class="contenedor-img">
            <img class="img-Laft" src="../../img/LogoBlanco.png">
        </div>
        <div class="datos-sesion">
            <i class="material-icons icon-nav">person</i>
            <h2 class="usuario"><?php echo $_SESSION["nombre_proveedor"]; ?></h2>
        </div>
    </div>

</head>

<body>
    <div id="content">

    </div>
</body>

</html>