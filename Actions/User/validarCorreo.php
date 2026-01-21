<?php

include("../../ConexionBD/conexion.php");

$correo_usuario = $_POST['correo_usuario'];

$consultarUsuario = mysqli_query($conexion, "SELECT * FROM usuarios WHERE correo_usuario = '$correo_usuario'");

if(mysqli_num_rows($consultarUsuario)>0){
    echo "true";
}else{
    echo "false";
}

?>