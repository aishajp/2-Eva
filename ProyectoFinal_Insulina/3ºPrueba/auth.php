<?php
// auth.php - Archivo para verificar la autenticación
session_start();

function verificarSesion() {
    // Si no hay sesión activa, redirigir al login
    if (!isset($_SESSION['usuario_id'])) {
        header("Location: login.php");
        exit;
    }
}

// Función para redirigir si ya está logueado
function redirigirSiLogueado() {
    if (isset($_SESSION['usuario_id'])) {
        header("Location: tabla.php");
        exit;
    }
}