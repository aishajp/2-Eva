<?php
session_start(); // Añadido session_start() para corregir
require_once '../includes/functions.php';

session_destroy();
header("Location: ../index.php");
exit();
?>