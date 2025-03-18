<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('HTTP/1.1 204 No Content');
    exit;
}

// Ruta al archivo JSON de usuarios
$filePath = 'usuarios.json';

// Lee todos los usuarios existentes del fichero
function getAllUsuarios($filePath) {
    if (file_exists($filePath)) {
        $data = file_get_contents($filePath);

        // Se decodifica el JSON en un array asociativo
        $retValue = json_decode($data, true);

        // Se verifica que retValue es un array y que no está vacío
        if (is_array($retValue) && !empty($retValue)) {
            // Se ordena por id
            usort($retValue, function ($a, $b) {
                return $a['id'] <=> $b['id']; // Operador de comparación espaciada (<=>)
            });
            
            // Se devuelve el array ordenado
            return $retValue;
        }
    }
    // Si el archivo no existe o está vacío, se devuelve un array vacío
    return [];
}

// Obtiene un usuario por su nombre de usuario
function getUsuarioByUsername($username, $filePath) {
    $usuarios = getAllUsuarios($filePath);
    // Se usa array_filter para filtrar por nombre de usuario
    $usuarioFiltrado = array_filter($usuarios, function ($usuario) use ($username) {
        return $usuario['username'] == $username;
    });
    // Si se encontró el usuario, se devuelve el primer elemento del array resultante
    return !empty($usuarioFiltrado) ? array_values($usuarioFiltrado)[0] : null;
}

// Crea un nuevo usuario y lo guarda en el archivo JSON
function createUsuario($username, $password, $nombre, $apellidos, $fechaNacimiento, $otros, $filePath) {
    $usuarios = getAllUsuarios($filePath);
    
    // Verificamos que el nombre de usuario no exista ya
    foreach ($usuarios as $usuario) {
        if ($usuario['username'] == $username) {
            return ['error' => 'El nombre de usuario ya existe.'];
        }
    }
    
    // Genera un nuevo ID único
    $id = isset($usuarios[count($usuarios) - 1]['id']) ? $usuarios[count($usuarios) - 1]['id'] + 1 : 1;
    
    // Creamos el hash de la contraseña
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    
    // Crea el nuevo usuario
    $nuevoUsuario = [
        'id' => $id,
        'username' => $username,
        'password' => $passwordHash,
        'nombre' => $nombre,
        'apellidos' => $apellidos,
        'fechaNacimiento' => $fechaNacimiento,
        'otros' => $otros
    ];
    
    // Agrega el nuevo usuario al array de usuarios
    $usuarios[] = $nuevoUsuario;
    
    // Guardar los usuarios actualizados en el archivo JSON
    file_put_contents($filePath, json_encode($usuarios, JSON_PRETTY_PRINT));
    
    // Devolvemos el usuario pero sin la contraseña
    $nuevoUsuario['password'] = '********';
    return $nuevoUsuario;
}

// Actualiza un usuario existente por su nombre de usuario
function updateUsuarioByUsername($username, $password, $nombre, $apellidos, $fechaNacimiento, $otros, $filePath) {
    $usuarios = getAllUsuarios($filePath);
    $usuarioExistente = null;
    
    // Buscamos el usuario para obtener su ID
    foreach ($usuarios as $usuario) {
        if ($usuario['username'] == $username) {
            $usuarioExistente = $usuario;
            break;
        }
    }
    
    // Si no encontramos el usuario, devolvemos error
    if ($usuarioExistente === null) {
        return ['error' => 'No se encontró el usuario con el nombre de usuario proporcionado.'];
    }
    
    // Procesamos la contraseña solo si se proporciona una nueva
    $passwordHash = $password ? password_hash($password, PASSWORD_DEFAULT) : $usuarioExistente['password'];
    
    // Actualizamos los usuarios
    $usuariosActualizados = array_map(function ($usuario) use ($username, $passwordHash, $nombre, $apellidos, $fechaNacimiento, $otros) {
        if ($usuario['username'] == $username) {
            return [
                'id' => $usuario['id'],
                'username' => $username, // El username no se puede cambiar
                'password' => $passwordHash,
                'nombre' => $nombre,
                'apellidos' => $apellidos,
                'fechaNacimiento' => $fechaNacimiento,
                'otros' => $otros
            ];
        }
        return $usuario;
    }, $usuarios);
    
    // Guardamos los usuarios actualizados
    file_put_contents($filePath, json_encode($usuariosActualizados, JSON_PRETTY_PRINT));
    
    // Devolvemos el usuario actualizado sin la contraseña
    $usuarioActualizado = getUsuarioByUsername($username, $filePath);
    $usuarioActualizado['password'] = '********';
    return $usuarioActualizado;
}

