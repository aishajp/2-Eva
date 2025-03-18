<?php
require_once 'includes/functions.php';
require_once 'config/database.php';

// Verificar si hay sesión activa
$logged_in = isset($_SESSION['user_id']);

// Si el usuario está logueado, obtener información básica
$user_name = '';
$resumen_datos = null;

if ($logged_in) {
    $database = new Database();
    $db = $database->getConnection();
    
    // Obtener nombre del usuario
    $stmt = $db->prepare("SELECT nombre FROM usuario WHERE id_usu = :id_usu");
$stmt->execute([':id_usu' => $_SESSION['user_id']]);    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $user_name = $user['nombre'] ?? '';
    
    // Obtener resumen de datos recientes
    $fecha_inicio = date('Y-m-d', strtotime('-7 days'));
    $fecha_fin = date('Y-m-d');
    
    // Contar registros por tipo
    $sql_comidas = "SELECT COUNT(*) as total FROM comida WHERE id_usu = :id_usu AND fecha BETWEEN :fecha_inicio AND :fecha_fin";
    $sql_hipo = "SELECT COUNT(*) as total FROM hipoglucemia WHERE id_usu = :id_usu AND fecha BETWEEN :fecha_inicio AND :fecha_fin";
    $sql_hiper = "SELECT COUNT(*) as total FROM hiperglucemia WHERE id_usu = :id_usu AND fecha BETWEEN :fecha_inicio AND :fecha_fin";
    
    $stmt_comidas = $db->prepare($sql_comidas);
    $stmt_hipo = $db->prepare($sql_hipo);
    $stmt_hiper = $db->prepare($sql_hiper);
    
    $stmt_comidas->execute([':id_usu' => $_SESSION['user_id'], ':fecha_inicio' => $fecha_inicio, ':fecha_fin' => $fecha_fin]);
    $stmt_hipo->execute([':id_usu' => $_SESSION['user_id'], ':fecha_inicio' => $fecha_inicio, ':fecha_fin' => $fecha_fin]);
    $stmt_hiper->execute([':id_usu' => $_SESSION['user_id'], ':fecha_inicio' => $fecha_inicio, ':fecha_fin' => $fecha_fin]);
    
    $resumen_datos = [
        'comidas' => $stmt_comidas->fetch(PDO::FETCH_ASSOC)['total'],
        'hipoglucemias' => $stmt_hipo->fetch(PDO::FETCH_ASSOC)['total'],
        'hiperglucemias' => $stmt_hiper->fetch(PDO::FETCH_ASSOC)['total']
    ];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Control de Diabetes - Tu asistente diario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .feature-icon {
            font-size: 2.5rem;
            color: #0d6efd;
            margin-bottom: 1rem;
        }
        .hero-section {
            background-color: #f8f9fa;
            padding: 3rem 0;
            margin-bottom: 2rem;
        }
        .dashboard-card {
            transition: transform 0.3s;
        }
        .dashboard-card:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
        <div class="container">
            <a class="navbar-brand" href="index.php">DiabetesControl</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">Inicio</a>
                    </li>
                    <?php if ($logged_in): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="insulin/read.php">Mis Registros</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="insulin/create.php">Nuevo Registro</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            Estadísticas
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="stats/tendencias.php">Tendencias de Glucosa</a></li>
                            <li><a class="dropdown-item" href="stats/eventos.php">Eventos Glucémicos</a></li>
                        </ul>
                    </li>
                    <?php endif; ?>
                </ul>
                <div class="d-flex">
                    <?php if ($logged_in): ?>
                        <span class="navbar-text me-3">
                            <i class="bi bi-person-circle"></i> Hola, <?php echo htmlspecialchars($user_name); ?>
                        </span>
                        <a href="auth/logout.php" class="btn btn-outline-light">Cerrar Sesión</a>
                    <?php else: ?>
                        <a href="auth/login.php" class="btn btn-outline-light me-2">Iniciar Sesión</a>
                        <a href="auth/register.php" class="btn btn-light">Registrarse</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <main class="container">
        <?php if (!$logged_in): ?>
            <!-- Página para visitantes -->
            <section class="hero-section text-center rounded">
                <div class="py-5">
                    <h1 class="display-5 fw-bold">Control de Diabetes Simplificado</h1>
                    <div class="col-lg-6 mx-auto">
                        <p class="lead mb-4">Monitoriza tus niveles de glucosa, dosis de insulina y patrones alimenticios en un solo lugar. Tu salud, bajo control.</p>
                        <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
                            <a href="auth/register.php" class="btn btn-primary btn-lg px-4 gap-3">Comenzar Ahora</a>
                            <a href="#caracteristicas" class="btn btn-outline-secondary btn-lg px-4">Saber Más</a>
                        </div>
                    </div>
                </div>
            </section>

            <section id="caracteristicas" class="py-5">
                <h2 class="text-center mb-5">Características Principales</h2>
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body text-center">
                                <div class="feature-icon">
                                    <i class="bi bi-graph-up"></i>
                                </div>
                                <h4>Seguimiento de Tendencias</h4>
                                <p>Visualiza la evolución de tus niveles de glucosa a lo largo del tiempo con gráficos intuitivos.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body text-center">
                                <div class="feature-icon">
                                    <i class="bi bi-journal-plus"></i>
                                </div>
                                <h4>Registro Diario</h4>
                                <p>Registra tus comidas, dosis de insulina y eventos relacionados con tu diabetes de forma sencilla.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body text-center">
                                <div class="feature-icon">
                                    <i class="bi bi-clipboard-data"></i>
                                </div>
                                <h4>Estadísticas Detalladas</h4>
                                <p>Obtén informes detallados sobre hipoglucemias, hiperglucemias y patrones para mejorar tu gestión diaria.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        <?php else: ?>
            <!-- Dashboard para usuarios logueados -->
            <div class="row mb-4">
                <div class="col">
                    <h2>Panel de Control</h2>
                    <p class="text-muted">Bienvenido a tu asistente de control de diabetes. Aquí tienes un resumen de tus datos recientes.</p>
                </div>
            </div>
            
            <div class="row mb-4">
                <div class="col-md-4 mb-3">
                    <div class="card dashboard-card bg-primary text-white h-100">
                        <div class="card-body">
                            <h5 class="card-title">Comidas Registradas</h5>
                            <p class="display-4"><?php echo $resumen_datos['comidas']; ?></p>
                            <p class="card-text">Últimos 7 días</p>
                        </div>
                        <div class="card-footer bg-transparent border-0">
                            <a href="insulin/read.php" class="btn btn-outline-light btn-sm">Ver Detalles</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card dashboard-card bg-warning text-dark h-100">
                        <div class="card-body">
                            <h5 class="card-title">Hipoglucemias</h5>
                            <p class="display-4"><?php echo $resumen_datos['hipoglucemias']; ?></p>
                            <p class="card-text">Últimos 7 días</p>
                        </div>
                        <div class="card-footer bg-transparent border-0">
                            <a href="stats/eventos.php" class="btn btn-outline-dark btn-sm">Ver Detalles</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card dashboard-card bg-danger text-white h-100">
                        <div class="card-body">
                            <h5 class="card-title">Hiperglucemias</h5>
                            <p class="display-4"><?php echo $resumen_datos['hiperglucemias']; ?></p>
                            <p class="card-text">Últimos 7 días</p>
                        </div>
                        <div class="card-footer bg-transparent border-0">
                            <a href="stats/eventos.php" class="btn btn-outline-light btn-sm">Ver Detalles</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row mb-4">
                <div class="col-md-6 mb-3">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="mb-0">Acciones Rápidas</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="insulin/create.php" class="btn btn-primary">
                                    <i class="bi bi-plus-circle"></i> Nuevo Registro
                                </a>
                                <a href="insulin/read.php" class="btn btn-outline-secondary">
                                    <i class="bi bi-list"></i> Ver Mis Registros
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="mb-0">Recordatorios</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Medir glucosa después de las comidas
                                    <span class="badge bg-primary rounded-pill">1-2h</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Registrar eventos de hipoglucemia
                                    <span class="badge bg-warning text-dark rounded-pill">Importante</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Revisar tendencias semanalmente
                                    <span class="badge bg-info text-dark rounded-pill">Consejo</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </main>

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
</body>
</html>