<?php
session_start();
include '../../ConexionBD/conexion.php';
?>
<html>
    <head>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.5/dist/sweetalert2.min.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.5/dist/sweetalert2.min.js"></script>
    </head>
</html>

<?php
$id_usuario = $_SESSION['id_usuario'];
$new_password = $_POST['new_password'];
$hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);

$updateContrasena = mysqli_prepare($conexion, "UPDATE usuarios SET password_usuario = ?, is_temporal = ? WHERE Id_usuario = ?");

$is_temporal = false;

mysqli_stmt_bind_param(
    $updateContrasena,
    "sii",
    $hashedPassword,
    $is_temporal,
    $id_usuario
);

mysqli_stmt_execute($updateContrasena);

$consultaAccesos = $conexion->prepare("SELECT * FROM usuarios 
                                        LEFT JOIN proveedores ON usuarios.id_proveedor_usuarios = proveedores.Id_proveedor
                                        WHERE Id_usuario = ?");
$consultaAccesos->bind_param("i", $id_usuario);
$consultaAccesos->execute();
$resultado = $consultaAccesos->get_result();

if ($resultado && $resultado->num_rows > 0) {
    $row = $resultado->fetch_assoc();
    $Id_proveedor = $row['Id_proveedor'];
    $proveedor_aprobado = $row['proveedor_aprobado'];
} else {
    $Id_laft = null;
}


if($updateContrasena){

    echo '<script>';
    echo 'Swal.fire({
                icon: "success",
                title: "Cambio Exitoso",
                text: "Tu contraseña ha sido cambiada con éxito.",
                showConfirmButton: false,
                timer: 2000 // Cierra automáticamente después de 3 segundos
            });';
    if($Id_proveedor == null){
        echo 'setTimeout(function() { window.location.href = "../../Views/User/index.php"; }, 2000);';
    }else{
        if($proveedor_aprobado == 1){
            echo 'setTimeout(function() { window.location.href = "../../Views/Supplier/index.php"; }, 2000);';
        }else{
            echo 'setTimeout(function() { window.location.href = "../../Views/Laft/index.php"; }, 2000);';
        }
    }

    
    echo '</script>';
}else{
    echo '<script>';
    echo 'Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Tu contraseña no se cambió correctamente, intenta de nuevo...",
                showConfirmButton: false,
                timer: 2000 // Cierra automáticamente después de 3 segundos
            });';
    echo 'setTimeout(function() { window.location.href = "../../cambiarContrasenaProveedor.php"; }, 2000);';
    echo '</script>';
}
?>
