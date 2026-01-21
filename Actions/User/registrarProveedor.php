<?php
session_start();

include("../../ConexionBD/conexion.php");

$id_proveedor = uniqid();
$id_proveedor = substr(str_replace(".", "", $id_proveedor), 0, 25);

$numero_acreedor = null;

if (isset($_POST['numero_acreedor'])) {
    $numero_acreedor = $_POST['numero_acreedor'];
}

$nombre_proveedor = strtoupper($_POST["nombre_proveedor"]);
$tipo_proveedor = $_POST['tipo_proveedor'];
$idioma_proveedor = $_POST['idioma_proveedor'];

$Id_commodity = null;
$id_categoria = null;
$id_sub_categoria = null;
$maneja_el_formato_CostBreakDown = null;
$historia_proveedor = null;
$descripcion_proveedor = null;
$porcentaje_bom_proveedor = null;
$logo_proveedor = null;

if ($tipo_proveedor == "Directo") {
    $Id_commodity = strtoupper($_POST["Id_commodity"]);
    $maneja_el_formato_CostBreakDown = $_POST['maneja_el_formato_CostBreakDown'];
    $historia_proveedor = $_POST['historia_proveedor'];
    $descripcion_proveedor = $_POST['descripcion_proveedor'];
    $porcentaje_bom_proveedor = $_POST['porcentaje_bom_proveedor'];

    if (isset($_FILES['logo_proveedor']) && $_FILES['logo_proveedor']['error'] === 0) {
        $nombreArchivo = basename($_FILES['logo_proveedor']['name']);
        $extension = strtolower(pathinfo($nombreArchivo, PATHINFO_EXTENSION));
        $permitidas = ['jpg', 'jpeg', 'png', 'webp'];

        if (in_array($extension, $permitidas)) {
            $carpetaDestino = "../../img/logos/";
            if (!is_dir($carpetaDestino)) {
                mkdir($carpetaDestino, 0777, true);
            }

            $nombreUnico = $id_proveedor . "_logo." . $extension;
            $rutaDestino = $carpetaDestino . $nombreUnico;

            if (move_uploaded_file($_FILES['logo_proveedor']['tmp_name'], $rutaDestino)) {
                $logo_proveedor = $rutaDestino;
            } else {
                echo json_encode(["success" => false, "error" => "Error al mover el archivo."]);
                exit;
            }
        } else {
            echo json_encode(["success" => false, "error" => "Formato de imagen no permitido."]);
            exit;
        }
    }
} else {
    $id_categoria = $_POST['id_categoria'];
    $id_sub_categoria = $_POST['id_sub_categoria'];
}

$formulario_ambiental = $_POST['formulario_ambiental'];

//Datos Proveedor (Usuario)
$correo_proveedor = $_POST['correo_proveedor'];
$password_proveedor = $_POST['password_proveedor'];
$hashedPassword = password_hash($password_proveedor, PASSWORD_DEFAULT);
$rol_usuario = '3';
$estado_registro = true;
$is_temporal = true;
$proveedor_aprobado    = false;
$carta_beneficiarios_finales = false;

$correo_envio = $_POST['correo_envio'];
$correo_negociador = $_SESSION["correo_usuario"];

