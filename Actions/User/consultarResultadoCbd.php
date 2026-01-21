<?php

include('../../ConexionBD/conexion.php');

if (isset($_POST['id_proveedor'])) {
    $id_proveedor = $_POST['id_proveedor'];

    $consulta = "SELECT * FROM proveedores
                INNER JOIN costbreakdown ON proveedores.Id_proveedor = costbreakdown.id_proveedor_costbreakdow
                INNER JOIN proveedor_partnumbers ON costbreakdown.partnumber_costbreakdown = proveedor_partnumbers.partnumber
                WHERE Id_proveedor = ?";

    $stmt = mysqli_prepare($conexion, $consulta);
    mysqli_stmt_bind_param($stmt, 's', $id_proveedor);
    mysqli_stmt_execute($stmt);

    $resultado = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($resultado) > 0) {
        while ($fila = mysqli_fetch_assoc($resultado)) {
            echo "<tr>";
            echo "<td class='text-start'>" . $fila['fecha_costbreakdown'] . "</td>";
            echo "<td class='text-start'>" . $fila['diligencio_costbreakdown'] . "</td>";
            echo "<td class='text-start'>" . $fila['partnumber_costbreakdown'] . "</td>";
            echo "<td class='text-start'>" . $fila['descripcion_partnumber'] . "</td>";
            echo "<td class='text-start'>" . $fila['precio_neto_total'] . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr>";
        echo "<td colspan='5' style='text-align: center;'>No se encontraron resultados</td>";
        echo "</tr>";
    }
}
