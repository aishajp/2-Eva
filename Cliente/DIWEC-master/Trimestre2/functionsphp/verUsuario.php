<?php    
    require_once './ctdb.php';
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Method: GET,POST');
    header('Access-Control-Allow-Headers: Content-Type');
    header('Content-Type: application/json');
try{
    $eData = file_get_contents("php://input");
    $dData = json_decode($eData, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Error al decodificar JSON: " . json_last_error_msg());
    }

    $username = $dData['username1'];

    $result= "";
    
    if($username){
        $stmt = $conn -> prepare ('SELECT nombre,apellido,fechaNac,username FROM usuario where username = ?');
        if(!$stmt){
            throw new Exception("Error al preparar la consulta: ". $conn->error);
        }
        $stmt -> bind_param('s', $username);
        $stmt -> execute();
        $stmt->bind_result($nombre, $apellido, $fechaNac, $username);

        if($stmt->fetch()){
            $result= ["nombre"=>$nombre, "apellido"=>$apellido, "fechaNac"=>$fechaNac,"username"=>$username];
        }else{
            $result="Ha ocurrido un problema en la obtencion del usuario";
        }
    }else{
        $result="No se proporciono usuario";
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