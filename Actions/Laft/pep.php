<?php
session_start();
include "../../ConexionBD/conexion.php";

$Id_proveedor_laft = $_SESSION['id_proveedor_usuarios'];
$consultarlaft = mysqli_query($conexion, "SELECT * FROM laft WHERE Id_proveedor_laft = '$Id_proveedor_laft'");
$row = mysqli_fetch_assoc($consultarlaft);
$Id_laft = $row['Id_laft'];

$consultarPeP = mysqli_query($conexion, "SELECT * FROM laft_pep WHERE Id_laft_pep = '$Id_laft'");

if (isset($_POST['isconsulta']) && $_POST['isconsulta'] === "true") {

    if (mysqli_num_rows($consultarPeP) > 0) {
        $pep = [];

        while ($row = mysqli_fetch_assoc($consultarPeP)) {
            
            $pep[] = $row;
        }

        $response = [
            'success' => true,
            'pep' => $pep
        ];
    } else {
        $response = [
            'success' => false,
            'message' => 'No record found'
        ];
    }
} else {

    $nombre_pep = $_POST['nombre_pep'];
    $tipo_documento_pep = $_POST['tipo_documento_pep'];
    $numero_identificacion_pep = $_POST['numero_identificacion_pep'];
    $cargo_ocupa_pep = $_POST['cargo_ocupa_pep'];
    $cargo_ocupa_ocupo_cataloga_pep = $_POST['cargo_ocupa_ocupo_cataloga_pep'];
    $desde_cuando_pep = $_POST['desde_cuando_pep'];
    $hasta_cuando_pep = $_POST['hasta_cuando_pep'];

    $total_formularios = count($nombre_pep);

    if (mysqli_num_rows($consultarPeP) > 0) {

        $eliminarPep = $conexion->prepare("DELETE FROM laft_pep WHERE Id_laft_pep = ?");

        $eliminarPep->bind_param("i", $Id_laft);

        if ($eliminarPep->execute()) {
            $response = ['success' => true];
        } else {
            $response = ['success' => false, 'message' => 'Delete failed'];
        }

        $eliminarPep->close();
    }

    for ($i = 0; $i < $total_formularios; $i++) {

        $nombre = $nombre_pep[$i];
        $tipo_documento = $tipo_documento_pep[$i];
        $numero_identificacion = $numero_identificacion_pep[$i];
        $cargo_ocupa = $cargo_ocupa_pep[$i];
        $cargo_ocupa_ocupo_cataloga = $cargo_ocupa_ocupo_cataloga_pep[$i];
        $desde_cuando = $desde_cuando_pep[$i];
        $hasta_cuando = $hasta_cuando_pep[$i];

        $insertarPep = $conexion->prepare("INSERT INTO laft_pep
            (nombre_pep, tipo_documento_pep, numero_identificacion_pep, cargo_ocupa_pep, cargo_ocupa_ocupo_cataloga_pep,
                desde_cuando_pep, hasta_cuando_pep, Id_laft_pep)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

        $insertarPep->bind_param(
            "ssissssi",
            $nombre,
            $tipo_documento,
            $numero_identificacion,
            $cargo_ocupa,
            $cargo_ocupa_ocupo_cataloga,
            $desde_cuando,
            $hasta_cuando,
            $Id_laft
        );

        if ($insertarPep->execute()) {
            $response = ['success' => true];
        } else {
            $response = ['success' => false, 'message' => 'Insert failed'];
        }
    }
}

echo json_encode($response);
