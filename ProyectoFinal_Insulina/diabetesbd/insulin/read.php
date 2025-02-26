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
            <div class="card">
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
