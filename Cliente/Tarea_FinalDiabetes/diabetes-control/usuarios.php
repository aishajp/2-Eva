<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('HTTP/1.1 204 No Content');
    exit;
}
//funcion de conexion
function myConexion(){ 

    $localhost = "localhost"; 
    $username = "root";  
    $pw ="";  
    $database = "diabetesdb"; 
    $con = new mysqli($localhost, $username, $pw, $database); 

    if($con->connect_error) { 
        die("Connection failed: ". $con->connect_error); 
    } 
    return $con; 
} 
$conectada = myConexion();

// Lee todas las notas existentes del fichero
function getAllUsuarios($conectada) {
    $query = "SELECT * FROM usuario"; 
    $result = $conectada->query($query); 
    $listaUsuario = $result -> fetch_all(MYSQLI_ASSOC); 
    return $listaUsuario; 

} 
// Procesa las solicitudes según el método HTTP
$method = $_SERVER['REQUEST_METHOD']; 

    switch($method){ 
       case 'GET': 
          if(isset($_GET['id_usu'])){ 
            $id_usu = intval($_GET['id_usu']); 
            //$usu = getUsuById($id_usu, $conectad); 
            echo json_encode($usu ?: ['error'=>"No se encontró ningún usuario con ID $id_usu."]); 
          }else{ 
            $usuarios = getAllUsuarios($conectada); 
            echo json_encode($usuarios); 

          }   

        break; 

} 
?>    