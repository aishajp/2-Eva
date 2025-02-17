<?php
$DB_HOST = 'localhost:8080'; // Cambia si tu base de datos está en otro servidor
$DB_USER = 'root'; // Tu usuario de MySQL
$DB_PASS = ''; // Tu contraseña de MySQL
$DB_NAME = 'diabetesdb'; // Nombre de tu base de datos

// Crear conexión
$conexion = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

// Verificar conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Establecer el juego de caracteres a UTF-8
$conexion->set_charset("utf8");
?>
