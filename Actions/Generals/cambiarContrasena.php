<?php
session_start();
include '../../ConexionBD/conexion.php';
/** @var mysqli $conexion */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.5/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.5/dist/sweetalert2.min.js"></script>
</head>
<body>
<?php
// Validar que exista una sesión activa
if (empty($_SESSION['id_usuario'])) {
    echo '<script>
        Swal.fire({
            icon: "warning",
            title: "Sesión expirada",
            text: "Por favor, inicia sesión nuevamente.",
            showConfirmButton: true
        }).then(() => {
            window.location.href = "../../index.php";
        });
    </script>
    </body></html>';
    exit;
}

// Validar que se haya recibido la contraseña
$new_password = trim($_POST['new_password'] ?? '');
if (empty($new_password)) {
    echo '<script>
        Swal.fire({
            icon: "error",
            title: "Campo requerido",
            text: "Por favor ingresa tu nueva contraseña.",
            showConfirmButton: true
        }).then(() => {
            window.location.href = "../../cambiarContrasenaProveedor.php";
        });
    </script>
    </body></html>';
    exit;
}

$id_usuario = (int)$_SESSION['id_usuario'];
$hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);
$is_temporal = 0;

// Preparación orientada a objetos (coherente con el resto del proyecto)
$updateContrasena = $conexion->prepare("UPDATE usuarios SET password_usuario = ?, is_temporal = ? WHERE Id_usuario = ?");

if (!$updateContrasena) {
    echo '<script>
        Swal.fire({
            icon: "error",
            title: "Error de servidor",
            text: "No se pudo preparar la actualización de la contraseña.",
            showConfirmButton: true
        }).then(() => {
            window.location.href = "../../cambiarContrasenaProveedor.php";
        });
    </script>
    </body></html>';
    exit;
}

$updateContrasena->bind_param("sii", $hashedPassword, $is_temporal, $id_usuario);
$ejecucionExitosa = $updateContrasena->execute();

$Id_proveedor = null;
$consultaAccesos = $conexion->prepare("SELECT u.Id_usuario, p.Id_proveedor, p.proveedor_aprobado 
                                        FROM usuarios u 
                                        LEFT JOIN proveedores p ON u.id_proveedor_usuarios = p.Id_proveedor
                                        WHERE u.Id_usuario = ?");

if ($consultaAccesos) {
    $consultaAccesos->bind_param("i", $id_usuario);
    $consultaAccesos->execute();
    $resultado = $consultaAccesos->get_result();
    if ($resultado && $resultado->num_rows > 0) {
        $row = $resultado->fetch_assoc();
        $Id_proveedor = $row['Id_proveedor'];
    }
}

if ($ejecucionExitosa) {
    echo '<script>
        Swal.fire({
            icon: "success",
            title: "Cambio Exitoso",
            text: "Tu contraseña ha sido cambiada con éxito.",
            showConfirmButton: false,
            timer: 2000
        });';
    if (isset($_SESSION["id_rol_usuarios"]) && (int)$_SESSION["id_rol_usuarios"] === 1) {
        echo 'setTimeout(function() { window.location.href = "../../Views/Admin/index.php"; }, 2000);';
    } elseif ($Id_proveedor === null) {
        echo 'setTimeout(function() { window.location.href = "../../Views/User/index.php"; }, 2000);';
    } else {
        echo 'setTimeout(function() { window.location.href = "../../Views/Supplier/index.php"; }, 2000);';
    }
    echo '</script>';
} else {
    echo '<script>
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Tu contraseña no se cambió correctamente, intenta de nuevo...",
            showConfirmButton: false,
            timer: 2000
        });
        setTimeout(function() { window.location.href = "../../cambiarContrasenaProveedor.php"; }, 2000);
    </script>';
}
?>
</body>
</html>
