<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';

// Redirigir al login si el usuario no ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Conectar a la base de datos
$database = new Database();
$db = $database->getConnection();

// Obtener datos del usuario
$stmt = $db->prepare("SELECT nombre FROM usuario WHERE id_usu = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Si no encuentra el usuario, forzar cierre de sesión
if (!$user) {
    session_destroy();
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Control Diabetes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">Control Diabetes</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link btn btn-danger text-white" href="logout.php">Cerrar sesión</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="card">
            <div class="card-body text-center">
                <h2>Bienvenido, <?php echo htmlspecialchars($user['nombre']); ?>!</h2>
                <p class="lead">Aquí podrás gestionar tu información y controlar tu salud.</p>
                <a href="logout.php" class="btn btn-danger">Cerrar sesión</a>
            </div>
        </div>
    </div>
</body>
</html>
