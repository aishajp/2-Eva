<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

// Verificar si se ha definido el número de contactos
if (!isset($_SESSION['num_emojis'])) {
    header("Location: inicio.php");
    exit();
}

$num_contactos = $_SESSION['num_emojis'];
$error = "";
$success = false;

// Procesar el formulario cuando se envía
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conexión a la base de datos
    $conexion = new mysqli("localhost:3307", "root", "", "AGENDA");
    
    // Verificar la conexión
    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }
    
    $todos_completos = true;
    
    // Verificar que todos los campos estén rellenos
    for ($i = 0; $i < $num_contactos; $i++) {
        if (empty($_POST["nombre$i"]) || empty($_POST["email$i"]) || empty($_POST["telefono$i"])) {
            $todos_completos = false;
            $error = "Todos los campos deben estar rellenos";
            break;
        }
    }
    
    if ($todos_completos) {
        $contactos_guardados = 0;
        
        for ($i = 0; $i < $num_contactos; $i++) {
            $nombre = $_POST["nombre$i"];
            $email = $_POST["email$i"];
            $telefono = $_POST["telefono$i"];
            $codusuario = $_SESSION['codusuario'];
            
            $sql = "INSERT IGNORE INTO contactos (nombre, email, telefono) VALUES ('$nombre', '$email', '$telefono')";
            
            if ($conexion->query($sql) === TRUE) {
                $contactos_guardados++;
            }
        }
        
        if ($contactos_guardados == $num_contactos) {
            $_SESSION['contactos_guardados'] = $contactos_guardados;
            header("Location: grabado.php");
            exit();
        } else {
            $error = "Hubo un problema al guardar algunos contactos";
        }
    }
    
    $conexion->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda - Introducir Contactos</title>
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
        .contact-form {
            margin-bottom: 30px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"], input[type="email"], input[type="tel"] {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            display: block;
            margin: 20px auto 0;
        }
        .btn:hover {
            background-color: #45a049;
        }
        .error {
            color: red;
            margin-bottom: 15px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
    <div class="welcome">
    Bienvenido, <?php echo $_SESSION['usuario']; ?> 
    <a href="logout.php" style="margin-left: 10px; font-size: 14px; color: #666;">Cerrar sesión</a>
</div>
        
        <h2>Introducir Contactos en la Agenda</h2>
        
        <?php if (!empty($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <?php for ($i = 0; $i < $num_contactos; $i++): ?>
                <div class="contact-form">
                    <h3>Contacto <?php echo $i + 1; ?></h3>
                    
                    <div class="form-group">
                        <label for="nombre<?php echo $i; ?>">Nombre:</label>
                        <input type="text" id="nombre<?php echo $i; ?>" name="nombre<?php echo $i; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email<?php echo $i; ?>">Email:</label>
                        <input type="email" id="email<?php echo $i; ?>" name="email<?php echo $i; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="telefono<?php echo $i; ?>">Teléfono:</label>
                        <input type="tel" id="telefono<?php echo $i; ?>" name="telefono<?php echo $i; ?>" required>
                    </div>
                </div>
            <?php endfor; ?>
            
            <button type="submit" class="btn">Guardar Contactos</button>
        </form>
    </div>
</body>
</html>