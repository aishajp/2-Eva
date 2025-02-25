<?php
require_once '../includes/functions.php';
require_once '../includes/insulin_functions.php';
require_once '../config/database.php';

redirectIfNotLoggedIn();

$database = new Database();
$db = $database->getConnection();

$fecha = isset($_GET['fecha']) ? $_GET['fecha'] : date('Y-m-d');
$registros = obtenerRegistrosDia($db, $fecha, $_SESSION['id_usu']);

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
                        <a class="nav-link active" href="read.php">Mis Registros</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="create.php">Nuevo Registro</a>
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

    <!-- Contenido principal -->
    <main class="flex-grow-1">
        <div class="container mt-4">
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
                                        <?php if (isset($comida['id'])): ?>
                                            <button class="btn btn-sm btn-warning" 
                                                    onclick="editarComida(<?php echo $comida['id']; ?>)">Editar</button>
                                            <button class="btn btn-sm btn-danger" 
                                                    onclick="eliminarRegistro('comida', <?php echo $comida['id']; ?>)">Eliminar</button>
                                        <?php else: ?>
                                            <span class="text-danger">Error: ID no encontrado</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Tabla de Hipoglucemias -->
                    <h4 class="mb-3 mt-4">Hipoglucemias</h4>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Tipo Comida</th>
                                    <th>Glucosa</th>
                                    <th>Hora</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($registros['hipos'] as $hipo): ?>
                                <tr>
                                    <td><?php echo ucfirst($hipo['tipo_comida']); ?></td>
                                    <td><?php echo $hipo['glucosa']; ?></td>
                                    <td><?php echo $hipo['hora']; ?></td>
                                    <td>
                                        <?php if (isset($hipo['id'])): ?>
                                            <button class="btn btn-sm btn-warning" 
                                                    onclick="editarHipo(<?php echo $hipo['id']; ?>)">Editar</button>
                                            <button class="btn btn-sm btn-danger" 
                                                    onclick="eliminarRegistro('hipoglucemia', <?php echo $hipo['id']; ?>)">Eliminar</button>
                                        <?php else: ?>
                                            <span class="text-danger">Error: ID no encontrado</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Tabla de Hiperglucemias -->
                    <h4 class="mb-3 mt-4">Hiperglucemias</h4>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Tipo Comida</th>
                                    <th>Glucosa</th>
                                    <th>Hora</th>
                                    <th>Corrección</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($registros['hipers'] as $hiper): ?>
                                <tr>
                                    <td><?php echo ucfirst($hiper['tipo_comida']); ?></td>
                                    <td><?php echo $hiper['glucosa']; ?></td>
                                    <td><?php echo $hiper['hora']; ?></td>
                                    <td><?php echo $hiper['correccion']; ?></td>
                                    <td>
                                        <?php if (isset($hiper['id'])): ?>
                                            <button class="btn btn-sm btn-warning" 
                                                    onclick="editarHiper(<?php echo $hiper['id']; ?>)">Editar</button>
                                            <button class="btn btn-sm btn-danger" 
                                                    onclick="eliminarRegistro('hiperglucemia', <?php echo $hiper['id']; ?>)">Eliminar</button>
                                        <?php else: ?>
                                            <span class="text-danger">Error: ID no encontrado</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer siempre al final -->
    <footer class="bg-dark text-white py-4">
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
        function eliminarRegistro(tipo, id) {
            if (confirm('¿Estás seguro de que deseas eliminar este registro?')) {
                window.location.href = `delete.php?tipo=${tipo}&id=${id}&fecha=<?php echo $fecha; ?>`;
            }
        }

        function editarComida(id) {
            window.location.href = `update.php?tipo=comida&id=${id}`;
        }

        function editarHipo(id) {
            window.location.href = `update.php?tipo=hipoglucemia&id=${id}`;
        }

        function editarHiper(id) {
            window.location.href = `update.php?tipo=hiperglucemia&id=${id}`;
        }
    </script>
</body>
</html>
