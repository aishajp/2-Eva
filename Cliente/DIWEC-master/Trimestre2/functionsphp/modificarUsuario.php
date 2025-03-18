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

    $username = isset($dData['username'])?$dData['username']:"";
    $nombre =  isset($dData['nombre'])?$dData['nombre']:"";
    $apellido =  isset($dData['apellido'])?$dData['apellido']:"";
    $fechaNac = isset($dData['fechaNac'])?$dData['fechaNac']:"";
    $password = isset($dData['password'])?password_hash($dData['password'], PASSWORD_BCRYPT):"";

    $result= "";
    
    if($username){
        $sentencia = 'UPDATE usuario SET ';
        $tipoDato='';
        $datos=[];
        if($nombre){
            $sentencia.="nombre=?,";
            $tipoDato.="s";
            array_push($nombre,$datos);
        }
        if($apellido){
            $sentencia.="apellido=?,";
            $tipoDato.="s";
            array_push($apellido,$datos);
        }
        if($fechaNac){
            $sentencia.="fechaNac=?,";
            $tipoDato.="s";
            array_push($fechaNac,$datos);
        }
        if($password){
            $sentencia.="password=?,";
            $tipoDato.="s";
            array_push($password,$datos);
        }
        $sentencia.=" WHERE username = ?";
        $stmt = $conn -> prepare ($datos);
        if(!$stmt){
            throw new Exception("Error al preparar la consulta: ". $conn->error);
        }
        $stmt -> bind_param($tipoDato,$datos, $username);
        $stmt -> execute();

        if($stmt->fetch()){
            $result= "Usuario Modificado Correctamente";
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