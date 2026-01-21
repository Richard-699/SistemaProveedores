<?php
include('../../ConexionBD/conexion.php');

if (isset($_POST['id_proveedor'])) {
    $id_proveedor = $_POST['id_proveedor'];

    $query_opciones = $conexion->query("SELECT id_servicio_suministro, servicio_suministro FROM proveedor_servicios_suministros");
    $opciones = [];
    while ($row = $query_opciones->fetch_assoc()) {
        $opciones[] = [
            "id_servicio" => $row['id_servicio_suministro'],
            "servicio_suministro" => $row['servicio_suministro']
        ];
    }

    $query_seleccionadas = $conexion->prepare("SELECT id_servicio_suministro FROM proveedores_servicios_suministros WHERE id_proveedor = ?");
    $query_seleccionadas->bind_param("s", $id_proveedor);
    $query_seleccionadas->execute();
    $result = $query_seleccionadas->get_result();

    $seleccionadas = [];
    while ($row = $result->fetch_assoc()) {
        $seleccionadas[] = $row['id_servicio_suministro'];
    }

    echo json_encode([
        "status" => "success",
        "opciones" => $opciones,
        "seleccionadas" => $seleccionadas
    ]);
} else {
    echo json_encode(["status" => "error", "message" => "ID de proveedor no proporcionado"]);
}
?>
