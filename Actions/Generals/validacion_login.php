<?php
session_start();
ob_start();
include "../../ConexionBD/conexion.php";
?>
<html>
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.5/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.5/dist/sweetalert2.min.js"></script>
</head>
</html>
<?php
if (!empty($_POST["btningresar"])) {
    if (!empty($_POST["usuario"]) and !empty($_POST["password"])) {
        $usuario = $_POST["usuario"];
        $password = $_POST["password"];
        $consultarUsuario = mysqli_query($conexion, "SELECT * FROM usuarios 
                                        LEFT JOIN proveedores ON usuarios.id_proveedor_usuarios = proveedores.Id_proveedor
                                        WHERE correo_usuario='$usuario'");
        if (mysqli_num_rows($consultarUsuario) == 0) {
            echo '<script>';
            echo 'Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Usuario Incorrecto, ingresa nuevamente...",
                        showConfirmButton: false,
                        timer: 2000
                    });';
            echo 'setTimeout(function() { window.location.href = "../../index.php"; }, 2000);'; // Redirige después de 3 segundos
            echo '</script>';
        } else {
            $arrayConsulta = mysqli_fetch_array($consultarUsuario);
            $passwordBD = ($arrayConsulta['password_usuario']);
            if (password_verify($password, $passwordBD)) {
                $estado_registro = ($arrayConsulta['estado_registro']);
                if ($estado_registro) {
                    $_SESSION["proveedor_aprobado"] = $arrayConsulta['proveedor_aprobado'];
                    $_SESSION["id_usuario"] = $arrayConsulta['Id_usuario'];
                    $_SESSION["correo_usuario"] = $arrayConsulta['correo_usuario'];
                    $_SESSION["id_proveedor_usuarios"] = $arrayConsulta['id_proveedor_usuarios'];
                    $_SESSION["id_rol_usuarios"] = $arrayConsulta['id_rol_usuarios'];
                    $_SESSION['lang'] = $arrayConsulta['idioma_proveedor'];
                    $_SESSION['nombre_proveedor'] = $arrayConsulta['nombre_proveedor'];
                    $_SESSION['numero_acreedor'] = $arrayConsulta['numero_acreedor'];
                    $_SESSION['nombre_usuario'] = $arrayConsulta['nombre_usuario'];
                    $_SESSION['maneja_formato_costbreakdown'] = $arrayConsulta['maneja_formato_costbreakdown'];
                    $_SESSION['Id_area_usuario'] = $arrayConsulta['Id_area_usuario'];
                    $_SESSION['tipo_proveedor'] = $arrayConsulta['tipo_proveedor'];
                    $_SESSION['correo_negociador'] = $arrayConsulta['correo_negociador'];
                    $_SESSION['carta_beneficiarios_finales'] = $arrayConsulta['carta_beneficiarios_finales'];
                    $is_temporal = $arrayConsulta['is_temporal'];
                    if ($is_temporal) {
                        header("Location: ../../cambiarContraseñaProveedor.php");
                    } else {
                        if ($_SESSION["id_rol_usuarios"] == 1) {
                            header("Location:../../Views/Admin/index.php");
                        }
                        if ($_SESSION["id_rol_usuarios"] == 2) {
                            header("Location:../../Views/User/index.php");
                        }
                        if ($_SESSION["id_rol_usuarios"] == 3 && !$_SESSION["proveedor_aprobado"]) {
                            header("Location:../../Views/Laft/index.php");
                        } else if ($_SESSION["id_rol_usuarios"] == 3 && $_SESSION["proveedor_aprobado"]) {
                            $Id_proveedor_laft = $_SESSION["id_proveedor_usuarios"];
                            $consultaLaft = $conexion->prepare("SELECT * FROM laft WHERE Id_proveedor_laft = ?");
                            $consultaLaft->bind_param("s", $Id_proveedor_laft);
                            $consultaLaft->execute();
                            $resultado = $consultaLaft->get_result();
                            if ($resultado && $resultado->num_rows > 0) {
                                $row = $resultado->fetch_assoc();
                                $ultima_actualizacion_laft_str = $row['ultima_actualizacion_laft'];
                                $ultima_actualizacion_laft = new DateTime($ultima_actualizacion_laft_str);
                            } else {
                                $ultima_actualizacion_laft = null;
                            }
                            if ($ultima_actualizacion_laft != null) {
                                date_default_timezone_set('America/Bogota');
                                $fecha_actual = new DateTime();
                                $ultima_actualizacion_laft->modify('+1 year');
                                if ($fecha_actual >= $ultima_actualizacion_laft) {
                                    $proveedor_aprobado = 0;
                                    $actualizarAprobacion = $conexion->prepare("UPDATE proveedores SET proveedor_aprobado = ? WHERE Id_proveedor = ?");
                                    $actualizarAprobacion->bind_param("ss", $proveedor_aprobado, $Id_proveedor_laft);
                                    $actualizarAprobacion->execute();
                                    $aprobado_cumplimiento = NULL;
                                    $observaciones_cumplimiento = NULL;
                                    $aprobado_ambiental = NULL;
                                    $observaciones_ambiental = NULL;
                                    $aprobado_negociacion = NULL;
                                    $observaciones_negociacion = NULL;
                                    $actualizarVinculacionProveedor = $conexion->prepare("UPDATE vinculacion_proveedor SET aprobado_cumplimiento = ?, observaciones_cumplimiento = ?,
                                                                        aprobado_ambiental = ?, observaciones_ambiental = ?, aprobado_negociacion = ?, observaciones_negociacion = ?
                                                                        WHERE Id_proveedor_vinculacion_proveedor = ?");
                                    $actualizarVinculacionProveedor->bind_param("sssssss",
                                    $aprobado_cumplimiento,
                                    $observaciones_cumplimiento,
                                    $aprobado_ambiental,
                                    $observaciones_ambiental,
                                    $aprobado_negociacion,
                                    $observaciones_negociacion,
                                    $Id_proveedor_laft);
                                    $actualizarVinculacionProveedor->execute();
                                    header("Location:../../Views/Laft/index.php");
                                } else {
                                    header("Location:../../Views/Supplier/index.php");
                                }
                            }
                        }
                    }
                } else {
                    echo '<script>';
                    echo 'Swal.fire({
                                icon: "warning",
                                title: "Oops...",
                                text: "Parece que aún no tienes acceso, solícitalo al administrador e intenta mas tarde...",
                                showConfirmButton: false,
                                timer: 3500
                            });';
                    echo 'setTimeout(function() { window.location.href = "../../index.php"; }, 3500);';
                    echo '</script>';
                }
            } else {
                echo '<script>';
                echo 'Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "Contraseña Incorrecta, ingresa nuevamente...",
                            showConfirmButton: false,
                            timer: 2000
                        });';
                echo 'setTimeout(function() { window.location.href = "../../index.php"; }, 2000);';
                echo '</script>';
            }
        }
    }
}
?>