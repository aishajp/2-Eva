<?php    
    require_once './ctdb.php';
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Method: POST');
    header('Access-Control-Allow-Headers: Content-Type');
    header('Content-Type: application/json');
try{
    $eData = file_get_contents("php://input");
    $dData = json_decode($eData, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Error al decodificar JSON: " . json_last_error_msg());
    }

    $nombre =  $dData['nombre'];
    $apellido =  $dData['apellido'];
    $fechaNac = $dData['fechaNac'];
    $username = $dData['username'];
    $password = password_hash($dData['password'], PASSWORD_BCRYPT);

    $result= "";
    
    if($nombre && $apellido && $fechaNac && $username && $password){
        $stmt = $conn -> prepare ('INSERT INTO usuario (nombre,apellido,fechaNac,username,password)
                                    VALUES (?,?,?,?,?);');
        if(!$stmt){
            throw new Exception("Error al preparar la consulta: ". $conn->error);
        }
        $stmt -> bind_param('sssss', $nombre, $apellido,$fechaNac ,$username, $password);
        $stmt -> execute();

        if($stmt->affected_rows){
            $result="Usuario creado correctamente";
        }else{
            $result="Ha ocurrido un problema en la creacion del usuario";
        }
    }else{
        $result="Todos los campos son requeridos";
    }
    $conn->close();
}catch(Exception $e){
    $result= "Error: ".$e->getMessage();
}
    $jsonresponse = ['result'=>$result];
    $response = json_encode($jsonresponse);
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo "Error al decodificar JSON: " . json_last_error_msg();
    }else{
        echo $response;
    }
?>