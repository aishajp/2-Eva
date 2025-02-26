<?php
// Iniciar sesión solo si no hay una activa
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once '../includes/functions.php';
require_once '../includes/insulin_functions.php';
require_once '../config/database.php';

redirectIfNotLoggedIn();

// Verificar que el usuario esté correctamente autenticado
if (!isset($_SESSION['user_id'])) {
    $_SESSION['mensaje'] = "Error: La sesión ha expirado. Por favor, inicie sesión nuevamente.";
    $_SESSION['tipo_mensaje'] = "danger";
    header("Location: ../auth/login.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();

$tipo = $_GET['tipo'] ?? '';
$tipo_comida = $_GET['tipo_comida'] ?? '';
$fecha = $_GET['fecha'] ?? date('Y-m-d');
$id_usu = $_SESSION['user_id'];
$registro = null;
$mensaje = '';
$tipo_mensaje = '';

// Obtener el registro actual
if ($tipo && $tipo_comida && $fecha) {
    try {
        $sql = "SELECT * FROM $tipo WHERE tipo_comida = :tipo_comida AND fecha = :fecha AND id_usu = :id_usu";
        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':tipo_comida' => $tipo_comida,
            ':fecha' => $fecha,
            ':id_usu' => $id_usu
        ]);
        $registro = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$registro) {
            $_SESSION['mensaje'] = "No se encontró el registro solicitado o no tiene permisos para editarlo.";
            $_SESSION['tipo_mensaje'] = "warning";
            header("Location: read.php?fecha=" . $fecha);
            exit();
        }
    } catch(PDOException $e) {
        $_SESSION['mensaje'] = "Error al obtener el registro: " . $e->getMessage();
        $_SESSION['tipo_mensaje'] = "danger";
        header("Location: read.php?fecha=" . $fecha);
        exit();
    }
}

