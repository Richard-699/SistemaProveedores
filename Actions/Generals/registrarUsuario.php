<html>
    <head>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.5/dist/sweetalert2.min.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.5/dist/sweetalert2.min.js"></script>
    </head>
</html>
<?php

include("../../ConexionBD/conexion.php");
$nombre_usuario = $_POST['nombre_usuario'];
$apellidos_usuario = $_POST['apellidos_usuario'];
$usuario = $_POST['usuario'];
$password = $_POST['password'];
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
$id_area_usuario = $_POST['id_area_usuario'];
$rol_usuario = '2';
$estado_registro = '0';
$is_temporal = false;

$insertarUsuario = mysqli_query($conexion, "INSERT INTO usuarios
    (nombre_usuario, apellidos_usuario, correo_usuario, password_usuario, Id_area_usuario, id_proveedor_usuarios,
     id_rol_usuarios, estado_registro, is_temporal) 
    VALUES ('$nombre_usuario', '$apellidos_usuario', '$usuario', '$hashedPassword', '$id_area_usuario', NULL, '$rol_usuario', '$estado_registro', '$is_temporal')");

if ($insertarUsuario) {
    echo '<script>';
    echo 'Swal.fire({
                icon: "warning",
                title: "Registro exitoso",
                text: "Tu registro ha sido completado con éxito, debes esperar aprobación del administrador.",
                showConfirmButton: false,
                timer: 5000,
                allowOutsideClick: false
            });';
    echo 'setTimeout(function() { window.location.href = "../../index.php"; }, 5000);';
    echo '</script>';
}else{
    echo '<script>';
    echo 'Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Tu registro no se guardó correctamente, intenta de nuevo...",
                showConfirmButton: false,
                timer: 2000 // Cierra automáticamente después de 3 segundos
            });';
    echo 'setTimeout(function() { window.location.href = "../../registro.php"; }, 2000);';
    echo '</script>';
}
?>