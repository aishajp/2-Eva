<?php
require_once '../includes/functions.php';
require_once '../includes/insulin_functions.php';
require_once '../config/database.php';

redirectIfNotLoggedIn();

$database = new Database();
$db = $database->getConnection();

// Obtener datos del último mes
$fecha_inicio = date('Y-m-d', strtotime('-30 days'));
$fecha_fin = date('Y-m-d');

try {
    // Obtener promedios por día
    $sql = "SELECT 
            fecha,
            AVG(gl_1h) as promedio_1h,
            AVG(gl_2h) as promedio_2h
            FROM comida 
            WHERE id_usu = :id_usu 
            AND fecha BETWEEN :fecha_inicio AND :fecha_fin
            GROUP BY fecha 
            ORDER BY fecha";
    
    $stmt = $db->prepare($sql);
    $stmt->execute([
        ':id_usu' => $_SESSION['user_id'],
        ':fecha_inicio' => $fecha_inicio,
        ':fecha_fin' => $fecha_fin
    ]);
    
    $datos_glucosa = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Preparar datos para el gráfico
    $fechas = [];
    $promedios_1h = [];
    $promedios_2h = [];
    
    foreach ($datos_glucosa as $dato) {
        $fechas[] = $dato['fecha'];
        $promedios_1h[] = round($dato['promedio_1h'], 1);
        $promedios_2h[] = round($dato['promedio_2h'], 1);
    }
    
} catch(PDOException $e) {
    $error = "Error al obtener los datos: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tendencias de Glucosa - Control Diabetes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                        <a class="nav-link " href="../insulin/read.php">Mis Registros</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="../insulin/create.php">Nuevo Registro</a>
                    </li>
                    <li class="nav-item active dropdown ">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            Estadísticas
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="tendencias.php">Tendencias de Glucosa</a></li>
                            <li><a class="dropdown-item" href="eventos.php">Eventos Glucémicos</a></li>
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
        <div class="card">
            <div class="card-header">
                <h3>Tendencias de Glucosa</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <canvas id="glucosaChart"></canvas>
                    </div>
                    <div class="col-md-4">
                        <div class="card mt-3">
                            <div class="card-body">
                                <h5>Estadísticas del Período</h5>
                                <?php if (!empty($promedios_1h)): ?>
                                    <p>Promedio 1h: <?php echo round(array_sum($promedios_1h)/count($promedios_1h), 1); ?> mg/dL</p>
                                    <p>Promedio 2h: <?php echo round(array_sum($promedios_2h)/count($promedios_2h), 1); ?> mg/dL</p>
                                    <p>Días registrados: <?php echo count($fechas); ?></p>
                                <?php else: ?>
                                    <p>No hay datos suficientes para mostrar estadísticas.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>DiabetesControl</h5>
                    <p>Una aplicación para ayudarte a gestionar tu diabetes de manera efectiva.</p>
                </div>
                <div class="col-md-3">
                    <h5>Enlaces</h5>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-white">Inicio</a></li>
                        <li><a href="#" class="text-white">Sobre Nosotros</a></li>
                        <li><a href="#" class="text-white">Contacto</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5>Síguenos</h5>
                    <div class="d-flex">
                        <a href="#" class="text-white me-3"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="text-white me-3"><i class="bi bi-twitter-x"></i></a>
                        <a href="#" class="text-white"><i class="bi bi-instagram"></i></a>
                    </div>
                </div>
            </div>
            <hr>
            <div class="text-center">
                <p class="mb-0">&copy; <?php echo date('Y'); ?> DiabetesControl. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const ctx = document.getElementById('glucosaChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($fechas); ?>,
                datasets: [{
                    label: 'Glucosa 1h',
                    data: <?php echo json_encode($promedios_1h); ?>,
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                },
                {
                    label: 'Glucosa 2h',
                    data: <?php echo json_encode($promedios_2h); ?>,
                    borderColor: 'rgb(255, 99, 132)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: false,
                        title: {
                            display: true,
                            text: 'mg/dL'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Fecha'
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>