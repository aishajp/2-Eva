<?php
require_once '../includes/functions.php';
require_once '../includes/insulin_functions.php';
require_once '../config/database.php';

redirectIfNotLoggedIn();

$database = new Database();
$db = $database->getConnection();

$tipo_tabla = $_GET['tipo'] ?? ''; // Tipo de tabla (comida, hipoglucemia, etc.)
$tipo_comida = $_GET['tipo_comida'] ?? ''; // Para la tabla comida
$fecha = $_GET['fecha'] ?? date('Y-m-d');
$id_usu = $_SESSION['user_id'];

if ($tipo_tabla && $tipo_comida && $fecha) {
    if (eliminarRegistro($db, $tipo_tabla, $tipo_comida, $fecha, $id_usu)) {
        $_SESSION['mensaje'] = "Registro eliminado correctamente";
        $_SESSION['tipo_mensaje'] = "success";
    } else {
        $_SESSION['mensaje'] = "Error al eliminar el registro";
        $_SESSION['tipo_mensaje'] = "danger";
    }
}

header("Location: read.php?fecha=" . $fecha);
exit();
?>