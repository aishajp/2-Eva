<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

// Array de emojis disponibles
$emojis = [
    "OIP0.jfif",
    "OIP1.jfif",
    "OIP2.jfif", 
    "OIP3.jfif",
    "OIP4.jfif"
];

// Inicializar el contador de emojis si no existe
if (!isset($_SESSION['num_emojis'])) {
    $_SESSION['num_emojis'] = 1;
    // Seleccionar un emoji aleatorio inicial
    $_SESSION['emojis_seleccionados'] = [array_rand($emojis)];
}

// Petición de incrementar
if (isset($_POST['incrementar'])) {
    if ($_SESSION['num_emojis'] < 5) {
        $_SESSION['num_emojis']++;
        $nuevo_emoji = array_rand($emojis);
        // No se repita el último emoji
        while (in_array($nuevo_emoji, $_SESSION['emojis_seleccionados']) && count($emojis) > count($_SESSION['emojis_seleccionados'])) {
            $nuevo_emoji = array_rand($emojis);
        }
        $_SESSION['emojis_seleccionados'][] = $nuevo_emoji;
    }
    
    // Si llegamos a 5 emojis, redirigir automáticamente
    if ($_SESSION['num_emojis'] >= 5) {
        header("Location: agenda.php");
        exit();
    }
}

if (isset($_POST['grabar'])) {
    header("Location: agenda.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda - Seleccionar Cantidad</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
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
        .emoji-container {
            margin: 20px 0;
            display: flex;
            justify-content: center;
            gap: 10px;
        }
        .emoji {
            width: 50px;
            height: 50px;
        }
        .buttons {
            margin-top: 20px;
        }
        .btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin: 0 5px;
        }
        .btn:hover {
            background-color: #45a049;
        }
        .counter {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
    <div class="welcome">
    Bienvenido, <?php echo $_SESSION['usuario']; ?> 
    <a href="logout.php" style="margin-left: 10px; font-size: 14px; color: #666;">Cerrar sesión</a>
</div>
        
        <h2>Seleccione el número de contactos a registrar</h2>
        
        <div class="counter">
            Contactos seleccionados: <?php echo $_SESSION['num_emojis']; ?>/5
        </div>
        
        <div class="emoji-container">
            <?php for ($i = 0; $i < $_SESSION['num_emojis']; $i++): ?>
                <img src="<?php echo $emojis[$_SESSION['emojis_seleccionados'][$i]]; ?>" alt="Emoji" class="emoji">
            <?php endfor; ?>
        </div>
        
        <div class="buttons">
            <form method="post">
                <?php if ($_SESSION['num_emojis'] < 5): ?>
                    <button type="submit" name="incrementar" class="btn">INCREMENTAR</button>
                <?php endif; ?>
                <button type="submit" name="grabar" class="btn">GRABAR</button>
            </form>
        </div>
    </div>
</body>
</html>