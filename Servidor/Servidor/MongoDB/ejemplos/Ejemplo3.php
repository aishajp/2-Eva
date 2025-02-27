<?php
//Conexion mysql
$mysql = new mysqli("localhost", "root", "", "empresa");
if ($mysql->connect_error) {
    die("Connection failed: ". $mysql->connect_error);
}
//Conexion a mongodb
$mongo = new MongoDB\Client("mongodb://localhost:27017");

$db = $mongo->empresa;
//Migrar la table empresa de mysql a mongodb

//Usar insertyMany para insertar todos los registros a la vez

$empleados = [
    ['nombre' => 'Juan', 'apellido' => 'Perez', 'telefono' => '123456789'],
    ['nombre' => 'Ana', 'apellido' => 'Garcia', 'telefono' => '987654321'],
    ['nombre' => 'Pedro', 'apellido' => 'Lopez', 'telefono' => '555555555']
];

//insertar en mongodb

$collection = $db->empleados;

$result = $collection->insertMany($empleados);

echo "Inserted " . $result->getInsertedCount() . " documents";