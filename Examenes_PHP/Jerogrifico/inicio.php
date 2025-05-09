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

// Mensaje para mostrar después de enviar una respuesta
$mensaje = "";

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $respuesta = $_POST["respuesta"];
    $login = $_SESSION["login"];
    $hora_actual = date("H:i:s");
    
    // Insertar la respuesta en la base de datos
    $sql = "INSERT INTO respuestas (fecha, login, hora, respuesta) VALUES ('$fecha_actual', '$login', '$hora_actual', '$respuesta')
            ON DUPLICATE KEY UPDATE respuesta = '$respuesta', hora = '$hora_actual'";
    
    if ($conexion->query($sql) === TRUE) {
        $mensaje = "¡Respuesta enviada correctamente!";
    } else {
        $mensaje = "Error al enviar la respuesta: " . $conexion->error;
    }
}

// Obtener el nombre del archivo del jeroglífico (suponiendo formato AAAAMMDD.jpg)
$nombre_archivo = date("Ymd") . ".jpg";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Jeroglífico del Día</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            text-align: center;
        }
        .contenedor {
            max-width: 800px;
            margin: 0 auto;
        }
        .usuario {
            text-align: right;
            margin-bottom: 20px;
        }
        .jeroglifico {
            margin-bottom: 30px;
        }
        .jeroglifico img {
            max-width: 100%;
            height: auto;
            border: 1px solid #ddd;
        }
        .formulario {
            margin-bottom: 30px;
        }
        .campo {
            margin-bottom: 15px;
        }
        .campo input[type="text"] {
            width: 300px;
            padding: 8px;
        }
        .boton {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 10px;
        }
        .navegacion {
            margin-top: 20px;
        }
        .navegacion a {
            display: inline-block;
            margin: 0 10px;
            padding: 10px 15px;
            background-color: #f0f0f0;
            text-decoration: none;
            color: #333;
            border-radius: 4px;
        }
        .mensaje {
            color: green;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="contenedor">
        <div class="usuario">
            Bienvenido, <?php echo $_SESSION["nombre"]; ?> 
            (<a href="index.php">Cerrar sesión</a>)
        </div>
        
        <h1>Jeroglífico del Día</h1>
        
        <?php if ($mensaje != ""): ?>
            <div class="mensaje"><?php echo $mensaje; ?></div>
        <?php endif; ?>
        
        <div class="jeroglifico">
            <img src="20241212.jpg" alt="Jeroglífico del día">
        </div>
        
        <div class="formulario">
            <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                <div class="campo">
                    <label for="respuesta">Tu solución:</label>
                    <input type="text" id="respuesta" name="respuesta" required>
                </div>
                
                <div class="campo">
                    <input type="submit" class="boton" value="Enviar">
                </div>
            </form>
        </div>
        
        <div class="navegacion">
            <a href="puntos.php">Ver puntos por jugador</a>
            <a href="resultado.php">Resultados del día</a>
        </div>
    </div>
</body>
</html>