// Elimina un usuario por su nombre de usuario
function deleteUsuarioByUsername($username, $filePath) {
    $usuarios = getAllUsuarios($filePath);
    
    // Verificamos que el usuario existe
    $existe = false;
    foreach ($usuarios as $usuario) {
        if ($usuario['username'] == $username) {
            $existe = true;
            break;
        }
    }
    
    if (!$existe) {
        return ['error' => "No se encontró ningún usuario con nombre de usuario '$username'."];
    }
    
    // Filtramos el usuario a eliminar
    $usuariosFiltrados = array_filter($usuarios, function ($usuario) use ($username) {
        return $usuario['username'] != $username;
    });
    
    // Guardamos los usuarios actualizados
    file_put_contents($filePath, json_encode(array_values($usuariosFiltrados), JSON_PRETTY_PRINT));
    
    return ['message' => "Usuario '$username' eliminado correctamente."];
}

// Procesa las solicitudes según el método HTTP
switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        if (isset($_GET['username'])) {
            $username = $_GET['username'];
            $usuario = getUsuarioByUsername($username, $filePath);
            
            if ($usuario) {
                // Ocultamos la contraseña en la respuesta
                $usuario['password'] = '********';
                echo json_encode($usuario);
            } else {
                http_response_code(404); // No encontrado
                echo json_encode(['error' => "No se encontró ningún usuario con nombre de usuario '$username'."]);
            }
        } else {
            $usuarios = getAllUsuarios($filePath);
            
            // Ocultamos las contraseñas en la respuesta
            foreach ($usuarios as &$usuario) {
                $usuario['password'] = '********';
            }
            
            echo json_encode($usuarios);
        }
        break;
        
    case 'POST':
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (
            isset($input['username']) && 
            isset($input['password']) && 
            isset($input['nombre']) && 
            isset($input['apellidos']) && 
            isset($input['fechaNacimiento'])
        ) {
            $otros = isset($input['otros']) ? $input['otros'] : '';
            $resultado = createUsuario(
                $input['username'], 
                $input['password'], 
                $input['nombre'], 
                $input['apellidos'], 
                $input['fechaNacimiento'], 
                $otros, 
                $filePath
            );
            
            if (isset($resultado['error'])) {
                http_response_code(400); // Bad Request
                echo json_encode($resultado);
            } else {
                http_response_code(201); // Created
                echo json_encode($resultado);
            }
        } else {
            http_response_code(400); // Bad Request
            echo json_encode(['error' => 'Datos incompletos para crear el usuario.']);
        }
        break;
        
    case 'PUT':
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (isset($input['username'])) {
            // Comprobamos si el usuario existe
            $usuario = getUsuarioByUsername($input['username'], $filePath);
            
            if (!$usuario) {
                http_response_code(404); // Not Found
                echo json_encode(['error' => "No se encontró ningún usuario con nombre de usuario '{$input['username']}'."]);
                break;
            }
            
            // Usamos los valores actuales si no se proporcionan nuevos
            $password = isset($input['password']) && !empty($input['password']) ? $input['password'] : null;
            $nombre = isset($input['nombre']) ? $input['nombre'] : $usuario['nombre'];
            $apellidos = isset($input['apellidos']) ? $input['apellidos'] : $usuario['apellidos'];
            $fechaNacimiento = isset($input['fechaNacimiento']) ? $input['fechaNacimiento'] : $usuario['fechaNacimiento'];
            $otros = isset($input['otros']) ? $input['otros'] : $usuario['otros'];
            
            $resultado = updateUsuarioByUsername(
                $input['username'], 
                $password, 
                $nombre, 
                $apellidos, 
                $fechaNacimiento, 
                $otros, 
                $filePath
            );
            
            echo json_encode($resultado);
        } else {
            http_response_code(400); // Bad Request
            echo json_encode(['error' => 'Nombre de usuario no proporcionado para actualizar.']);
        }
        break;
        
    case 'DELETE':
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (isset($input['username'])) {
            $resultado = deleteUsuarioByUsername($input['username'], $filePath);
            
            if (isset($resultado['error'])) {
                http_response_code(404); // Not Found
            }
            
            echo json_encode($resultado);
        } else {
            http_response_code(400); // Bad Request
            echo json_encode(['error' => 'Nombre de usuario no proporcionado para eliminar.']);
        }
        break;
        
    default:
        http_response_code(405); // Method Not Allowed
        echo json_encode(['error' => 'Método no permitido.']);
}
?>