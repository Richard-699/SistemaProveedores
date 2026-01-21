<?php

session_start();

include('../../ConexionBD/conexion.php');

$id_partnumber = $_POST['id_partnumber'];
$id_proveedor_usuarios = $_SESSION['id_proveedor_usuarios'];
$consultarCostbreakdown = mysqli_query($conexion, "SELECT * FROM costbreakdown WHERE id_proveedor_costbreakdow = '$id_proveedor_usuarios' and partnumber_costbreakdown = '$id_partnumber'");

$row = mysqli_fetch_assoc($consultarCostbreakdown);
$id_costbreakdown = $row['id_costbreakdown'];

$consultarMateriaPrima = mysqli_query($conexion, "SELECT * FROM costbreakdown_materia_prima WHERE id_costbreakdown_materia_prima = '$id_costbreakdown'");

if (isset($_POST['isconsulta']) && $_POST['isconsulta'] === "true") {

    if (mysqli_num_rows($consultarMateriaPrima) > 0) {

        $materiaPrima = [];

        while ($row = mysqli_fetch_assoc($consultarMateriaPrima)) {
            $materiaPrima[] = $row;
        }

        $response = [
            'success' => true,
            'materiaPrima' => $materiaPrima
        ];
    } else {
        $response = [
            'success' => false,
            'message' => 'No record found'
        ];
    }
} else {

    $nombre_materia_prima = $_POST['nombre_materia_prima'];
    $moneda_unidad_materia_prima = $_POST['moneda_unidad_materia_prima'];
    $unidad_materia_prima = $_POST['unidad_materia_prima'];
    $unidad_pieza_materia_prima = $_POST['unidad_pieza_materia_prima'];
    $moneda_pieza_materia_prima = $_POST['moneda_pieza_materia_prima'];
    $total_moneda_pieza_materia_prima = $_POST['total_moneda_pieza_materia_prima'];

    $total_moneda_pieza_materia_prima = str_replace('.', '', $total_moneda_pieza_materia_prima);
    $total_moneda_pieza_materia_prima = str_replace(',', '.', $total_moneda_pieza_materia_prima);

    if (mysqli_num_rows($consultarMateriaPrima) > 0) {

        $deleteMateriaPrima = "DELETE FROM costbreakdown_materia_prima WHERE id_costbreakdown_materia_prima = ?";

        if ($stmt = $conexion->prepare($deleteMateriaPrima)) {
            $stmt->bind_param("s", $id_costbreakdown);
            $stmt->execute();
        }
    }

    $insertarMateriaPrima = $conexion->prepare("INSERT INTO costbreakdown_materia_prima (nombre_materia_prima, 
                                                        moneda_unidad_materia_prima, unidad_materia_prima, unidad_pieza_materia_prima, 
                                                        moneda_pieza_materia_prima, total_moneda_pieza_materia_prima,
                                                        id_costbreakdown_materia_prima) VALUES (?, ?, ?, ?, ?, ?, ?)");

    for ($i = 0; $i < count($nombre_materia_prima); $i++) {

        $moneda_unidad_materia_prima[$i] = str_replace('.', '', $moneda_unidad_materia_prima[$i]);
        $moneda_unidad_materia_prima[$i] = str_replace(',', '.', $moneda_unidad_materia_prima[$i]);

        $moneda_pieza_materia_prima[$i] = str_replace('.', '', $moneda_pieza_materia_prima[$i]);
        $moneda_pieza_materia_prima[$i] = str_replace(',', '.', $moneda_pieza_materia_prima[$i]);

        $total_moneda_pieza_materia_prima[$i] = str_replace('.', '', $total_moneda_pieza_materia_prima[$i]);
        $total_moneda_pieza_materia_prima[$i] = str_replace(',', '.', $total_moneda_pieza_materia_prima[$i]);

        $unidad_pieza_materia_prima[$i] = str_replace('.', '', $unidad_pieza_materia_prima[$i]);
        $unidad_pieza_materia_prima[$i] = str_replace(',', '.', $unidad_pieza_materia_prima[$i]);

        $insertarMateriaPrima->bind_param(
            "sdsdsss",
            $nombre_materia_prima[$i],
            $moneda_unidad_materia_prima[$i],
            $unidad_materia_prima[$i],
            $unidad_pieza_materia_prima[$i],
            $moneda_pieza_materia_prima[$i],
            $total_moneda_pieza_materia_prima,
            $id_costbreakdown
        );
        $insertarMateriaPrima->execute();

        if ($insertarMateriaPrima->affected_rows > 0) {
            $response = ['success' => true];
        } else {
            $response = ['success' => false, 'message' => 'Insert failed'];
        }
    }
}

echo json_encode($response);
$conexion->close();
