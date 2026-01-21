<?php

include('../../ConexionBD/conexion.php');

$numero_acreedor = $_POST['numero_acreedor'];

$consultarProveedor = mysqli_query($conexion, "SELECT * FROM proveedores WHERE numero_acreedor = '$numero_acreedor'");

if(mysqli_num_rows($consultarProveedor)>0){
    echo json_encode(array("success" => true));
}else{
    echo json_encode(array("success" => false));
}

?>