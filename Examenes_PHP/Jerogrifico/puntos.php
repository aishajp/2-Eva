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

// Obtener todos los jugadores ordenados por puntos de mayor a menor
$sql = "SELECT nombre, puntos FROM jugador ORDER BY puntos DESC";
$resultado = $conexion->query($sql);

// Obtener el máximo de puntos para escalar la gráfica
$max_puntos = 0;
if ($resultado->num_rows > 0) {
    $datos = [];
    while ($fila = $resultado->fetch_assoc()) {
        $datos[] = $fila;
        if ($fila["puntos"] > $max_puntos) {
            $max_puntos = $fila["puntos"];
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Puntos por Jugador</title>
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
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
        .barra-contenedor {
            width: 300px;
        }
        .barra {
            height: 20px;
            background-color: #4CAF50;
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
        
        <h1>Puntos por Jugador</h1>
        
        <?php if (isset($datos) && count($datos) > 0): ?>
        <table>
            <tr>
                <th>Nombre</th>
                <th>Puntos</th>
                <th>Gráfica</th>
            </tr>
            <?php foreach ($datos as $jugador): ?>
            <tr>
                <td><?php echo $jugador["nombre"]; ?></td>
                <td><?php echo $jugador["puntos"]; ?></td>
                <td>
                    <div class="barra-contenedor">
                        <?php 
                        // Calcular el ancho de la barra proporcional a los puntos
                        $ancho = ($max_puntos > 0) ? ($jugador["puntos"] / $max_puntos) * 100 : 0;
                        ?>
                        <div class="barra" style="width: <?php echo $ancho; ?>%;"></div>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php else: ?>
        <p>No hay datos de jugadores disponibles.</p>
        <?php endif; ?>
        
        <div class="navegacion">
            <a href="inicio.php">Volver al jeroglífico</a>
            <a href="resultado.php">Ver resultados del día</a>
        </div>
    </div>
</body>
</html>