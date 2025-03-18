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
    $query = "SELECT id_usu, nombre_usuario, nombre, apellidos, fecha_nacimiento FROM usuario"; 
    $result = $conectada->query($query); 
    $listaUsuario = $result->fetch_all(MYSQLI_ASSOC); 
    return $listaUsuario; 
}

// Obtiene un usuario por su nombre de usuario
function getUsuByUsername($nombre_usuario, $conectada) {
    $query = "SELECT id_usu, nombre_usuario, nombre, apellidos, fecha_nacimiento FROM usuario WHERE nombre_usuario = ?";
    $stmt = $conectada->prepare($query);
    $stmt->bind_param("s", $nombre_usuario);
    $stmt->execute();
    $result = $stmt->get_result();
    $usuario = $result->fetch_assoc();
    $stmt->close();
    return $usuario;
}

// Crea un nuevo usuario
function createUsu($datos, $conectada) {
    // Verificar si ya existe el nombre de usuario
    $query_check = "SELECT COUNT(*) as count FROM usuario WHERE nombre_usuario = ?";
    $stmt_check = $conectada->prepare($query_check);
    $stmt_check->bind_param("s", $datos['nombre_usuario']);
    $stmt_check->execute();
    $result = $stmt_check->get_result();
    $count = $result->fetch_assoc()['count'];
    $stmt_check->close();
    
    if ($count > 0) {
        return [
            'success' => false,
            'error' => 'El nombre de usuario ya existe'
        ];
    }
    
    $query = "INSERT INTO usuario (nombre_usuario, contrasena, nombre, apellidos, fecha_nacimiento) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conectada->prepare($query);
    $stmt->bind_param("sssss", 
        $datos['nombre_usuario'], 
        $datos['contrasena'], 
        $datos['nombre'], 
        $datos['apellidos'], 
        $datos['fecha_nacimiento']
    );
    
    if ($stmt->execute()) {
        $id = $conectada->insert_id;
        $stmt->close();
        return [
            'success' => true,
            'usuario' => getUsuByUsername($datos['nombre_usuario'], $conectada)
        ];
    } else {
        $stmt->close();
        return [
            'success' => false,
            'error' => 'Error al crear el usuario: ' . $conectada->error
        ];
    }
}

// Actualiza un usuario existente
function updateUsu($nombre_usuario, $datos, $conectada) {
    // Verificar que el usuario existe
    $usuario_actual = getUsuByUsername($nombre_usuario, $conectada);
    if (!$usuario_actual) {
        return [
            'success' => false,
            'error' => 'El usuario no existe'
        ];
    }
    
    $query = "UPDATE usuario SET 
                contrasena = ?, 
                nombre = ?, 
                apellidos = ?, 
                fecha_nacimiento = ? 
              WHERE nombre_usuario = ?";
    
    $stmt = $conectada->prepare($query);
    $stmt->bind_param("sssss", 
        $datos['contrasena'], 
        $datos['nombre'], 
        $datos['apellidos'], 
        $datos['fecha_nacimiento'],
        $nombre_usuario
    );
    
    if ($stmt->execute()) {
        $stmt->close();
        return [
            'success' => true,
            'usuario' => getUsuByUsername($nombre_usuario, $conectada)
        ];
    } else {
        $stmt->close();
        return [
            'success' => false,
            'error' => 'Error al actualizar el usuario: ' . $conectada->error
        ];
    }
}

// Elimina un usuario por su nombre de usuario
function deleteUsuByUsername($nombre_usuario, $conectada) {
    // Verificar que el usuario existe
    $usuario_actual = getUsuByUsername($nombre_usuario, $conectada);
    if (!$usuario_actual) {
        return [
            'success' => false,
            'error' => 'El usuario no existe'
        ];
    }
    
    $query = "DELETE FROM usuario WHERE nombre_usuario = ?";
    $stmt = $conectada->prepare($query);
    $stmt->bind_param("s", $nombre_usuario);
    
    if ($stmt->execute()) {
        $stmt->close();
        return [
            'success' => true,
            'mensaje' => "Usuario '$nombre_usuario' eliminado correctamente"
        ];
    } else {
        $stmt->close();
        return [
            'success' => false,
            'error' => 'Error al eliminar el usuario: ' . $conectada->error
        ];
    }
}

// Procesa las solicitudes según el método HTTP
$method = $_SERVER['REQUEST_METHOD']; 

switch($method) { 
    case 'GET': 
        if(isset($_GET['nombre_usuario'])) { 
            $nombre_usuario = $_GET['nombre_usuario']; 
            $usu = getUsuByUsername($nombre_usuario, $conectada); 
            echo json_encode($usu ?: ['error' => "No se encontró ningún usuario con nombre '$nombre_usuario'"]); 
        } else { 
            $usuarios = getAllUsuarios($conectada); 
            echo json_encode($usuarios); 
        }   
        break;
        
    case 'POST':
        $datos = json_decode(file_get_contents('php://input'), true);
        if ($datos) {
            $resultado = createUsu($datos, $conectada);
            if ($resultado['success']) {
                http_response_code(201); // Created
                echo json_encode($resultado);
            } else {
                http_response_code(400); // Bad Request
                echo json_encode($resultado);
            }
        } else {
            http_response_code(400); // Bad Request
            echo json_encode(['error' => 'Datos inválidos']);
        }
        break;
        
    case 'PUT':
        if (isset($_GET['nombre_usuario'])) {
            $nombre_usuario = $_GET['nombre_usuario'];
            $datos = json_decode(file_get_contents('php://input'), true);
            if ($datos) {
                $resultado = updateUsu($nombre_usuario, $datos, $conectada);
                if ($resultado['success']) {
                    echo json_encode($resultado);
                } else {
                    http_response_code(400); // Bad Request
                    echo json_encode($resultado);
                }
            } else {
                http_response_code(400); // Bad Request
                echo json_encode(['error' => 'Datos inválidos']);
            }
        } else {
            http_response_code(400); // Bad Request
            echo json_encode(['error' => 'Se requiere un nombre de usuario']);
        }
        break;
        
    case 'DELETE':
        if (isset($_GET['nombre_usuario'])) {
            $nombre_usuario = $_GET['nombre_usuario'];
            $resultado = deleteUsuByUsername($nombre_usuario, $conectada);
            if ($resultado['success']) {
                http_response_code(200); // OK
                echo json_encode($resultado);
            } else {
                http_response_code(400); // Bad Request
                echo json_encode($resultado);
            }
        } else {
            http_response_code(400); // Bad Request
            echo json_encode(['error' => 'Se requiere un nombre de usuario']);
        }
        break;
        
    default:
        http_response_code(405); // Method Not Allowed
        echo json_encode(['error' => 'Método no permitido']);
        break;
} 

$conectada->close();
?>