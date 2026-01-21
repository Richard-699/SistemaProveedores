<?php

include('../../ConexionBD/conexion.php');

$Id_proveedor = $_POST['Id_proveedor'];
$numero_acreedor = $_POST['numero_acreedor'];
$nombre_proveedor = $_POST['nombre_proveedor'];
$idioma_proveedor = $_POST['idioma_proveedor'];
$tipo_proveedor = $_POST['tipo_proveedor'];
$correo_proveedor = $_POST['correo_proveedor'];
$carta_beneficiarios_finales = $_POST['carta_beneficiarios_finales'];

$Id_categoria = NULL;
$Id_sub_categoria = NULL;
$id_commodity_proveedor = NULL;
$maneja_formato_costbreakdown = NULL;
$historia_proveedor = null;
$descripcion_proveedor = null;
$porcentaje_bom_proveedor = null;
$logo_proveedor = null;

$success = null;

if($tipo_proveedor == "Directo"){
    $id_commodity_proveedor = $_POST['id_commodity_proveedor'];
    $maneja_formato_costbreakdown = $_POST['maneja_el_formato_CostBreakDown'];
    $historia_proveedor = $_POST['historia_proveedor'];
    $descripcion_proveedor = $_POST['descripcion_proveedor'];
    $porcentaje_bom_proveedor = $_POST['porcentaje_bom_proveedor'];
    $logo_proveedor = $_POST['logo_bd'];

    if (isset($_FILES['logo_proveedor']) && $_FILES['logo_proveedor']['error'] === 0) {
        $nombreArchivo = basename($_FILES['logo_proveedor']['name']);
        $extension = strtolower(pathinfo($nombreArchivo, PATHINFO_EXTENSION));
        $permitidas = ['jpg', 'jpeg', 'png', 'webp'];

        if (in_array($extension, $permitidas)) {
            $carpetaDestino = "../../img/logos/";
            if (!is_dir($carpetaDestino)) {
                mkdir($carpetaDestino, 0777, true);
            }

            $nombreUnico = $Id_proveedor . "_logo." . $extension;
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
}else{
    $Id_categoria = $_POST['id_categoria'];
    $Id_sub_categoria = $_POST['id_sub_categoria'];
}

$formulario_ambiental = $_POST['formulario_ambiental'];

$query = "UPDATE proveedores 
          SET numero_acreedor = ?, nombre_proveedor = ?, idioma_proveedor = ?, tipo_proveedor = ?, 
              Id_categoria = ?, Id_sub_categoria = ?, id_commodity_proveedor = ?, 
              maneja_formato_costbreakdown = ?, historia_proveedor = ?, descripcion_proveedor = ?,
              porcentaje_bom_proveedor = ?, logo_proveedor = ?,
              formulario_ambiental = ?, correo_proveedor = ?,
              carta_beneficiarios_finales = ?
          WHERE Id_proveedor = ?";

$stmt = $conexion->prepare($query);

if($stmt === false) {
    die("Error al preparar la consulta: " . $conexion->error);
}

$stmt->bind_param("isssiiisssdsssis", 
    $numero_acreedor, 
    $nombre_proveedor,
    $idioma_proveedor,
    $tipo_proveedor, 
    $Id_categoria, 
    $Id_sub_categoria, 
    $id_commodity_proveedor, 
    $maneja_formato_costbreakdown,
    $historia_proveedor,
    $descripcion_proveedor,
    $porcentaje_bom_proveedor,
    $logo_proveedor,
    $formulario_ambiental,
    $correo_proveedor,
    $carta_beneficiarios_finales,
    $Id_proveedor
);

if ($stmt->execute()) {
    $success = true;
}else{
    $success = false;
}

if($tipo_proveedor == "Indirecto" && $success){

    $servicios_suministros = $_POST['servicios_suministros'];

    $conexion->query("DELETE FROM proveedores_servicios_suministros WHERE id_proveedor = '$Id_proveedor'");

    $stmt = $conexion->prepare("INSERT INTO proveedores_servicios_suministros (id_proveedor, id_servicio_suministro) VALUES (?, ?)");
    foreach ($servicios_suministros as $id_servicio) {
        $stmt->bind_param("si", $Id_proveedor, $id_servicio);
        $stmt->execute();
    }

    if($stmt->execute()) {
        $success = true;
    } else {
        $success = false;
    }
}

if($success){
    echo json_encode(['success' => true, 'message' => 'Proveedor actualizado con éxito']);
}else{
    echo json_encode(['success' => false, 'message' => 'Error al actualizar el proveedor']);
}

?>