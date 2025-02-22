<?php
// auth.php
session_start();

function verificarSesion() {
    if (!isset($_SESSION['usuario_id'])) {
        header("Location: login.php");
        exit;
    }
}
?>