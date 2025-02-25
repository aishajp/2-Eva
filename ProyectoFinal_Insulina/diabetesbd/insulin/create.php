<?php
require_once '../includes/functions.php';
require_once '../includes/insulin_functions.php';
require_once '../config/database.php';

// Verificar si el usuario está logueado
redirectIfNotLoggedIn();

$database = new Database();
$db = $database->getConnection();

$mensaje = '';
$tipo_mensaje = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fecha = $_POST['fecha'];
    
    // Procesar siempre como comida, pero incluyendo datos de hipo/hiper
    $datos = [
        'tipo_comida' => $_POST['tipo_comida'],
        'gl_1h' => $_POST['gl_1h'] ?? null,
        'gl_2h' => $_POST['gl_2h'] ?? null,
        'raciones' => $_POST['raciones'] ?? null,
        'insulina' => $_POST['insulina'] ?? null,
        'fecha' => $fecha
    ];
    
    // Guardar el registro de comida
    if (guardarRegistroComida($db, $datos)) {
        $mensaje = "Registro de comida guardado correctamente";
        $tipo_mensaje = "success";
        
        // Si hay hipoglucemia, guardar también el registro de hipoglucemia
        if (isset($_POST['es_hipo']) && $_POST['es_hipo'] == '1') {
            $datos_hipo = [
                'glucosa' => $_POST['glucosa_hipo'],
                'hora' => $_POST['hora_hipo'],
                'tipo_comida' => $_POST['tipo_comida'],
                'fecha' => $fecha
            ];
            if (guardarRegistroHipo($db, $datos_hipo)) {
                $mensaje .= " - Hipoglucemia registrada";
            }
        }
        
        // Si hay hiperglucemia, guardar también el registro de hiperglucemia
        if (isset($_POST['es_hiper']) && $_POST['es_hiper'] == '1') {
            $datos_hiper = [
                'glucosa' => $_POST['glucosa_hiper'],
                'hora' => $_POST['hora_hiper'],
                'correccion' => $_POST['correccion'],
                'tipo_comida' => $_POST['tipo_comida'],
                'fecha' => $fecha
            ];
            if (guardarRegistroHiper($db, $datos_hiper)) {
                $mensaje .= " - Hiperglucemia registrada";
            }
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <!-- Barra de navegación -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
        <div class="container">
            <a class="navbar-brand" href="../index.php">DiabetesControl</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="../index.php">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="read.php">Mis Registros</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="create.php">Nuevo Registro</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            Estadísticas
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="../stats/tendencias.php">Tendencias de Glucosa</a></li>
                            <li><a class="dropdown-item" href="../stats/eventos.php">Eventos Glucémicos</a></li>
                        </ul>
                    </li>
                </ul>
                <div class="d-flex">
                    <a href="../auth/logout.php" class="btn btn-outline-light">Cerrar Sesión</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <?php if ($mensaje): ?>
            <div class="alert alert-<?php echo $tipo_mensaje; ?>" role="alert">
                <?php echo $mensaje; ?>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header">
                <h5>Registro Diario</h5>
            </div>
            <div class="card-body">
                <form method="POST" id="registroForm">
                    <div class="mb-3">
                        <label for="fecha" class="form-label">Fecha</label>
                        <input type="date" class="form-control form-control-sm" id="fecha" name="fecha" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tipo de Comida</label>
                        <select class="form-select form-select-sm" name="tipo_comida" required>
                            <option value="desayuno">Desayuno</option>
                            <option value="comida">Comida</option>
                            <option value="merienda">Merienda</option>
                            <option value="cena">Cena</option>
                        </select>
                    </div>
                    
                    <!-- Datos comunes de comida -->
                    <div class="row g-2">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Glucosa 1h</label>
                                <input type="number" class="form-control form-control-sm" name="gl_1h">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Glucosa 2h</label>
                                <input type="number" class="form-control form-control-sm" name="gl_2h">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row g-2">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Raciones</label>
                                <input type="number" class="form-control form-control-sm" name="raciones" step="0.1">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Insulina</label>
                                <input type="number" class="form-control form-control-sm" name="insulina" step="0.1">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Sección de Hipoglucemia -->
                    <div class="card mt-3 mb-3">
                        <div class="card-header">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="check_hipo" name="es_hipo" value="1">
                                <label class="form-check-label fw-bold" for="check_hipo">
                                    Registrar Hipoglucemia
                                </label>
                            </div>
                        </div>
                        <div class="card-body" id="form_hipo" style="display:none;">
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Glucosa</label>
                                        <input type="number" class="form-control form-control-sm" name="glucosa_hipo">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Hora</label>
                                        <input type="time" class="form-control form-control-sm" name="hora_hipo">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Sección de Hiperglucemia -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="check_hiper" name="es_hiper" value="1">
                                <label class="form-check-label fw-bold" for="check_hiper">
                                    Registrar Hiperglucemia
                                </label>
                            </div>
                        </div>
                        <div class="card-body" id="form_hiper" style="display:none;">
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Glucosa</label>
                                        <input type="number" class="form-control form-control-sm" name="glucosa_hiper">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Hora</label>
                                        <input type="time" class="form-control form-control-sm" name="hora_hiper">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Corrección</label>
                                        <input type="number" class="form-control form-control-sm" name="correccion" step="0.1">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-sm">Guardar Registro</button>
                    <a href="../index.php" class="btn btn-secondary btn-sm">Cancelar</a>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Mostrar/ocultar secciones de hipo/hiperglucemia
        document.getElementById('check_hipo').addEventListener('change', function() {
            document.getElementById('form_hipo').style.display = this.checked ? 'block' : 'none';
        });
        
        document.getElementById('check_hiper').addEventListener('change', function() {
            document.getElementById('form_hiper').style.display = this.checked ? 'block' : 'none';
        });
    </script>
</body>
</html>
