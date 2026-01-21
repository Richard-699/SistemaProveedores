<html>
    <head>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.5/dist/sweetalert2.min.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.5/dist/sweetalert2.min.js"></script>
    </head>
</html>
<?php

include("../../ConexionBD/conexion.php");

$id_usuario=$_GET["id_usuario"];
$eliminarUsuario = mysqli_query($conexion, "DELETE FROM usuarios where id_usuario='$id_usuario'");
$eliminarProveedor = mysqli_query($conexion, "DELETE FROM proveedores where numero_acreedor='$id_usuario'");
$eliminarOpcionesCostBreakDownSimplified = mysqli_query($conexion, "DELETE FROM options_costbreakdown_simplified where id_proveedor_options_costbreakdown_simplified='$id_usuario'");

if ($eliminarUsuario && $eliminarProveedor && $eliminarOpcionesCostBreakDownSimplified) {
    // Registro exitoso, muestra la notificación y redirecciona
    echo '<script>';
    echo 'Swal.fire({
                icon: "success",
                title: "Solicitud Rechazada!",
                text: "Se eliminó la solicitud de registro.",
                showConfirmButton: false,
                timer: 2000
            });';
    echo 'setTimeout(function() { window.location.href = "../../Views/Admin"; }, 2000);'; // Redirige después de 3 segundos
    echo '</script>';
}else{
    echo '<script>';
    echo 'Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "No se pudo rechazar la solicitud, intenta de nuevo...",
                showConfirmButton: false,
                timer: 2000
            });';
    echo 'setTimeout(function() { window.location.href = "../../Views/Admin/gestionAccesos.php"; }, 2000);'; // Redirige después de 3 segundos
    echo '</script>';
}
?>