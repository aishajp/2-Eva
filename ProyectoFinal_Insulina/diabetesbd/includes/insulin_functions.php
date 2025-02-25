<?php
require_once 'functions.php';
require_once '../config/database.php';

function guardarRegistroComida($db, $datos) {
    try {
        // Verificamos que id_usu esté definido y no sea nulo
        if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
            error_log("Error: user_id no está definido en la sesión");
            return false;
        }

        // ID del usuario desde la sesión
        $id_usuario = $_SESSION['user_id'];

        // Verificar si existe un registro en control_glucosa
        $sqlCheck = "SELECT COUNT(*) FROM control_glucosa WHERE fecha = :fecha AND id_usu = :id_usu";
        $stmtCheck = $db->prepare($sqlCheck);
        $stmtCheck->execute([
            ':fecha' => $datos['fecha'],
            ':id_usu' => $id_usuario
        ]);
        $exists = $stmtCheck->fetchColumn();

        // Si no existe, insertamos un registro vacío
        if (!$exists) {
            $sqlInsert = "INSERT INTO control_glucosa (fecha, id_usu) VALUES (:fecha, :id_usu)";
            $stmtInsert = $db->prepare($sqlInsert);
            $stmtInsert->execute([
                ':fecha' => $datos['fecha'],
                ':id_usu' => $id_usuario
            ]);
        }

        // Ahora sí, insertamos en la tabla comida
        $sql = "INSERT INTO comida (tipo_comida, gl_1h, gl_2h, raciones, insulina, fecha, id_usu) 
                VALUES (:tipo_comida, :gl_1h, :gl_2h, :raciones, :insulina, :fecha, :id_usu)";

        $stmt = $db->prepare($sql);
        $result = $stmt->execute([
            ':tipo_comida' => $datos['tipo_comida'],
            ':gl_1h' => $datos['gl_1h'] ?: 0,
            ':gl_2h' => $datos['gl_2h'] ?: 0,
            ':raciones' => $datos['raciones'] ?: 0,
            ':insulina' => $datos['insulina'] ?: 0,
            ':fecha' => $datos['fecha'],
            ':id_usu' => $id_usuario
        ]);

        if ($result) {
            return true;
        } else {
            error_log("Error PDO: " . print_r($stmt->errorInfo(), true));
            return false;
        }
    } catch (PDOException $e) {
        error_log("Error PDO Exception: " . $e->getMessage());
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
    } catch (PDOException $e) {
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
    } catch (PDOException $e) {
        return false;
    }
}

function obtenerRegistrosDia($db, $fecha, $id_usuario) {
    $registros = [
        'comidas' => [],
        'hipos' => [],
        'hipers' => []
    ];

    // Verificar que la columna correcta existe en la tabla comida
    $sql = "SELECT id, tipo_comida, gl_1h, gl_2h, raciones, insulina, fecha, id_usu FROM comida WHERE fecha = :fecha AND id_usu = :id_usu";
    $stmt = $db->prepare($sql);
    $stmt->execute([':fecha' => $fecha, ':id_usu' => $id_usuario]);
    $registros['comidas'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Obtener registros de hipoglucemia
    $sql = "SELECT id_hipo, glucosa, hora, tipo_comida, fecha, id_usu FROM hipoglucemia WHERE fecha = :fecha AND id_usu = :id_usu";
    $stmt = $db->prepare($sql);
    $stmt->execute([':fecha' => $fecha, ':id_usu' => $id_usuario]);
    $registros['hipos'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Obtener registros de hiperglucemia
    $sql = "SELECT id_hiper, glucosa, hora, fecha, id_usu FROM hiperglucemia WHERE fecha = :fecha AND id_usu = :id_usu";
    $stmt = $db->prepare($sql);
    $stmt->execute([':fecha' => $fecha, ':id_usu' => $id_usuario]);
    $registros['hipers'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $registros;
}

function actualizarRegistroComida($db, $tipo_comida, $fecha, $id_usu, $datos) {
    try {
        $sql = "UPDATE comida SET 
                gl_1h = :gl_1h,
                gl_2h = :gl_2h,
                raciones = :raciones,
                insulina = :insulina
                WHERE tipo_comida = :tipo_comida AND fecha = :fecha AND id_usu = :id_usu";

        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':gl_1h' => $datos['gl_1h'],
            ':gl_2h' => $datos['gl_2h'],
            ':raciones' => $datos['raciones'],
            ':insulina' => $datos['insulina'],
            ':tipo_comida' => $tipo_comida,
            ':fecha' => $fecha,
            ':id_usu' => $id_usu
        ]);
        return true;
    } catch (PDOException $e) {
        return false;
    }
}
function eliminarRegistro($db, $tabla, $tipo, $fecha, $id_usu) {
    try {
        // Ajusta según la tabla específica
        if ($tabla == 'comida') {
            $sql = "DELETE FROM comida WHERE tipo_comida = :tipo AND fecha = :fecha AND id_usu = :id_usu";
            $stmt = $db->prepare($sql);
            $stmt->execute([
                ':tipo' => $tipo,
                ':fecha' => $fecha,
                ':id_usu' => $id_usu
            ]);
            return true;
        }
        
        // Añade casos para otras tablas basándote en sus estructuras reales
        
        return false;
    } catch (PDOException $e) {
        return false;
    }
}
?>
