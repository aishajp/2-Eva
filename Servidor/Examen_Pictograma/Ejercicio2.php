<?php
session_start();

// Procesar el formulario cuando se envía
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conexión a la base de datos
    $conexion = new mysqli("localhost", "root", "", "pictogramasphp");
    
    // Verificar la conexión
    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }
    $todos_completos=true;
// Crear conexión
$conexion = new mysqli($servidor, $usuario, $password, $bd);

// Verificar conexión
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}
// Verificar que todos los campos estén rellenos
for ($i = 0; $i < 0; $i++) {
    if (empty($_POST["fecha$i"]) || empty($_POST["hora$i"]) || empty($_POST["personas$i"])) {
        $todos_completos = false;
        $error = "Todos los campos deben estar rellenos";
        break;
    }
}
// Si todos los campos están completos, proceder a guardar
if ($todos_completos) {
    $contactos_guardados = 0;
    
    for ($i = 0; $i < $num_contactos; $i++) {
        $fecha = $_POST["fecha$i"];
        $hora = $_POST["hora$i"];
        $personas = $_POST["personas$i"];
        $id = $_SESSION['id'];
        
        $sql = "INSERT INTO agenda (fecha, hora, personas, id) VALUES ('$fecha', '$hora', '$personas', $id)";
        
        if ($conexion->query($sql) === TRUE) {
            $contactos_guardados++;
        }
    }

$conexion->close();
}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ejercicio 2</title>
</head>
<body>
    <?php if (!empty($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <?php for ($i = 0; $i < $num_contactos; $i++): ?>
                <div class="contact-form">
                    <h3>Añadir datos agenda <?php echo $i + 1; ?></h3>
                    
                    <div class="form-group">
                        <label for="fecha<?php echo $i; ?>">Fecha:</label>
                        <input type="text" id="fecha<?php echo $i; ?>" name="fecha<?php echo $i; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="hora<?php echo $i; ?>">Hora:</label>
                        <input type="time" id="hora<?php echo $i; ?>" name="hora<?php echo $i; ?>" required>
                    </div>
                    
                    <div>
                        <label class="form-label">Personas</label>
                        <select class="form-select form-select-sm" name="personas" required>
                            <option value="Carllos">Carlos</option>
                            <option value="Juan">Juan</option>
                            <option value="Manuel">Manuel</option>
                        </select>
                    </div>
                </div>
            <?php endfor; ?>
            
    <div>
    <div>
                <form method="POST" id="registroForm">
                    <div>
                        <label for="fecha" class="form-label">Fecha</label>
                        <input type="date" class="form-control form-control-sm" id="fecha" name="fecha" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    <div>
                                    <div>
                                        <label class="form-label">Hora</label>
                                        <input type="time" class="form-control form-control-sm" name="hora">
                                    </div>
                                </div>
                                <div>
                        <label class="form-label">Personas</label>
                        <select class="form-select form-select-sm" name="personas" required>
                            <option value="Carllos">Carlos</option>
                            <option value="Juan">Juan</option>
                            <option value="Manuel">Manuel</option>
                        </select>
                    </div>
                </form>
</div>
<br>
<h3>Selecciona una imagen</h3>

<br>
<button type="radio-button">Añadir entrada en agenda</button>
<a href="Ejercicio1.php">< Volver al listado</a>



</body>
</html>