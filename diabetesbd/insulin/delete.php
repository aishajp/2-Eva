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

$tipo = $_GET['tipo'] ?? ''; 
$tipo_comida = $_GET['tipo_comida'] ?? '';
$fecha = $_GET['fecha'] ?? date('Y-m-d');

// Verificar que la sesión de usuario esté activa
if (!isset($_SESSION['user_id'])) {
    $_SESSION['mensaje'] = "Error: Sesión inválida";
    $_SESSION['tipo_mensaje'] = "danger";
    header("Location: read.php");
    exit();
}

$id_usu = $_SESSION['user_id'];

if ($tipo && $tipo_comida && $fecha) {
    try {
        // Validar el nombre de la tabla para prevenir inyección SQL
        $allowedTables = ['comida', 'hipoglucemia', 'hiperglucemia'];
        if (!in_array($tipo, $allowedTables)) {
            throw new Exception("Nombre de tabla inválido");
        }
        
        // SQL para eliminar con clave compuesta
        $sql = "DELETE FROM $tipo WHERE tipo_comida = :tipo_comida AND fecha = :fecha AND id_usu = :id_usu";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':tipo_comida', $tipo_comida);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->bindParam(':id_usu', $id_usu);
        
        if ($stmt->execute()) {
            $_SESSION['mensaje'] = "Registro eliminado correctamente";
            $_SESSION['tipo_mensaje'] = "success";
        } else {
            $_SESSION['mensaje'] = "Error al eliminar el registro";
            $_SESSION['tipo_mensaje'] = "danger";
        }
    } catch(Exception $e) {
        $_SESSION['mensaje'] = "Error: " . $e->getMessage();
        $_SESSION['tipo_mensaje'] = "danger";
    }
}

header("Location: read.php?fecha=" . $fecha);
exit();
?>