// Procesar el formulario de actualización
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        switch ($tipo) {
            case 'comida':
                $datos = [
                    ':gl_1h' => $_POST['gl_1h'],
                    ':gl_2h' => $_POST['gl_2h'],
                    ':raciones' => $_POST['raciones'],
                    ':insulina' => $_POST['insulina']
                ];
                $sql = "UPDATE comida SET 
                        gl_1h = :gl_1h,
                        gl_2h = :gl_2h,
                        raciones = :raciones,
                        insulina = :insulina
                        WHERE tipo_comida = :tipo_comida AND fecha = :fecha AND id_usu = :id_usu";
                break;

            case 'hipoglucemia':
                $datos = [
                    ':glucosa' => $_POST['glucosa'],
                    ':hora' => $_POST['hora']
                ];
                $sql = "UPDATE hipoglucemia SET 
                        glucosa = :glucosa,
                        hora = :hora
                        WHERE tipo_comida = :tipo_comida AND fecha = :fecha AND id_usu = :id_usu";
                break;

            case 'hiperglucemia':
                $datos = [
                    ':glucosa' => $_POST['glucosa'],
                    ':hora' => $_POST['hora'],
                    ':correccion' => $_POST['correccion']
                ];
                $sql = "UPDATE hiperglucemia SET 
                        glucosa = :glucosa,
                        hora = :hora,
                        correccion = :correccion
                        WHERE tipo_comida = :tipo_comida AND fecha = :fecha AND id_usu = :id_usu";
                break;
                
            default:
                $_SESSION['mensaje'] = "Tipo de registro no válido";
                $_SESSION['tipo_mensaje'] = "danger";
                header("Location: read.php?fecha=" . $fecha);
                exit();
        }

        $datos[':tipo_comida'] = $tipo_comida;
        $datos[':fecha'] = $fecha;
        $datos[':id_usu'] = $id_usu;
        
        $stmt = $db->prepare($sql);
        if ($stmt->execute($datos)) {
            $_SESSION['mensaje'] = "Registro actualizado correctamente";
            $_SESSION['tipo_mensaje'] = "success";
            header("Location: read.php?fecha=" . $fecha);
            exit();
        } else {
            $mensaje = "Error al actualizar el registro: " . implode(", ", $stmt->errorInfo());
            $tipo_mensaje = "danger";
        }
    } catch(PDOException $e) {
        $mensaje = "Error al actualizar el registro: " . $e->getMessage();
        $tipo_mensaje = "danger";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Registro - Control Diabetes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>
<body class="bg-light">
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
                        <a class="nav-link" href="create.php">Nuevo Registro</a>
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

        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0">Actualizar Registro de <?php echo ucfirst($tipo); ?></h3>
            </div>
            <div class="card-body">
                <?php if ($registro): ?>
                    <form method="POST" id="updateForm">
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                        <input type="hidden" name="tipo" value="<?php echo $tipo; ?>">
                        <input type="hidden" name="fecha" value="<?php echo $fecha; ?>">
                        
                        <?php if ($tipo == 'comida'): ?>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Tipo de Comida</label>
                                <input type="text" class="form-control" value="<?php echo ucfirst($registro['tipo_comida']); ?>" readonly>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Glucosa 1h después</label>
                                    <input type="number" class="form-control" name="gl_1h" value="<?php echo $registro['gl_1h']; ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Glucosa 2h después</label>
                                    <input type="number" class="form-control" name="gl_2h" value="<?php echo $registro['gl_2h']; ?>" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Raciones</label>
                                    <input type="number" class="form-control" name="raciones" step="0.1" value="<?php echo $registro['raciones']; ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Insulina</label>
                                    <input type="number" class="form-control" name="insulina" step="0.1" value="<?php echo $registro['insulina']; ?>" required>
                                </div>
                            </div>

                        <?php elseif ($tipo == 'hipoglucemia'): ?>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Tipo de Comida</label>
                                <input type="text" class="form-control" value="<?php echo ucfirst($registro['tipo_comida']); ?>" readonly>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Glucosa</label>
                                    <input type="number" class="form-control" name="glucosa" value="<?php echo $registro['glucosa']; ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Hora</label>
                                    <input type="time" class="form-control" name="hora" value="<?php echo $registro['hora']; ?>" required>
                                </div>
                            </div>

                        <?php elseif ($tipo == 'hiperglucemia'): ?>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Tipo de Comida</label>
                                <input type="text" class="form-control" value="<?php echo ucfirst($registro['tipo_comida']); ?>" readonly>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Glucosa</label>
                                    <input type="number" class="form-control" name="glucosa" value="<?php echo $registro['glucosa']; ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Hora</label>
                                    <input type="time" class="form-control" name="hora" value="<?php echo $registro['hora']; ?>" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Corrección</label>
                                <input type="number" class="form-control" name="correccion" step="0.1" value="<?php echo $registro['correccion']; ?>" required>
                            </div>
                        <?php endif; ?>

                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Actualizar
                            </button>
                            <a href="read.php?fecha=<?php echo $fecha; ?>" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Cancelar
                            </a>
                        </div>
                    </form>
                <?php else: ?>
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        No se encontró el registro o no tienes permiso para editarlo.
                    </div>
                    <a href="read.php?fecha=<?php echo $fecha; ?>" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Volver a Registros
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Footer añadido sin alterar la lógica del código -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container">
            <div class="text-center">
                <p class="mb-0">&copy; <?php echo date('Y'); ?> DiabetesControl. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Validación del lado del cliente
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('updateForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const inputs = this.querySelectorAll('input[required]');
                    let valid = true;

                    inputs.forEach(input => {
                        if (!input.value.trim()) {
                            valid = false;
                            input.classList.add('is-invalid');
                        } else {
                            input.classList.remove('is-invalid');
                        }
                    });

                    if (!valid) {
                        e.preventDefault();
                        alert('Por favor, completa todos los campos requeridos.');
                    }
                });
            }
        });
    </script>
</body>
</html>