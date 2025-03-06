<?php
session_start();
// Configuración de la conexión a la base de datos
$servidor = "localhost";
$usuario = "root";
$password = "";
$bd = "pictogramasphp";

// Crear conexión
$conexion = new mysqli($servidor, $usuario, $password, $bd);

// Verificar conexión
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}
$mensaje_error = "";

$imagenesGuardadas="SELECT imagen FROM imagenes";
$allow = array("jpg","png" );
$archivos = array_slice(scandir($imagenesGuardadas),2);
echo"<pre>";
print_r($archivos);
echo"</pre>";

$imagenes= [
    "imagenes/banarse.jpg",
    "imagenes/desayunar.jpg",
    "imagenes/colegio_pic.jpg",
    "imagenes/dentista_pic.jpg",
    "imagenes/jugar.jpg",
    "imagenes/playa_pic.jpg",
    "imagenes/piscina_pic.png",
    "imagenes/vestirse.jpg",
    "imagenes/comer.jpg"


];
if (count ($archivos))

//consulta para obtener las imagenes de la base de datos
$sqlImagen= "SELECT imagen  FROM imagenes";
$stmt = $conexion -> prepare($sqlImagen);
$imagen="";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ejercicio 1</title>
</head>
<body>
    <h1>Listado pictogramas</h1>
</body>
</html>