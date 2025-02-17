<?php
require 'conexion.php'; // Asegúrate de tener tu conexión a la base de datos aquí.

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $usuario = $_POST['usuario'];
    $contra = $_POST['contra'];

    // Hashear la contraseña
    $passwordHash = password_hash($contra, PASSWORD_DEFAULT);

    // Insertar en la base de datos
    $sql = "INSERT INTO usuarios (nombre, apellidos, fecha_nacimiento, usuario, contrasena) 
            VALUES (?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $nombre, $apellidos, $fecha_nacimiento, $usuario, $passwordHash);
    
    if ($stmt->execute()) {
        echo "Registro exitoso";
    } else {
        echo "Error al registrar: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
