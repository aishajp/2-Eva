<?php

$servidor = "localhost:8080";
$usuario = "root";
$contrasena = "";

$conexion = new mysqli($servidor, $usuario, $contrasena);

if ($conexion->connect_error) {
    die("Conexion fallida: " . $conexion->connect_error);
}

//Seleccionar la base de datos
$nombre_db = 'empresa';

$conexion->select_db($nombre_db);//Aqui seleccionar la base de datos

//Crear la tabla si no existe

$sql = "CREATE TABLE IF NOT EXISTS empleados (
    CodEmple INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(30) NOT NULL,
    apellido VARCHAR(30) NOT NULL,
    Departamento VARCHAR(50) NOT NULL
    )";
    
if ($conexion->query($sql) === TRUE) {
    echo "La tabla empleados se ha creado o ya existía.<br>";
} else {
    echo "Error al crear la tabla: ". $conexion->error;
}

//Insertar datos de 50 empleados

$datos = array(
    array('nombre' => 'John', 'apellido' => 'Doe', 'Departamento' => 'Marketing'),
    array('nombre' => 'Jane', 'apellido' => 'Smith', 'Departamento' => 'Finance'),
    array('nombre' => 'Michael', 'apellido' => 'Johnson', 'Departamento' => 'HR'),
    //...
);

//Preparar la consulta de inercion

$stmt = $conexion->prepare("INSERT INTO empleados (nombre, apellido, Departamento) VALUES (?,?,?)");

//Ejecutar la insercion para cada empleado

foreach ($datos as $empleado) {
    $stmt->bind_param("sss", $empleado['nombre'], $empleado['apellido'], $empleado['Departamento']);
    $stmt->execute();
    echo "Empleado añadido: ". $conexion->insert_id. "<br>";
    $stmt->close();
}

//Cerrar la declaracion y la conexion

$stmt->close();
$conexion->close();

?> 

