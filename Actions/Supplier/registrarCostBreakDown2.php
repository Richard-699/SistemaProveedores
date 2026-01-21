<?php
session_start();
include ('../../ConexionBD/conexion.php');
require '../../vendor/autoload.php';
?>
<html>
    <head>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.5/dist/sweetalert2.min.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.5/dist/sweetalert2.min.js"></script>
    </head>
</html>

<?php
$zona_horaria = new DateTimeZone('America/Bogota');
$fecha_actual = new DateTime('now', $zona_horaria);
$fecha_costbreakdown_simplified = $fecha_actual->format('Y-m-d H:i:s');

$id_partnumber = $_POST['id_partnumber'];
$id_proveedor_usuarios = $_SESSION['id_proveedor_usuarios'];
$descripcion_costbreakdown_simplified = $_POST['descripcion_costbreakdown_simplified'];
$moneda_costbreakdown = $_POST['moneda_costbreakdown'];
$precio_costbreakdown_simplified = $_POST['precio_costbreakdown_simplified'];
$porcentaje_costbreakdown_simplified = $_POST['porcentaje_costbreakdown_simplified'];

$consultarCbd2 = "SELECT * FROM costbreakdown_simplified WHERE partnumber_costbreakdown_simplified = ?";
$stmt = $conexion->prepare($consultarCbd2);
$stmt->bind_param("s", $id_partnumber);
$stmt->execute();
$resultado = $stmt->get_result();

if(mysqli_num_rows($resultado)>0){

    while($row = mysqli_fetch_array($resultado)){

        $id_costbreakdown_simplified_history = $row['id_costbreakdown_simplified'];
        $descripcion_costbreakdown_simplified_history = $row['descripcion_costbreakdown_simplified'];
        $porcentaje_costbreakdown_simplified_history = $row['porcentaje_costbreakdown_simplified'];
        $fecha_costbreakdown_simplified_history = $row['fecha_costbreakdown_simplified'];
        $id_proveedor_simplified_history = $row['id_proveedor_simplified'];
        $partnumber_costbreakdown_simplified_history = $row['partnumber_costbreakdown_simplified'];

        $insertarCostbreakdownSimplifiedHistory = $conexion->prepare("INSERT INTO costbreakdown_simplified_history 
                                                    (id_costbreakdown_simplified_history,
                                                    descripcion_costbreakdown_simplified_history, 
                                                    moneda_costbreakdown_simplified,
                                                    precio_costbreakdown_simplified,
                                                    porcentaje_costbreakdown_simplified_history,
                                                    fecha_costbreakdown_simplified_history, id_proveedor_simplified_history, 
                                                    partnumber_costbreakdown_simplified_history)
                                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

        $insertarCostbreakdownSimplifiedHistory->bind_param("sssddsis", $id_costbreakdown_simplified_history, 
                                                    $descripcion_costbreakdown_simplified_history,
                                                    $moneda_costbreakdown, $precio_costbreakdown_simplified,
                                                    $porcentaje_costbreakdown_simplified_history, 
                                                    $fecha_costbreakdown_simplified_history,
                                                    $id_proveedor_simplified_history, 
                                                    $partnumber_costbreakdown_simplified_history);
                                                    
        $insertarCostbreakdownSimplifiedHistory->execute();
    }

    
    $deletecbd2 = "DELETE FROM costbreakdown_simplified WHERE partnumber_costbreakdown_simplified = ?";
    $stmt = $conexion->prepare($deletecbd2);
    $stmt->bind_param("s", $id_partnumber);
    $stmt->execute();
}

$insertarCostbreakdownSimplified = $conexion->prepare("INSERT INTO costbreakdown_simplified (id_costbreakdown_simplified,
                                                    descripcion_costbreakdown_simplified, moneda_costbreakdown_simplified,
                                                    precio_costbreakdown_simplified, porcentaje_costbreakdown_simplified,
                                                    fecha_costbreakdown_simplified, id_proveedor_simplified, 
                                                    partnumber_costbreakdown_simplified)
                                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

for ($i = 0; $i < count($porcentaje_costbreakdown_simplified); $i++) {
    $id_costbreakdown = uniqid();
    $id_costbreakdown = substr(str_replace(".", "", $id_costbreakdown), 0, 25);
    
    $insertarCostbreakdownSimplified->bind_param("sssddsis", $id_costbreakdown, $descripcion_costbreakdown_simplified[$i],
                                                        $moneda_costbreakdown, $precio_costbreakdown_simplified,
                                                        $porcentaje_costbreakdown_simplified[$i], $fecha_costbreakdown_simplified,
                                                        $id_proveedor_usuarios, $id_partnumber);
    $insertarCostbreakdownSimplified->execute();
};

if ($insertarCostbreakdownSimplified->affected_rows > 0) {
    echo '<script>';
    echo 'Swal.fire({
                icon: "success",
                title: "Carga exitosa",
                text: "Los datos han sido cargados con éxito.",
                showConfirmButton: false,
                timer: 2000 // Cierra automáticamente después de 3 segundos
            });';
    echo 'setTimeout(function() { window.location.href = "../../Views/Supplier/costBreakDown.php"; }, 2000);';
    echo '</script>';
}else{
    echo '<script>';
    echo 'Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Los datos no se cargaron correctamente, intenta de nuevo...",
                showConfirmButton: false,
                timer: 2000 // Cierra automáticamente después de 3 segundos
            });';
    echo 'setTimeout(function() { window.location.href = "../../Views/Supplier/costBreakDown.php"; }, 2000);';
    echo '</script>';
}

$stmt->close();
$conexion->close();

?>