<?php
include('../../ConexionBD/conexion.php');

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Consultar los datos del proveedor por ID
    $query = "SELECT * FROM proveedores WHERE Id_proveedor = ?";
    $stmt = mysqli_prepare($conexion, $query);
    mysqli_stmt_bind_param($stmt, "s", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($proveedor = mysqli_fetch_assoc($result)) {
        // Cargar la vista parcial con los datos del proveedor
        include '../../Views/User/_EditarProveedorPartial.php';
    } else {
        echo "No se encontró el proveedor.";
    }
}
?>
