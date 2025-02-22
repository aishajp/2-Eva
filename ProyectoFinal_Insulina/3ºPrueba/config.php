<?php
// config.php
function conectarDB() {
    $db_host = 'localhost:8080';
    $db_user = 'root';
    $db_pass = '';  // Use environment variables for credentials
    $db_name = 'diabetesdb';
    
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
    
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }
    
    $conn->set_charset("utf8mb4");
    return $conn;
}
?>