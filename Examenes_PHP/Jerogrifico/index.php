<?php
session_start();

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

$mensaje_error = "";

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = $_POST["login"];
    $clave = $_POST["clave"];
    
    // Consulta para verificar las credenciales
    $sql = "SELECT * FROM jugador WHERE login = '$login' AND clave = '$clave'";
    $resultado = $conexion->query($sql);
    
    if ($resultado->num_rows > 0) {
        // Credenciales correctas, almacenar en la sesión y redirigir
        $fila = $resultado->fetch_assoc();
        $_SESSION["login"] = $login;
        $_SESSION["nombre"] = $fila["nombre"];
        
        header("Location: inicio.php");
        exit();
    } else {
        // Credenciales incorrectas
        $mensaje_error = "Login o clave incorrectos. Por favor, inténtelo de nuevo.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Jeroglífico - Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .formulario {
            width: 300px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .campo {
            margin-bottom: 15px;
        }
        .campo label {
            display: block;
            margin-bottom: 5px;
        }
        .campo input {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }
        .boton {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .error {
            color: red;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="formulario">
        <h2>Acceso al Jeroglífico</h2>
        
        <?php if ($mensaje_error != ""): ?>
            <div class="error"><?php echo $mensaje_error; ?></div>
        <?php endif; ?>
        
        <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
            <div class="campo">
                <label for="login">Login:</label>
                <input type="text" id="login" name="login" required>
            </div>
            
            <div class="campo">
                <label for="clave">Clave:</label>
                <input type="password" id="clave" name="clave" required>
            </div>
            
            <div class="campo">
                <input type="submit" class="boton" value="Iniciar Sesión">
            </div>
        </form>
    </div>
</body>
</html>