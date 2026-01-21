<?php
$conexion=new mysqli("localhost","root","","sistema_proveedores");
if ($conexion->connect_errno){
    die("Error de conexion: " . $conexion->connect_errno);
}
?>