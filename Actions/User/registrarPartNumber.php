<html>
    <head>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.5/dist/sweetalert2.min.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.5/dist/sweetalert2.min.js"></script>
    </head>
</html>

<?php

include("../../ConexionBD/conexion.php");

$partnumbers = $_POST['partnumber'];
$descripciones = $_POST['descripcion_partnumber'];
$commodities_proveedor = $_POST['commodity_proveedor'];
$porcentajes_peso_bom_partnumber = $_POST['porcentaje_peso_bom_partnumber'];
$imagenes_partnumber = $_POST['imagen_partnumber'];
$imagen_partnumber = null;
$id_proveedor = $_POST['id_proveedor'];

$query = "INSERT INTO proveedor_partnumbers (partnumber, descripcion_partnumber, 
                    commodity_partnumber, porcentaje_peso_bom_partnumber, imagen_partnumber, id_proveedor_partnumber) 
                    VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conexion->prepare($query);

if (!$stmt) {
    die("Error en la preparación de la consulta: " . $conexion->error);
}

$registroExitoso = true;

for ($i = 0; $i < count($partnumbers); $i++) {
    $partnumber = $partnumbers[$i];
    $descripcion = $descripciones[$i];
    $commodity_proveedor = $commodities_proveedor[$i];
    $porcentaje_peso_bom = $porcentajes_peso_bom_partnumber[$i]; // Asignar el valor individual

    if (isset($_FILES['imagen_partnumber']) && $_FILES['imagen_partnumber']['error'][$i] === UPLOAD_ERR_OK) {
        
        $error_upload = false;
        $nombreArchivo = basename($_FILES['imagen_partnumber']['name'][$i]);
        $extension = strtolower(pathinfo($nombreArchivo, PATHINFO_EXTENSION));
        $permitidas = ['jpg', 'jpeg', 'png', 'webp'];

        if (in_array($extension, $permitidas)) {
            $carpetaDestino = "../../img/partnumbers/";
            if (!is_dir($carpetaDestino)) {
                mkdir($carpetaDestino, 0777, true);
            }

            $nombreUnico = $partnumber . "_partnumber." . $extension;
            $rutaDestino = $carpetaDestino . $nombreUnico;

            if (move_uploaded_file($_FILES['imagen_partnumber']['tmp_name'][$i], $rutaDestino)) {
                $imagen_partnumber = $rutaDestino;
            } else {
                $error_upload = true;
                $error_msg = "Error al mover el archivo para el PartNumber: " . $partnumber;
            }
        } else {
            $error_upload = true;
            $error_msg = "Formato de imagen no permitido para el PartNumber: " . $partnumber;
        }

        if ($error_upload) {
            echo json_encode(["success" => false, "error" => $error_msg]);
            exit;
        }
    }

    $stmt->bind_param(
        "sssdss",
        $partnumber, 
        $descripcion, 
        $commodity_proveedor,
        $porcentaje_peso_bom,
        $imagen_partnumber,
        $id_proveedor
    );

    if (!$stmt->execute()) {
        $registroExitoso = false;
        $error = $stmt->error;
        break;
    }
}


$stmt->close();

if ($registroExitoso) {
    echo '<script>';
    echo 'Swal.fire({
                icon: "success",
                title: "Registro exitoso",
                text: "El partnumber se registró con éxito.",
                showConfirmButton: false,
                timer: 2000
            });';
    echo 'setTimeout(function() { window.location.href = "../../Views/User/registrarPartNumber.php"; }, 2000);';
    echo '</script>';
} else {
    echo "Hubo un problema al registrar el partnumber: ' . addslashes($error) . '";
}

?>
