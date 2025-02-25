<?php
require_once 'functions.php';
require_once '../config/database.php';

function guardarRegistroComida($db, $datos) {
    try {
        $sql = "INSERT INTO comida (tipo_comida, gl_1h, gl_2h, raciones, insulina, fecha, id_usu) 
                VALUES (:tipo_comida, :gl_1h, :gl_2h, :raciones, :insulina, :fecha, :id_usu)";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':tipo_comida' => $datos['tipo_comida'],
            ':gl_1h' => $datos['gl_1h'],
            ':gl_2h' => $datos['gl_2h'],
            ':raciones' => $datos['raciones'],
            ':insulina' => $datos['insulina'],
            ':fecha' => $datos['fecha'],
            ':id_usu' => $_SESSION['user_id']
        ]);
        return true;
    } catch(PDOException $e) {
       // Log the error message
       error_log("Error al guardar comida: " . $e->getMessage());
       return false;
   }
}

function guardarRegistroHipo($db, $datos) {
    try {
        $sql = "INSERT INTO hipoglucemia (glucosa, hora, tipo_comida, fecha, id_usu) 
                VALUES (:glucosa, :hora, :tipo_comida, :fecha, :id_usu)";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':glucosa' => $datos['glucosa'],
            ':hora' => $datos['hora'],
            ':tipo_comida' => $datos['tipo_comida'],
            ':fecha' => $datos['fecha'],
            ':id_usu' => $_SESSION['user_id']
        ]);
        return true;
    } catch(PDOException $e) {
        return false;
    }
}

function guardarRegistroHiper($db, $datos) {
    try {
        $sql = "INSERT INTO hiperglucemia (glucosa, hora, correccion, tipo_comida, fecha, id_usu) 
                VALUES (:glucosa, :hora, :correccion, :tipo_comida, :fecha, :id_usu)";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':glucosa' => $datos['glucosa'],
            ':hora' => $datos['hora'],
            ':correccion' => $datos['correccion'],
            ':tipo_comida' => $datos['tipo_comida'],
            ':fecha' => $datos['fecha'],
            ':id_usu' => $_SESSION['user_id']
        ]);
        return true;
    } catch(PDOException $e) {
        return false;
    }
}

function obtenerRegistrosDia($db, $fecha, $id_usuario) {
    $registros = [
        'comidas' => [],
        'hipos' => [],
        'hipers' => []
    ];
    
    // Obtener comidas
    $sql = "SELECT * FROM comida WHERE fecha = :fecha AND id_usu = :id_usu";
    $stmt = $db->prepare($sql);
    $stmt->execute([':fecha' => $fecha, ':id_usu' => $id_usuario]);
    $registros['comidas'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Obtener hipoglucemias
    $sql = "SELECT * FROM hipoglucemia WHERE fecha = :fecha AND id_usu = :id_usu";
    $stmt = $db->prepare($sql);
    $stmt->execute([':fecha' => $fecha, ':id_usu' => $id_usuario]);
    $registros['hipos'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Obtener hiperglucemias
    $sql = "SELECT * FROM hiperglucemia WHERE fecha = :fecha AND id_usu = :id_usu";
    $stmt = $db->prepare($sql);
    $stmt->execute([':fecha' => $fecha, ':id_usu' => $id_usuario]);
    $registros['hipers'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    return $registros;
}

function actualizarRegistroComida($db, $id, $datos) {
    try {
        $sql = "UPDATE comida SET 
                gl_1h = :gl_1h,
                gl_2h = :gl_2h,
                raciones = :raciones,
                insulina = :insulina
                WHERE id = :id AND id_usu = :id_usu";
        
        $stmt = $db->prepare($sql);
        $datos[':id'] = $id;
        $datos[':id_usu'] = $_SESSION['user_id'];
        $stmt->execute($datos);
        return true;
    } catch(PDOException $e) {
        return false;
    }
}

function eliminarRegistro($db, $tabla, $id) {
    try {
        $sql = "DELETE FROM $tabla WHERE id = :id AND id_usu = :id_usu";
        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':id' => $id,
            ':id_usu' => $_SESSION['user_id']
        ]);
        return true;
    } catch(PDOException $e) {
        return false;
    }
}
?>