<?php    
    require_once './ctdb.php';
    header('Access-Control-Allow-Origin: http://localhost:5173');
    header('Access-Control-Allow-Method: POST');
    header('Access-Control-Allow-Headers: Content-Type');
    header('Content-Type: application/json');
    try{
        $stmt = $conn -> prepare ('SELECT nombre,apellido,fechaNac,username FROM usuario');
        if(!$stmt){
            throw new Exception("Error al preparar la consulta: ". $conn->error);
        }
        $stmt -> execute();
        $stmt->bind_result($nombre, $apellido, $fechaNac, $username);

        while($stmt->fetch()){
                $result[]= ["nombre"=>$nombre, "apellido"=>$apellido, "fechaNac"=>$fechaNac,"username"=>$username];
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