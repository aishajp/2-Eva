<?php
session_start();
require_once 'config/database.php';

// Verificar si el usuario está autenticado
$isLoggedIn = isset($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - Control Diabetes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">Control Diabetes</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <?php if ($isLoggedIn): ?>
                        <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link" href="logout.php">Cerrar sesión</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="login.php">Iniciar sesión</a></li>
                        <li class="nav-item"><a class="nav-link" href="register.php">Registrarse</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="text-center">
            <h1>Bienvenido a Control Diabetes</h1>
            <p class="lead">Lleva un control seguro y fácil de tu salud.</p>

            <?php if ($isLoggedIn): ?>
                <a href="dashboard.php" class="btn btn-primary">Ir al Dashboard</a>
            <?php else: ?>
                <a href="register.php" class="btn btn-success">Regístrate ahora</a>
                <a href="login.php" class="btn btn-outline-primary">Iniciar sesión</a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
