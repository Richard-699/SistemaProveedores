<?php

include('../../ConexionBD/conexion.php');

$categoria = $_GET['categoria'];

$sql = mysqli_query($conexion, "SELECT * FROM proveedor_sub_categoria WHERE id_categoria_sub_categoria = $categoria ORDER BY descripcion_sub_categoria ASC");

if (mysqli_num_rows($sql) > 0) {

    $datos = array();
    while ($fila = mysqli_fetch_assoc($sql)) {
        $datos[] = $fila;
    }
    echo json_encode($datos);
} else {
    echo "No se encontraron resultados.";
}

mysqli_close($conexion);

?>
