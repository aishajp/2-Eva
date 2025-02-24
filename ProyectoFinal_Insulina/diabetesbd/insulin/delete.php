<?php
require_once '../includes/functions.php';
require_once '../includes/insulin_functions.php';
require_once '../config/database.php';

redirectIfNotLoggedIn();

$database = new Database();
$db = $database->getConnection();

$tipo = $_GET['tipo'] ?? '';
$id = $_GET['id'] ?? '';
$fecha = $_GET['fecha'] ?? date('Y-m-d');

if ($tipo && $id) {
    if (eliminarRegistro($db, $tipo, $id)) {
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