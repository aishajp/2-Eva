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

// Lee todos los usuarios
function getAllUsuarios($conectada) {
    $query = "SELECT * FROM usuario"; 
    $result = $conectada->query($query); 
    $listaUsuario = $result->fetch_all(MYSQLI_ASSOC); 
    return $listaUsuario; 
}

// Obtiene un usuario por su ID
function getUsuById($id_usu, $conectada) {
    $query = "SELECT * FROM usuario WHERE id_usu = ?";
    $stmt = $conectada->prepare($query);
    $stmt->bind_param("i", $id_usu);
    $stmt->execute();
    $result = $stmt->get_result();
    $usuario = $result->fetch_assoc();
    $stmt->close();
    return $usuario;
}

// Crea un nuevo usuario
function createUsu($datos, $conectada) {
    // Ajusta estos campos según la estructura de tu tabla usuario
    $query = "INSERT INTO usuario (nombre, apellido, correo, contrasena, fecha_nacimiento, genero) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conectada->prepare($query);
    $stmt->bind_param("ssssss", 
        $datos['nombre'], 
        $datos['apellido'], 
        $datos['correo'], 
        $datos['contrasena'], 
        $datos['fecha_nacimiento'], 
        $datos['genero']
    );
    
    if ($stmt->execute()) {
        $id = $conectada->insert_id;
        $stmt->close();
        return getUsuById($id, $conectada);
    } else {
        $stmt->close();
        return false;
    }
}

// Actualiza un usuario existente
function updateUsu($id_usu, $datos, $conectada) {
    // Ajusta estos campos según la estructura de tu tabla usuario
    $query = "UPDATE usuario SET 
                nombre = ?, 
                apellido = ?, 
                correo = ?, 
                contrasena = ?, 
                fecha_nacimiento = ?, 
                genero = ? 
              WHERE id_usu = ?";
    
    $stmt = $conectada->prepare($query);
    $stmt->bind_param("ssssssi", 
        $datos['nombre'], 
        $datos['apellido'], 
        $datos['correo'], 
        $datos['contrasena'], 
        $datos['fecha_nacimiento'], 
        $datos['genero'],
        $id_usu
    );
    
    $success = $stmt->execute();
    $stmt->close();
    
    if ($success) {
        return getUsuById($id_usu, $conectada);
    } else {
        return false;
    }
}

// Elimina un usuario por su ID
function deleteUsuById($id_usu, $conectada) {
    $query = "DELETE FROM usuario WHERE id_usu = ?";
    $stmt = $conectada->prepare($query);
    $stmt->bind_param("i", $id_usu);
    $success = $stmt->execute();
    $stmt->close();
    return $success;
}

// Procesa las solicitudes según el método HTTP
$method = $_SERVER['REQUEST_METHOD']; 

switch($method) { 
    case 'GET': 
        if(isset($_GET['id_usu'])) { 
            $id_usu = intval($_GET['id_usu']); 
            $usu = getUsuById($id_usu, $conectada); 
            echo json_encode($usu ?: ['error' => "No se encontró ningún usuario con ID $id_usu."]); 
        } else { 
            $usuarios = getAllUsuarios($conectada); 
            echo json_encode($usuarios); 
        }   
        break;
        
    case 'POST':
        $datos = json_decode(file_get_contents('php://input'), true);
        if ($datos) {
            $nuevo_usuario = createUsu($datos, $conectada);
            if ($nuevo_usuario) {
                http_response_code(201); // Created
                echo json_encode($nuevo_usuario);
            } else {
                http_response_code(500); // Internal Server Error
                echo json_encode(['error' => 'No se pudo crear el usuario']);
            }
        } else {
            http_response_code(400); // Bad Request
            echo json_encode(['error' => 'Datos inválidos']);
        }
        break;
        
    case 'PUT':
        if (isset($_GET['id_usu'])) {
            $id_usu = intval($_GET['id_usu']);
            $datos = json_decode(file_get_contents('php://input'), true);
            if ($datos) {
                $usuario_actualizado = updateUsu($id_usu, $datos, $conectada);
                if ($usuario_actualizado) {
                    echo json_encode($usuario_actualizado);
                } else {
                    http_response_code(500); // Internal Server Error
                    echo json_encode(['error' => 'No se pudo actualizar el usuario']);
                }
            } else {
                http_response_code(400); // Bad Request
                echo json_encode(['error' => 'Datos inválidos']);
            }
        } else {
            http_response_code(400); // Bad Request
            echo json_encode(['error' => 'Se requiere un ID de usuario']);
        }
        break;
        
    case 'DELETE':
        if (isset($_GET['id_usu'])) {
            $id_usu = intval($_GET['id_usu']);
            $resultado = deleteUsuById($id_usu, $conectada);
            if ($resultado) {
                http_response_code(200); // OK
                echo json_encode(['mensaje' => "Usuario con ID $id_usu eliminado correctamente"]);
            } else {
                http_response_code(500); // Internal Server Error
                echo json_encode(['error' => 'No se pudo eliminar el usuario']);
            }
        } else {
            http_response_code(400); // Bad Request
            echo json_encode(['error' => 'Se requiere un ID de usuario']);
        }
        break;
        
    default:
        http_response_code(405); // Method Not Allowed
        echo json_encode(['error' => 'Método no permitido']);
        break;
} 

$conectada->close();
?>