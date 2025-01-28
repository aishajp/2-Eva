<?php 

    require_once "C:/xampp/php/vendor/autoload.php"; 

 

    $mongo = new MongoDB\Client("mongodb://localhost:27017");
 

    $db = $mongo->Ejemplo; 

    $collection = $db->Clase1; 

    $datos = [ 

        "nombre" => "Daria", 

        "id" => "1" 

    ]; 

    $collection->insertOne($datos);

        echo "Dato insertado correctamente"; 

?> 