<?php
session_start();
// Configuración de la conexión a la base de datos
$servidor = "localhost";
$usuario = "root";
$password = "";
$bd = "pictogramasphp";

// Crear conexión
$conexion = new mysqli($servidor, $usuario, $password, $bd);

// Verificar conexión
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ejercicio3</title>
</head>
<body>
    <h3>Ver agenda</h3>
    <div>
    <form method="POST" id="registroForm">
                    <div>
                        <label for="fecha" class="form-label">Fecha</label>
                        <input type="date" class="form-control form-control-sm" id="fecha" name="fecha" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    <label class="form-label">Personas</label>
                        <select class="form-select form-select-sm" name="personas" required>
                            <option value="Carllos">Carlos</option>
                            <option value="Juan">Juan</option>
                            <option value="Manuel">Manuel</option>
                        </select>
    </form>
    <br>
    <button type="radio">Mostrar agenda</button>
    <a href="Ejercicio1.php">< Volver al listado</a>

    </div>
</body>
</html>