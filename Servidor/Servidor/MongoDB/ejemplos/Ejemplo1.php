<?php
require 'vendor/autoload.php';// Asegurarse de que la ruta sea correcta

//Conectar a MongoDB

$client = new MongoDB\Client("mongodb://localhost:27017");

// Selecionar la base de datos

$db = $client->Ejemplo;

// Selecionar la colección

$collection = $db->Clase1;

// Insertar un registro

$datos = [

    "nombre" => "Aisha",

    "id" => "2"

];

$result = $collection-> insertOne($datos);

//Recuperar el registro

$document = $collection->find();

foreach ($document as $doc) {
    echo $doc["nombre"]. " - ". $doc["id"]. "\n";
}
?>