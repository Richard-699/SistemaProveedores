<?php

include('../../ConexionBD/conexion.php');

$id_servicio_suministro = $_POST['id_servicio_suministro'];
$servicio_suministro = $_POST['servicio_suministro'];

$query = "UPDATE proveedor_servicios_suministros 
          SET servicio_suministro = ?
          WHERE id_servicio_suministro = ?";

$stmt = $conexion->prepare($query);

if($stmt === false) {
    die("Error al preparar la consulta: " . $conexion->error);
}

$stmt->bind_param("si", 
    $servicio_suministro, 
    $id_servicio_suministro
);

if($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Servicio o Suministro actualizado con éxito']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al actualizar el PartNumber']);
}


?>