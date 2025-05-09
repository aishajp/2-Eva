<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION["login"])) {
    header("Location: index.php");
    exit();
}

// Configuración de la conexión a la base de datos
$servidor = "localhost:3307";
$usuario = "root";
$password = "";
$bd = "jerogrifico";

// Crear conexión
$conexion = new mysqli($servidor, $usuario, $password, $bd);

// Verificar conexión
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Obtener la fecha actual
$fecha_actual = date("Y-m-d");

// Inicializar variables
$solucion_correcta = "No disponible";
$resultado_acertantes = null;
$resultado_fallados = null;

// Obtener la solución correcta del jeroglífico para el día actual
$sql_solucion = "SELECT solucion FROM solucion WHERE fecha = '$fecha_actual'";
$resultado_solucion = $conexion->query($sql_solucion);

if ($resultado_solucion->num_rows > 0) {
    $fila_solucion = $resultado_solucion->fetch_assoc();
    $solucion_correcta = $fila_solucion["solucion"];
    
    // Obtener jugadores que han acertado (respuesta coincide con la solución)
    $sql_acertantes = "SELECT j.nombre, r.hora, r.login 
                      FROM respuestas r 
                      JOIN jugador j ON r.login = j.login 
                      WHERE r.fecha = '$fecha_actual' 
                      AND LOWER(r.respuesta) = LOWER('$solucion_correcta')
                      ORDER BY r.hora";
    $resultado_acertantes = $conexion->query($sql_acertantes);
    
    // Obtener jugadores que han fallado (respuesta no coincide con la solución)
    $sql_fallados = "SELECT j.nombre, r.hora, r.respuesta 
                    FROM respuestas r 
                    JOIN jugador j ON r.login = j.login 
                    WHERE r.fecha = '$fecha_actual' 
                    AND LOWER(r.respuesta) != LOWER('$solucion_correcta')
                    ORDER BY r.hora";
    $resultado_fallados = $conexion->query($sql_fallados);
    
    // Sumar un punto a cada jugador que ha acertado
    if ($resultado_acertantes->num_rows > 0) {
        // Primero, verificamos si ya se sumaron los puntos (para no sumarlos cada vez que se visita la página)
        $login_acertantes = [];
        
        // Guardar todos los logins de acertantes
        $resultado_acertantes_temp = clone $resultado_acertantes; // Clonar para no perder la posición original
        while ($fila = $resultado_acertantes_temp->fetch_assoc()) {
            $login_acertantes[] = $fila["login"];
        }
        
        if (!empty($login_acertantes)) {
            $logins = "'" . implode("','", $login_acertantes) . "'";
            $sql_actualizar = "UPDATE jugador SET puntos = puntos + 1 WHERE login IN ($logins)";
            $conexion->query($sql_actualizar);
        }
    }
}

// Contar acertantes y fallados
$num_acertantes = $resultado_acertantes ? $resultado_acertantes->num_rows : 0;
$num_fallados = $resultado_fallados ? $resultado_fallados->num_rows : 0;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Resultados del Día</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .contenedor {
            max-width: 800px;
            margin: 0 auto;
        }
        .usuario {
            text-align: right;
            margin-bottom: 20px;
        }
        .fecha {
            font-size: 18px;
            margin-bottom: 20px;
        }
        .resumen {
            margin-bottom: 30px;
            padding: 15px;
            background-color: #f0f0f0;
            border-radius: 5px;
        }
        .seccion {
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .navegacion {
            margin-top: 30px;
        }
        .navegacion a {
            display: inline-block;
            margin-right: 15px;
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="contenedor">
        <div class="usuario">
            Usuario: <?php echo $_SESSION["nombre"]; ?> 
            (<a href="index.php">Cerrar sesión</a>)
        </div>
        
        <h1>Resultados del Día</h1>
        
        <div class="fecha">
            Fecha: <?php echo date("d/m/Y", strtotime($fecha_actual)); ?>
        </div>
        
        <div class="resumen">
            <p><strong>Solución correcta:</strong> <?php echo $solucion_correcta; ?></p>
            <p><strong>Jugadores que han acertado:</strong> <?php echo $num_acertantes; ?></p>
            <p><strong>Jugadores que han fallado:</strong> <?php echo $num_fallados; ?></p>
        </div>
        
        <?php if ($resultado_acertantes && $num_acertantes > 0): ?>
        <div class="seccion">
            <h2>Jugadores que han acertado</h2>
            <table>
                <tr>
                    <th>Nombre</th>
                    <th>Hora</th>
                </tr>
                <?php
                $resultado_acertantes->data_seek(0); // Reiniciar el puntero del resultado
                while ($fila = $resultado_acertantes->fetch_assoc()):
                ?>
                <tr>
                    <td><?php echo $fila["nombre"]; ?></td>
                    <td><?php echo $fila["hora"]; ?></td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>
        <?php endif; ?>
        
        <?php if ($resultado_fallados && $num_fallados > 0): ?>
        <div class="seccion">
            <h2>Jugadores que han fallado</h2>
            <table>
                <tr>
                    <th>Nombre</th>
                    <th>Hora</th>
                    <th>Respuesta</th>
                </tr>
                <?php while ($fila = $resultado_fallados->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $fila["nombre"]; ?></td>
                    <td><?php echo $fila["hora"]; ?></td>
                    <td><?php echo $fila["respuesta"]; ?></td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>
        <?php endif; ?>
        
        <div class="navegacion">
            <a href="inicio.php">Volver al jeroglífico</a>
            <a href="puntos.php">Ver puntos por jugador</a>
        </div>
    </div>
</body>
</html>