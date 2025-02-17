<?php
session_start();
include 'conexion.php'; // Asegúrate de conectar con la BD

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $contra = $_POST['contra'];

    $sql = "SELECT id, usuario FROM usuarios WHERE usuario = '$usuario' AND contra = '$contra'";
    $resultado = $conexion->query($sql);

    if ($resultado->num_rows > 0) {
        $fila = $resultado->fetch_assoc();
        $_SESSION['usuario'] = $fila['usuario'];
        header("Location: inicio.html");
        exit();
    } else {
        echo "Usuario o contraseña incorrectos";
    }
}
?>
