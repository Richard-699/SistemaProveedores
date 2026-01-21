<?php

include ('../../ConexionBD/conexion.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $partnumber = $_POST['partnumber'];
    $tipoCbd = $_POST['tipoCbd'];

    if($tipoCbd == 1){

        $consulta_partNumber = mysqli_query($conexion, "SELECT * FROM proveedor_partnumbers
                                            WHERE partnumber = '$partnumber'");

        $consultaInfoGeneral = mysqli_query($conexion, "SELECT * FROM proveedor_partnumbers 
                            INNER JOIN costbreakdown ON proveedor_partnumbers.partnumber = costbreakdown.partnumber_costbreakdown
                            WHERE proveedor_partnumbers.partnumber = '$partnumber'");
    }else{

        $consultaInfoGeneral = mysqli_query($conexion, "SELECT * FROM proveedor_partnumbers 
                            LEFT JOIN costbreakdown_simplified ON proveedor_partnumbers.partnumber = costbreakdown_simplified.partnumber_costbreakdown_simplified
                            WHERE proveedor_partnumbers.partnumber = '$partnumber'");

        $consulta_partNumber = mysqli_query($conexion, "SELECT * FROM proveedor_partnumbers 
                            WHERE partnumber = '$partnumber'");
    }

    if (mysqli_num_rows($consultaInfoGeneral) > 0) {

        $descripcion_partnumber = array();

        while ($fila4 = mysqli_fetch_assoc($consulta_partNumber)) {
            $descripcion_partnumber[] = $fila4;
        }

        $datosInfoGeneral = array();
        
        while ($fila = mysqli_fetch_assoc($consultaInfoGeneral)) {
            $datosInfoGeneral[] = $fila;
        }

        $response['success'] = true;
        $response['data'] = $datosInfoGeneral;
    } else {
        $response['success'] = false;

        $descripcion_partnumber = array();

        while ($fila4 = mysqli_fetch_assoc($consulta_partNumber)) {
            $descripcion_partnumber[] = $fila4;
        }

        $response['data'] = $descripcion_partnumber;
    }

    echo json_encode($response);
}
?>