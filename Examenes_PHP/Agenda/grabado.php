<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

// Verificar si se ha definido el número de contactos guardados
if (!isset($_SESSION['contactos_guardados'])) {
    header("Location: inicio.php");
    exit();
}

$contactos_guardados = $_SESSION['contactos_guardados'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda - Contactos Grabados</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        h2 {
            color: #333;
            margin-bottom: 20px;
        }
        .welcome {
            margin-bottom: 20px;
            font-size: 18px;
            color: #666;
        }
        .success-message {
            margin: 20px 0;
            font-size: 20px;
            color: #4CAF50;
        }
        .links {
            margin-top: 30px;
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
        
        <h2>Contactos Grabados</h2>
        
        <div class="success-message">
            Se han grabado <?php echo $contactos_guardados; ?> contactos en la agenda.
        </div>
        
        <div class="links">
            <a href="inicio.php" class="link">Grabar más contactos</a>
            <a href="totales.php" class="link">Ver totales</a>
            <a href="index.php" class="link">Pagina inicial</a>
        </div>
    </div>
</body>
</html>