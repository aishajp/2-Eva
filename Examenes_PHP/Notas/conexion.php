<?php
    $localhost = "localhost:3306"; 
    $usuario = "root"; 
    $pw = ""; 
    $database = "bdnotas"; 

    $conexion = new mysqli ($localhost, $usuario, $pw, $database); 
    if(!$conexion){
        die("error de conexion");
    }else{
        echo "Conexion existosa"; 
    }

?>