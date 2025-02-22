<?php
// registro.php
session_start();
require_once "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = conectarDB();
    
    // Limpieza y validación de datos
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $apellidos = $conn->real_escape_string($_POST['apellidos']);
    $fechaNacimiento = $conn->real_escape_string($_POST['fecha_nacimiento']);
    $usuario = $conn->real_escape_string($_POST['usuario']);
    $contra = $_POST['contra'];
    
    // Validar usuario existente
    $stmt = $conn->prepare("SELECT * FROM usuario WHERE usuario = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($result->num_rows > 0) {
        echo "El usuario ya existe.";
        exit;
    }
    
    // Insertar nuevo usuario
    $stmt = $conn->prepare("INSERT INTO usuario (fecha_nacimiento, nombre, apellidos, usuario, contra) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $fechaNacimiento, $nombre, $apellidos, $usuario, $contra);
    
    if ($stmt->execute()) {
        $_SESSION['usuario_id'] = $conn->insert_id;
        $_SESSION['usuario'] = $usuario;
        
        header("Location: tabla.php");
        exit;
    } else {
        echo "Error al registrar el usuario: " . $conn->error;
    }
    
    $stmt->close();
    $conn->close();
}
?>