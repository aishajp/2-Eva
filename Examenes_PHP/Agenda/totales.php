<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

// Conexión a la base de datos
$conexion = new mysqli("localhost:3307", "root", "", "AGENDA");

// Verificar la conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Consulta para obtener los usuarios y el número de contactos
$sql = "SELECT u.Nombre, COUNT(c.codcontacto) as total_contactos 
        FROM usuarios u 
        LEFT JOIN contactos c ON u.Codigo = c.codusuario 
        GROUP BY u.Codigo";

$resultado = $conexion->query($sql);

// Almacenar los resultados en un array
$datos = [];
if ($resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $datos[] = $fila;
    }
}

$conexion->close();

// Encontrar el valor máximo para la escala de la gráfica
$max_contactos = 0;
foreach ($datos as $dato) {
    if ($dato['total_contactos'] > $max_contactos) {
        $max_contactos = $dato['total_contactos'];
    }
}

// Si no hay contactos, establecer un valor mínimo para evitar división por cero
if ($max_contactos == 0) {
    $max_contactos = 1;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda - Totales</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 {
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }
        .welcome {
            margin-bottom: 20px;
            font-size: 18px;
            color: #666;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f8f8f8;
        }
        .bar-container {
            width: 300px;
            background-color: #e0e0e0;
            margin: 5px 0;
            border-radius: 4px;
            overflow: hidden;
        }
        .bar {
            height: 20px;
            background-color: #4CAF50;
        }
        .links {
            margin-top: 30px;
            text-align: center;
        }
        .link {
            display: inline-block;
            margin: 0 10px;
            color: #2196F3;
            text-decoration: none;
        }
        .link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
    <div class="welcome">
    Bienvenido, <?php echo $_SESSION['usuario']; ?> 
    <a href="logout.php" style="margin-left: 10px; font-size: 14px; color: #666;">Cerrar sesión</a>
</div>
        
        <h2>Total de Contactos por Usuario</h2>
        
        <table>
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Total Contactos</th>
                    <th>Gráfica</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($datos as $dato): ?>
                <tr>
                    <td><?php echo $dato['Nombre']; ?></td>
                    <td><?php echo $dato['total_contactos']; ?></td>
                    <td>
                        <div class="bar-container">
                            <div class="bar" style="width: <?php echo ($dato['total_contactos'] / $max_contactos) * 100; ?>%"></div>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <div class="links">
            <a href="inicio.php" class="link">Grabar más contactos</a>
            <a href="index.php" class="link">Pagina principal</a>
        </div>
    </div>
</body>
</html>