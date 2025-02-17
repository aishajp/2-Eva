<?php
require 'conexion.php'; // Asegúrate de tener tu conexión a la base de datos aquí.
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $contra = $_POST['contra'];

    // Buscar usuario en la base de datos
    $sql = "SELECT id, usuario, contrasena FROM usuarios WHERE usuario = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $usuario_db, $hashedPassword);
        $stmt->fetch();

        // Verificar la contraseña
        if (password_verify($contra, $hashedPassword)) {
            $_SESSION['usuario'] = $usuario_db;
            $_SESSION['id_usuario'] = $id;
            header("Location: inicio.html");
            exit();
        } else {
            echo "Contraseña incorrecta.";
        }
    } else {
        echo "Usuario no encontrado.";
    }

    $stmt->close();
    $conn->close();
}
?>