<?php

// Iniciar sesión solo si no hay una activa
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../includes/functions.php';
require_once '../includes/insulin_functions.php';
require_once '../config/database.php';

redirectIfNotLoggedIn();

$database = new Database();
$db = $database->getConnection();

$tipo = $_GET['tipo'] ?? ''; // Type of table (comida, hipoglucemia, etc.)
$id = $_GET['id'] ?? ''; // ID of the record
$fecha = $_GET['fecha'] ?? date('Y-m-d');

// Make sure user_id is defined in the session
if (!isset($_SESSION['user_id'])) {
    $_SESSION['mensaje'] = "Error: Invalid session";
    $_SESSION['tipo_mensaje'] = "danger";
    header("Location: read.php");
    exit();
}

$id_usu = $_SESSION['user_id'];

if ($tipo && $id && $fecha) {
    try {
        // Direct SQL query to delete by ID
        $sql = "DELETE FROM $tipo WHERE id = :id AND id_usu = :id_usu";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':id_usu', $id_usu);
        
        if ($stmt->execute()) {
            $_SESSION['mensaje'] = "Registro eliminado correctamente";
            $_SESSION['tipo_mensaje'] = "success";
        } else {
            $_SESSION['mensaje'] = "Error al eliminar el registro";
            $_SESSION['tipo_mensaje'] = "danger";
        }
    } catch(PDOException $e) {
        $_SESSION['mensaje'] = "Error en la base de datos: " . $e->getMessage();
        $_SESSION['tipo_mensaje'] = "danger";
    }
}

header("Location: read.php?fecha=" . $fecha);
exit();
?>