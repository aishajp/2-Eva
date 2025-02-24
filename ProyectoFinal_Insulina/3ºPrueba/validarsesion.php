<?php 

session_start(); 
require_once "config.php";   
$usuario = $_POST['usuario']; 
$contra = $_POST['contra']; 

// Conectar a la base de datos 
$con = new mysqli($db_host, $db_user, $db_pass, $db_name); 

// Verificar conexión 
if ($con->connect_error) { 

    die("Error de conexión: " . $con->connect_error); 

} 

// Usar consultas preparadas para seguridad 

$stmt = $con->prepare("SELECT * FROM usuario WHERE usuario = ? AND contra = ?"); 

$stmt->bind_param("ss", $usuario, $contra); 

$stmt->execute(); 

$result = $stmt->get_result(); 

if ($result->num_rows > 0) { 

    $_SESSION['usuario'] = $usuario; 

    header("Location: formularios.html"); 

    exit(); 

} else { 

    header("Location: index.html?error=1"); 

    exit(); 

} 

?> 