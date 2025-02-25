<?php
session_start(); // Asegurarse de que la sesión está iniciada primero

// Rutas correctas a los archivos de inclusión
require_once '../includes/functions.php';
require_once '../config/database.php';
require_once '../includes/insulin_functions.php';

// Verificar si el usuario está logueado
redirectIfNotLoggedIn();

// Crear conexión a la base de datos
$database = new Database();
$db = $database->getConnection();

// Verificar la conexión
if ($db === null) {
    die("Error: La conexión a la base de datos falló. Revisa la configuración de la base de datos.");
}

$mensaje = '';
$tipo_mensaje = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fecha = $_POST['fecha'];
    
    // Procesar datos según el tipo de registro
    if (isset($_POST['tipo_registro'])) {
        switch ($_POST['tipo_registro']) {
            case 'comida':
                $datos = [
                    'tipo_comida' => $_POST['tipo_comida'],
                    'gl_1h' => $_POST['gl_1h'],
                    'gl_2h' => $_POST['gl_2h'],
                    'raciones' => $_POST['raciones'],
                    'insulina' => $_POST['insulina'],
                    'fecha' => $fecha
                ];

// Justo antes de llamar a guardarRegistroComida()
echo "<pre>";
echo "Session: "; print_r($_SESSION);
echo "Datos a insertar: "; print_r($datos);
echo "</pre>";

// Intenta una consulta simple para verificar el usuario
try {
    $check_user = $db->prepare("SELECT * FROM usuario WHERE id_usu = ?");
    $check_user->execute([$_SESSION['user_id']]);
    $user = $check_user->fetch();
    
    if (!$user) {
        echo "Error: El usuario con ID " . $_SESSION['user_id'] . " no existe en la base de datos.";
    }
} catch (PDOException $e) {
    echo "Error al verificar usuario: " . $e->getMessage();
}
                if (guardarRegistroComida($db, $datos)) {
                    $mensaje = "Registro de comida guardado correctamente";
                    $tipo_mensaje = "success";
                } else {
                    $mensaje = "Error al guardar el registro de comida";
                    $tipo_mensaje = "danger";
                }
                break;
                
            case 'hipo':
                $datos = [
                    'glucosa' => $_POST['glucosa'],
                    'hora' => $_POST['hora'],
                    'tipo_comida' => $_POST['tipo_comida'],
                    'fecha' => $fecha
                ];
                if (guardarRegistroHipo($db, $datos)) {
                    $mensaje = "Registro de hipoglucemia guardado correctamente";
                    $tipo_mensaje = "success";
                } else {
                    $mensaje = "Error al guardar el registro de hipoglucemia";
                    $tipo_mensaje = "danger";
                }
                break;
                
            case 'hiper':
                $datos = [
                    'glucosa' => $_POST['glucosa'],
                    'hora' => $_POST['hora'],
                    'correccion' => $_POST['correccion'],
                    'tipo_comida' => $_POST['tipo_comida'],
                    'fecha' => $fecha
                ];
                if (guardarRegistroHiper($db, $datos)) {
                    $mensaje = "Registro de hiperglucemia guardado correctamente";
                    $tipo_mensaje = "success";
                } else {
                    $mensaje = "Error al guardar el registro de hiperglucemia";
                    $tipo_mensaje = "danger";
                }
                break;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Insulina - Control Diabetes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-4">
        <?php if ($mensaje): ?>
            <div class="alert alert-<?php echo $tipo_mensaje; ?>" role="alert">
                <?php echo $mensaje; ?>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header">
                <h3>Registro Diario de Insulina</h3>
            </div>
            <div class="card-body">
                <form method="POST" id="registroForm">
                    <div class="mb-3">
                        <label for="fecha" class="form-label">Fecha</label>
                        <input type="date" class="form-control" id="fecha" name="fecha" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tipo de Registro</label>
                        <select class="form-select" id="tipo_registro" name="tipo_registro" required>
                            <option value="">Seleccione tipo de registro</option>
                            <option value="comida">Comida</option>
                            <option value="hipo">Hipoglucemia</option>
                            <option value="hiper">Hiperglucemia</option>
                        </select>
                    </div>

                    <!-- Formulario para Comida -->
                    <div id="form_comida" style="display:none;">
                        <div class="mb-3">
                            <label class="form-label">Tipo de Comida</label>
                            <select class="form-select" name="tipo_comida">
                                <option value="desayuno">Desayuno</option>
                                <option value="comida">Comida</option>
                                <option value="cena">Cena</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Glucosa 1h</label>
                            <input type="number" class="form-control" name="gl_1h">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Glucosa 2h</label>
                            <input type="number" class="form-control" name="gl_2h">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Raciones</label>
                            <input type="number" class="form-control" name="raciones" step="0.1">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Insulina</label>
                            <input type="number" class="form-control" name="insulina" step="0.1">
                        </div>
                    </div>

                    <!-- Formulario para Hipoglucemia -->
                    <div id="form_hipo" style="display:none;">
                        <div class="mb-3">
                            <label class="form-label">Tipo de Comida</label>
                            <select class="form-select" name="tipo_comida">
                                <option value="desayuno">Desayuno</option>
                                <option value="comida">Comida</option>
                                <option value="cena">Cena</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Glucosa</label>
                            <input type="number" class="form-control" name="glucosa">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Hora</label>
                            <input type="time" class="form-control" name="hora">
                        </div>
                    </div>

                    <!-- Formulario para Hiperglucemia -->
                    <div id="form_hiper" style="display:none;">
                        <div class="mb-3">
                            <label class="form-label">Tipo de Comida</label>
                            <select class="form-select" name="tipo_comida">
                                <option value="desayuno">Desayuno</option>
                                <option value="comida">Comida</option>
                                <option value="cena">Cena</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Glucosa</label>
                            <input type="number" class="form-control" name="glucosa">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Hora</label>
                            <input type="time" class="form-control" name="hora">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Corrección</label>
                            <input type="number" class="form-control" name="correccion" step="0.1">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Guardar Registro</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('tipo_registro').addEventListener('change', function() {
            // Ocultar todos los formularios
            document.getElementById('form_comida').style.display = 'none';
            document.getElementById('form_hipo').style.display = 'none';
            document.getElementById('form_hiper').style.display = 'none';
            
            // Mostrar el formulario seleccionado
            if (this.value) {
                document.getElementById('form_' + this.value).style.display = 'block';
            }
        });
    </script>
</body>
</html>