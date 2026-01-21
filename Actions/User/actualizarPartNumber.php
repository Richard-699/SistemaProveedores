<?php

include('../../ConexionBD/conexion.php');

<<<<<<< HEAD
$partnumber = $_POST['partnumber'] ?? null;
$descripcion_partnumber = $_POST['descripcion_partnumber'] ?? null;
$commodity_partnumber = $_POST['commodity_partnumber'] ?? null;
$porcentaje_peso_bom_partnumber = $_POST['porcentaje_peso_bom_partnumber'] ?? null;
$ruta_imagen_anterior = $_POST['ruta_imagen_anterior'] ?? null;
$imagen_partnumber_nueva_ruta = $ruta_imagen_anterior;

if (isset($_FILES['imagen_partnumber']) && $_FILES['imagen_partnumber']['error'] === UPLOAD_ERR_OK) {
    
    $nombreArchivo = basename($_FILES['imagen_partnumber']['name']);
    $extension = strtolower(pathinfo($nombreArchivo, PATHINFO_EXTENSION));
    $permitidas = ['jpg', 'jpeg', 'png', 'webp'];

    if (in_array($extension, $permitidas)) {
        $carpetaDestino = "../../img/partnumbers/";
        if (!is_dir($carpetaDestino)) {
            mkdir($carpetaDestino, 0777, true);
        }

        $nombreUnico = $partnumber . "_partnumber." . $extension; 
        $rutaDestino = $carpetaDestino . $nombreUnico;

        if (move_uploaded_file($_FILES['imagen_partnumber']['tmp_name'], $rutaDestino)) {
            $imagen_partnumber_nueva_ruta = $rutaDestino;
            
            if ($ruta_imagen_anterior && file_exists($ruta_imagen_anterior) && $rutaDestino != $ruta_imagen_anterior) {
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al mover el nuevo archivo de imagen.']);
            exit;
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Formato de imagen no permitido.']);
        exit;
    }
}

$query = "UPDATE proveedor_partnumbers 
          SET partnumber = ?, 
              descripcion_partnumber = ?,
              commodity_partnumber = ?,
              porcentaje_peso_bom_partnumber = ?,
              imagen_partnumber = ?
=======
$partnumber = $_POST['partnumber'];
$descripcion_partnumber = $_POST['descripcion_partnumber'];
$commodity_partnumber = $_POST['commodity_partnumber'];

$query = "UPDATE proveedor_partnumbers 
          SET partnumber = ?, descripcion_partnumber = ?,
          commodity_partnumber = ?
>>>>>>> 8fe25a02a378af3db1c5f09c74bddd125a144800
          WHERE partnumber = ?";

$stmt = $conexion->prepare($query);

if($stmt === false) {
<<<<<<< HEAD
    echo json_encode(['success' => false, 'message' => 'Error al preparar la consulta: ' . $conexion->error]);
    exit;
}

$stmt->bind_param("sssdss", 
    $partnumber, 
    $descripcion_partnumber,
    $commodity_partnumber,
    $porcentaje_peso_bom_partnumber,
    $imagen_partnumber_nueva_ruta,
=======
    die("Error al preparar la consulta: " . $conexion->error);
}

$stmt->bind_param("ssss", 
    $partnumber, 
    $descripcion_partnumber,
    $commodity_partnumber,
>>>>>>> 8fe25a02a378af3db1c5f09c74bddd125a144800
    $partnumber
);

if($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'PartNumber actualizado con éxito']);
} else {
<<<<<<< HEAD
    echo json_encode(['success' => false, 'message' => 'Error al actualizar el PartNumber: ' . $stmt->error]);
}

$stmt->close();
=======
    echo json_encode(['success' => false, 'message' => 'Error al actualizar el PartNumber']);
}


>>>>>>> 8fe25a02a378af3db1c5f09c74bddd125a144800
?>