$insertarProveedor = mysqli_prepare($conexion, "INSERT INTO proveedores
    (Id_proveedor, numero_acreedor, nombre_proveedor, id_commodity_proveedor, tipo_proveedor, idioma_proveedor, 
    maneja_formato_costbreakdown, historia_proveedor, descripcion_proveedor, porcentaje_bom_proveedor, logo_proveedor,
    Id_categoria, Id_sub_categoria, formulario_ambiental, carta_beneficiarios_finales, correo_negociador, 
    correo_proveedor, proveedor_aprobado) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

mysqli_stmt_bind_param(
    $insertarProveedor,
    "sisisssssdsiisisss",
    $id_proveedor,
    $numero_acreedor,
    $nombre_proveedor,
    $Id_commodity,
    $tipo_proveedor,
    $idioma_proveedor,
    $maneja_el_formato_CostBreakDown,
    $historia_proveedor, 
    $descripcion_proveedor,
    $porcentaje_bom_proveedor, 
    $logo_proveedor,
    $id_categoria,
    $id_sub_categoria,
    $formulario_ambiental,
    $carta_beneficiarios_finales,
    $correo_negociador,
    $correo_envio,
    $proveedor_aprobado
);
mysqli_stmt_execute($insertarProveedor);

$insertarUsuario = mysqli_prepare($conexion, "INSERT INTO usuarios
    (correo_usuario, password_usuario, id_proveedor_usuarios, id_rol_usuarios, estado_registro, is_temporal) 
            VALUES (?, ?, ?, ?, ?, ?)");

mysqli_stmt_bind_param(
    $insertarUsuario,
    "ssssii",
    $correo_proveedor,
    $hashedPassword,
    $id_proveedor,
    $rol_usuario,
    $estado_registro,
    $is_temporal
);
mysqli_stmt_execute($insertarUsuario);

$aprobado_cumplimiento = NULL;
$aprobado_ambiental = NULL;
$aprobado_negociacion = NULL;

$insertarVinculacion = mysqli_prepare($conexion, "INSERT INTO vinculacion_proveedor
    (aprobado_cumplimiento, aprobado_ambiental, aprobado_negociacion, Id_proveedor_vinculacion_proveedor)
            VALUES (?, ?, ?, ?)");

mysqli_stmt_bind_param(
    $insertarVinculacion,
    "ssss",
    $aprobado_cumplimiento,
    $aprobado_ambiental,
    $aprobado_negociacion,
    $id_proveedor
);
mysqli_stmt_execute($insertarVinculacion);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($insertarProveedor && $insertarUsuario) {

    if ($idioma_proveedor == "Es") {
        $titulo = 'Bienvenido a Haceb Whirlpool, ';
        $asunto = "Bienvenido a Haceb Whirlpool, ";
        $text1 = "Las siguientes son las credenciales de acceso al sistema de proveedores de Haceb Whirlpool Industrial S.A.S.";
        $text2 = "Usuario:";
        $text3 = "Contraseña temporal:";
        $text4 = "Enlace de acceso:";
        $text5 = "Puede consultar el manual de usuario";
        $text6 = "Aquí";
    } else {
        $titulo = 'Welcome to Haceb Whirlpool, ';
        $asunto = "Welcome to Haceb Whirlpool, ";
        $text1 = "The following are the access credentials to the Haceb Whirlpool Industrial S.A.S. supplier system.";
        $text2 = "User:";
        $text3 = "Temporary password:";
        $text4 = "Access link:";
        $text5 = "You can consult the user manual";
        $text6 = "Here";
    }

    require '../../Services/Exception.php';
    require '../../Services/PHPMailer.php';
    require '../../Services/SMTP.php';

    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host       = 'gtxm1009.siteground.biz';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'hwiverificacion@hacebwhirlpoolindustrial.com';
        $mail->Password   = 'HWI2023*';
        $mail->SMTPSecure = 'ssl';
        $mail->Port       = 465;
        $mail->setFrom('hwiverificacion@hacebwhirlpoolindustrial.com', 'Equipo BI');
        $mail->addAddress($correo_envio);
        $mail->addBCC('ricardo.rojas@hacebwhirlpool.com');
        $mail->addBCC($correo_negociador);
        $mail->isHTML(true);
        $mail->Subject = $titulo . $nombre_proveedor;

        $logoUrl = "https://dattics.com/wp-content/uploads/2022/03/Haceb-Whirlpool-Industrial.jpg";

        $mail->Body = '
            <div style="border-radius:10px; border: 1px solid #cccccc; max-width: 100%; max-height: 100%; margin-top: 50px; margin-left: auto; margin-right: auto; text-align: center; padding: 20px;">
                <div style="text-align: center;">
                    <div style="display: inline-block; border-radius: 10px; max-width: 200px; padding: 10px;">
                        <img src="' . $logoUrl . '" alt="Logo Empresa" style="max-width: 100%; height: auto; border-radius: 50%; border: 1px solid #cccccc;">
                    </div>
                </div>
                <h4 style="margin-top: 20px;">' . $asunto . $nombre_proveedor . '</h4>
                <hr style="background-color: #cccccc; border: none; height: 1px; width: 100%; margin-top: 20px; margin-bottom: 20px;">
                <div style="width: 95%; text-align: justify; margin-left: auto; margin-right: auto;">
                    <div>
                        <p style="margin-top: 10px;">' . $text1 . '</p>
                        <p><span style="font-weight: bold;">' . $text2 . '</span> ' . $correo_proveedor . '</p>
                        <p><span style="font-weight: bold;">' . $text3 . '</span> ' . $password_proveedor . '</p>
                        <p><span style="font-weight: bold;">' . $text4 . ' </span><a href="https://sistema.proveedores.hacebwhirlpoolindustrial.com/SistemaProveedores/index.php?idioma=' . $idioma_proveedor . '">Haceb Whirlpool</a></p>
                        <p><span style="font-weight: bold;">' . $text5 . ' </span><a href="https://drive.google.com/drive/folders/1z0syJeQ-8I6f8cJQfizZtww6b6nSk1OO?usp=sharing">' . $text6 . '</a></p>
                    </div>
                </div>
            </div>
            <div style="text-align: center;">
                <p style="color: #999999;">Copyright © Haceb Whirlpool Industrial S.A.S</p>
            </div>
        ';

        $mail->send();
        echo json_encode(array("success" => true));
    } catch (Exception $e) {
        echo json_encode(array("success" => false, "error" => $mail->ErrorInfo));
    }
} else {
    echo json_encode(array("success" => false));
}
