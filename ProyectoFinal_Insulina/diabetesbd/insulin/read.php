<?php

// Iniciar sesión solo si no hay una activa
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../includes/functions.php';
require_once '../includes/insulin_functions.php';
require_once '../config/database.php';

redirectIfNotLoggedIn();

// Verificar que la sesión contiene 'id_usu'
if (!isset($_SESSION['user_id'])) {
    die("Error: El usuario no ha iniciado sesión correctamente.");
}

$database = new Database();
$db = $database->getConnection();

$fecha = isset($_GET['fecha']) ? $_GET['fecha'] : date('Y-m-d');
$registros = obtenerRegistrosDia($db, $fecha, $_SESSION['user_id']);

// Obtener el mes y año de la fecha seleccionada
$mes = date('m', strtotime($fecha));
$anio = date('Y', strtotime($fecha));

// Función para obtener resumen mensual adaptada a la estructura de la BD
function obtenerResumenMensual($db, $mes, $anio, $user_id) {
    // Primer día del mes
    $primer_dia = date('Y-m-d', strtotime($anio . '-' . $mes . '-01'));
    // Último día del mes
    $ultimo_dia = date('Y-m-t', strtotime($primer_dia));
    
    // Inicializar arreglo de resumen
    $resumen = [
        'comidas' => [
            'total_registros' => 0,
            'promedio_gl_1h' => 0,
            'promedio_gl_2h' => 0,
            'promedio_raciones' => 0,
            'promedio_insulina' => 0,
            'tipos_comida' => [
                'desayuno' => ['count' => 0, 'gl_1h' => 0, 'gl_2h' => 0, 'raciones' => 0, 'insulina' => 0],
                'almuerzo' => ['count' => 0, 'gl_1h' => 0, 'gl_2h' => 0, 'raciones' => 0, 'insulina' => 0],
                'merienda' => ['count' => 0, 'gl_1h' => 0, 'gl_2h' => 0, 'raciones' => 0, 'insulina' => 0],
                'cena' => ['count' => 0, 'gl_1h' => 0, 'gl_2h' => 0, 'raciones' => 0, 'insulina' => 0]
            ]
        ],
        'control' => [
            'total_registros' => 0,
            'promedio_deporte' => 0,
            'promedio_lenta' => 0
        ],
        'hiper' => [
            'total_registros' => 0,
            'promedio_glucosa' => 0,
            'promedio_correccion' => 0
        ],
        'hipo' => [
            'total_registros' => 0,
            'promedio_glucosa' => 0
        ]
    ];
    
    // Consulta SQL para obtener todas las comidas del mes
    $query = "SELECT tipo_comida, gl_1h, gl_2h, raciones, insulina 
              FROM COMIDA 
              WHERE id_usu = ? AND fecha BETWEEN ? AND ?";
    
    $stmt = $db->prepare($query);
    $stmt->bindParam(1, $user_id);
    $stmt->bindParam(2, $primer_dia);
    $stmt->bindParam(3, $ultimo_dia);
    $stmt->execute();
    
    $total_gl_1h = 0;
    $total_gl_2h = 0;
    $total_raciones = 0;
    $total_insulina = 0;
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $resumen['comidas']['total_registros']++;
        
        $tipo = strtolower($row['tipo_comida']);
        
        // Si el tipo no está en nuestro arreglo predefinido, saltar
        if (!isset($resumen['comidas']['tipos_comida'][$tipo])) {
            continue;
        }
        
        // Actualizar contadores por tipo de comida
        $resumen['comidas']['tipos_comida'][$tipo]['count']++;
        $resumen['comidas']['tipos_comida'][$tipo]['gl_1h'] += $row['gl_1h'];
        $resumen['comidas']['tipos_comida'][$tipo]['gl_2h'] += $row['gl_2h'];
        $resumen['comidas']['tipos_comida'][$tipo]['raciones'] += $row['raciones'];
        $resumen['comidas']['tipos_comida'][$tipo]['insulina'] += $row['insulina'];
        
        // Actualizar totales generales
        $total_gl_1h += $row['gl_1h'];
        $total_gl_2h += $row['gl_2h'];
        $total_raciones += $row['raciones'];
        $total_insulina += $row['insulina'];
    }
    
    // Calcular promedios de comidas si hay registros
    if ($resumen['comidas']['total_registros'] > 0) {
        $resumen['comidas']['promedio_gl_1h'] = round($total_gl_1h / $resumen['comidas']['total_registros'], 1);
        $resumen['comidas']['promedio_gl_2h'] = round($total_gl_2h / $resumen['comidas']['total_registros'], 1);
        $resumen['comidas']['promedio_raciones'] = round($total_raciones / $resumen['comidas']['total_registros'], 1);
        $resumen['comidas']['promedio_insulina'] = round($total_insulina / $resumen['comidas']['total_registros'], 1);
        
        // Calcular promedios por tipo de comida
        foreach ($resumen['comidas']['tipos_comida'] as $tipo => &$datos) {
            if ($datos['count'] > 0) {
                $datos['gl_1h'] = round($datos['gl_1h'] / $datos['count'], 1);
                $datos['gl_2h'] = round($datos['gl_2h'] / $datos['count'], 1);
                $datos['raciones'] = round($datos['raciones'] / $datos['count'], 1);
                $datos['insulina'] = round($datos['insulina'] / $datos['count'], 1);
            }
        }
    }
    
    // Consulta para CONTROL_GLUCOSA
    $query = "SELECT deporte, lenta 
              FROM CONTROL_GLUCOSA 
              WHERE id_usu = ? AND fecha BETWEEN ? AND ?";
    
    $stmt = $db->prepare($query);
    $stmt->bindParam(1, $user_id);
    $stmt->bindParam(2, $primer_dia);
    $stmt->bindParam(3, $ultimo_dia);
    $stmt->execute();
    
    $total_deporte = 0;
    $total_lenta = 0;
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $resumen['control']['total_registros']++;
        $total_deporte += $row['deporte'];
        $total_lenta += $row['lenta'];
    }
    
    // Calcular promedios de control si hay registros
    if ($resumen['control']['total_registros'] > 0) {
        $resumen['control']['promedio_deporte'] = round($total_deporte / $resumen['control']['total_registros'], 1);
        $resumen['control']['promedio_lenta'] = round($total_lenta / $resumen['control']['total_registros'], 1);
    }
    
    // Consulta para HIPERGLUCEMIA
    $query = "SELECT glucosa, correccion 
              FROM HIPERGLUCEMIA 
              WHERE id_usu = ? AND fecha BETWEEN ? AND ?";
    
    $stmt = $db->prepare($query);
    $stmt->bindParam(1, $user_id);
    $stmt->bindParam(2, $primer_dia);
    $stmt->bindParam(3, $ultimo_dia);
    $stmt->execute();
    
    $total_glucosa_hiper = 0;
    $total_correccion = 0;
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $resumen['hiper']['total_registros']++;
        $total_glucosa_hiper += $row['glucosa'];
        $total_correccion += $row['correccion'];
    }
    
    // Calcular promedios de hiperglucemia si hay registros
    if ($resumen['hiper']['total_registros'] > 0) {
        $resumen['hiper']['promedio_glucosa'] = round($total_glucosa_hiper / $resumen['hiper']['total_registros'], 1);
        $resumen['hiper']['promedio_correccion'] = round($total_correccion / $resumen['hiper']['total_registros'], 1);
    }
    
    // Consulta para HIPOGLUCEMIA
    $query = "SELECT glucosa 
              FROM HIPOGLUCEMIA 
              WHERE id_usu = ? AND fecha BETWEEN ? AND ?";
    
    $stmt = $db->prepare($query);
    $stmt->bindParam(1, $user_id);
    $stmt->bindParam(2, $primer_dia);
    $stmt->bindParam(3, $ultimo_dia);
    $stmt->execute();
    
    $total_glucosa_hipo = 0;
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $resumen['hipo']['total_registros']++;
        $total_glucosa_hipo += $row['glucosa'];
    }
    
    // Calcular promedios de hipoglucemia si hay registros
    if ($resumen['hipo']['total_registros'] > 0) {
        $resumen['hipo']['promedio_glucosa'] = round($total_glucosa_hipo / $resumen['hipo']['total_registros'], 1);
    }
    
    return $resumen;
}

