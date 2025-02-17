<?php
session_start();
session_destroy();
header("Location: inicio.html"); // Redirige a la página de inicio
exit();
?>