// Obtener el resumen mensual
$resumen_mensual = obtenerResumenMensual($db, $mes, $anio, $_SESSION['user_id']);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Registros - Control Diabetes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <style>
        html, body {
            height: 100%;
        }
    </style>
</head>
<body class="bg-light d-flex flex-column min-vh-100">
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
                        <a class="nav-link active" href="read.php">Mis Registros</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="create.php">Nuevo Registro</a>
                    </li>
                </ul>
                <div class="d-flex">
                    <a href="../auth/logout.php" class="btn btn-outline-light">Cerrar Sesión</a>
                </div>
            </div>
        </div>
    </nav>

    <main class="flex-grow-1">
    <div class="container mt-4">
    <?php if (isset($_SESSION['mensaje'])): ?>
        <div class="alert alert-<?php echo $_SESSION['tipo_mensaje']; ?> alert-dismissible fade show" role="alert">
            <?php 
            echo $_SESSION['mensaje']; 
            // Clear the message after displaying it
            unset($_SESSION['mensaje']);
            unset($_SESSION['tipo_mensaje']);
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <!-- Rest of your code -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>Registros del Día</h3>
                    <a href="create.php" class="btn btn-primary">Nuevo Registro</a>
                </div>
                <div class="card-body">
                    <form class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="fecha" class="form-label">Seleccionar Fecha</label>
                                <input type="date" class="form-control" id="fecha" name="fecha" 
                                       value="<?php echo $fecha; ?>" onchange="this.form.submit()">
                            </div>
                        </div>
                    </form>

                    <!-- Tabla de Comidas -->
                    <h4 class="mb-3">Comidas</h4>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Tipo</th>
                                    <th>GL 1h</th>
                                    <th>GL 2h</th>
                                    <th>Raciones</th>
                                    <th>Insulina</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($registros['comidas'] as $comida): ?>
                                <tr>
                                    <td><?php echo ucfirst($comida['tipo_comida']); ?></td>
                                    <td><?php echo $comida['gl_1h']; ?></td>
                                    <td><?php echo $comida['gl_2h']; ?></td>
                                    <td><?php echo $comida['raciones']; ?></td>
                                    <td><?php echo $comida['insulina']; ?></td>
                                    <td>
                                        <a href="update.php?tipo=comida&tipo_comida=<?php echo urlencode($comida['tipo_comida']); ?>&fecha=<?php echo $fecha; ?>" 
                                        class="btn btn-sm btn-warning">Editar</a>
                                        <a href="#" class="btn btn-sm btn-danger" 
                                        onclick="eliminarRegistro('comida', '<?php echo urlencode($comida['tipo_comida']); ?>')">Eliminar</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Otras tablas (Hipoglucemias, Hiperglucemias) seguirían la misma lógica -->
                </div>
            </div>

            <!-- Sección de Resumen Mensual -->
            <div class="card mb-4">
                <div class="card-header text-black">
                    <h3>Resumen Mensual: <?php echo date('F Y', strtotime($fecha)); ?></h3>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs mb-3" id="resumenTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="comidas-tab" data-bs-toggle="tab" data-bs-target="#comidas" type="button" role="tab" aria-controls="comidas" aria-selected="true">Comidas</button>
                        </li>
                        <li class="nav-item" role="presentation">                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="hiper-tab" data-bs-toggle="tab" data-bs-target="#hiper" type="button" role="tab" aria-controls="hiper" aria-selected="false">Hiperglucemias</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="hipo-tab" data-bs-toggle="tab" data-bs-target="#hipo" type="button" role="tab" aria-controls="hipo" aria-selected="false">Hipoglucemias</button>
                        </li>
                    </ul>
                    
                    <div class="tab-content" id="resumenTabContent">
                        <!-- Comidas Tab -->
                        <div class="tab-pane fade show active" id="comidas" role="tabpanel" aria-labelledby="comidas-tab">
                            <?php if ($resumen_mensual['comidas']['total_registros'] > 0): ?>
                                <!-- Resumen general de comidas -->
                                <div class="mb-4">
                                    <h4 class="mb-3">Resumen General de Comidas</h4>
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Total Registros</th>
                                                    <th>Promedio GL 1h</th>
                                                    <th>Promedio GL 2h</th>
                                                    <th>Promedio Raciones</th>
                                                    <th>Promedio Insulina</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><?php echo $resumen_mensual['comidas']['total_registros']; ?></td>
                                                    <td><?php echo $resumen_mensual['comidas']['promedio_gl_1h']; ?></td>
                                                    <td><?php echo $resumen_mensual['comidas']['promedio_gl_2h']; ?></td>
                                                    <td><?php echo $resumen_mensual['comidas']['promedio_raciones']; ?></td>
                                                    <td><?php echo $resumen_mensual['comidas']['promedio_insulina']; ?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Resumen por tipo de comida -->
                                <div>
                                    <h4 class="mb-3">Promedios por Tipo de Comida</h4>
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Tipo de Comida</th>
                                                    <th>Cantidad</th>
                                                    <th>Promedio GL 1h</th>
                                                    <th>Promedio GL 2h</th>
                                                    <th>Promedio Raciones</th>
                                                    <th>Promedio Insulina</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($resumen_mensual['comidas']['tipos_comida'] as $tipo => $datos): ?>
                                                    <?php if ($datos['count'] > 0): ?>
                                                        <tr>
                                                            <td><?php echo ucfirst($tipo); ?></td>
                                                            <td><?php echo $datos['count']; ?></td>
                                                            <td><?php echo $datos['gl_1h']; ?></td>
                                                            <td><?php echo $datos['gl_2h']; ?></td>
                                                            <td><?php echo $datos['raciones']; ?></td>
                                                            <td><?php echo $datos['insulina']; ?></td>
                                                        </tr>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-info">
                                    No hay registros de comidas para el mes de <?php echo date('F Y', strtotime($fecha)); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Hiperglucemias Tab -->
                        <div class="tab-pane fade" id="hiper" role="tabpanel" aria-labelledby="hiper-tab">
                            <?php if ($resumen_mensual['hiper']['total_registros'] > 0): ?>
                                <div class="mb-4">
                                    <h4 class="mb-3">Resumen de Hiperglucemias</h4>
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Total Registros</th>
                                                    <th>Promedio Glucosa</th>
                                                    <th>Promedio Corrección</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><?php echo $resumen_mensual['hiper']['total_registros']; ?></td>
                                                    <td><?php echo $resumen_mensual['hiper']['promedio_glucosa']; ?></td>
                                                    <td><?php echo $resumen_mensual['hiper']['promedio_correccion']; ?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-info">
                                    No hay registros de hiperglucemias para el mes de <?php echo date('F Y', strtotime($fecha)); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Hipoglucemias Tab -->
                        <div class="tab-pane fade" id="hipo" role="tabpanel" aria-labelledby="hipo-tab">
                            <?php if ($resumen_mensual['hipo']['total_registros'] > 0): ?>
                                <div class="mb-4">
                                    <h4 class="mb-3">Resumen de Hipoglucemias</h4>
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Total Registros</th>
                                                    <th>Promedio Glucosa</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><?php echo $resumen_mensual['hipo']['total_registros']; ?></td>
                                                    <td><?php echo $resumen_mensual['hipo']['promedio_glucosa']; ?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-info">
                                    No hay registros de hipoglucemias para el mes de <?php echo date('F Y', strtotime($fecha)); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-dark text-white py-4">
        <div class="container">
            <div class="text-center">
                <p class="mb-0">&copy; <?php echo date('Y'); ?> DiabetesControl. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function eliminarRegistro(tipo, tipo_comida) {
        if (confirm('¿Estás seguro de que deseas eliminar este registro?')) {
            window.location.href = `delete.php?tipo=${tipo}&tipo_comida=${tipo_comida}&fecha=<?php echo $fecha; ?>`;
        }
    }
    function editarComida(id_usu) {
        window.location.href = `update.php?tipo_comida=tipo_comida&id_usu=${id_usu}&fecha=<?php echo $fecha; ?>`;
    }
    </script>
</body>
</html>