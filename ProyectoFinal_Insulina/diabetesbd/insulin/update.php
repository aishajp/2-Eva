<?php
require_once '../includes/functions.php';
require_once '../includes/insulin_functions.php';
require_once '../config/database.php';

redirectIfNotLoggedIn();

$database = new Database();
$db = $database->getConnection();

$tipo = $_GET['tipo'] ?? '';
$id = $_GET['id'] ?? '';
$registro = null;
$mensaje = '';
$tipo_mensaje = '';

// Obtener el registro actual
if ($tipo && $id) {
    try {
        $sql = "SELECT * FROM $tipo WHERE id = :id AND id_usu = :id_usu";
        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':id' => $id,
            ':id_usu' => $_SESSION['user_id']
        ]);
        $registro = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        $mensaje = "Error al obtener el registro";
        $tipo_mensaje = "danger";
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
                        WHERE id = :id AND id_usu = :id_usu";
                break;

            case 'hipoglucemia':
                $datos = [
                    ':glucosa' => $_POST['glucosa'],
                    ':hora' => $_POST['hora']
                ];
                $sql = "UPDATE hipoglucemia SET 
                        glucosa = :glucosa,
                        hora = :hora
                        WHERE id = :id AND id_usu = :id_usu";
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
                        WHERE id = :id AND id_usu = :id_usu";
                break;
        }

        $datos[':id'] = $id;
        $datos[':id_usu'] = $_SESSION['user_id'];
        
        $stmt = $db->prepare($sql);
        if ($stmt->execute($datos)) {
            $mensaje = "Registro actualizado correctamente";
            $tipo_mensaje = "success";
            header("refresh:2;url=read.php"); // Redirige después de 2 segundos
        }
    } catch(PDOException $e) {
        $mensaje = "Error al actualizar el registro";
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
                <h3>Actualizar Registro de <?php echo ucfirst($tipo); ?></h3>
            </div>
            <div class="card-body">
                <?php if ($registro): ?>
                    <form method="POST">
                        <?php if ($tipo == 'comida'): ?>
                            <div class="mb-3">
                                <label class="form-label">Tipo de Comida</label>
                                <input type="text" class="form-control" value="<?php echo ucfirst($registro['tipo_comida']); ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Glucosa 1h</label>
                                <input type="number" class="form-control" name="gl_1h" value="<?php echo $registro['gl_1h']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Glucosa 2h</label>
                                <input type="number" class="form-control" name="gl_2h" value="<?php echo $registro['gl_2h']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Raciones</label>
                                <input type="number" class="form-control" name="raciones" step="0.1" value="<?php echo $registro['raciones']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Insulina</label>
                                <input type="number" class="form-control" name="insulina" step="0.1" value="<?php echo $registro['insulina']; ?>" required>
                            </div>

                        <?php elseif ($tipo == 'hipoglucemia'): ?>
                            <div class="mb-3">
                                <label class="form-label">Tipo de Comida</label>
                                <input type="text" class="form-control" value="<?php echo ucfirst($registro['tipo_comida']); ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Glucosa</label>
                                <input type="number" class="form-control" name="glucosa" value="<?php echo $registro['glucosa']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Hora</label>
                                <input type="time" class="form-control" name="hora" value="<?php echo $registro['hora']; ?>" required>
                            </div>

                        <?php elseif ($tipo == 'hiperglucemia'): ?>
                            <div class="mb-3">
                                <label class="form-label">Tipo de Comida</label>
                                <input type="text" class="form-control" value="<?php echo ucfirst($registro['tipo_comida']); ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Glucosa</label>
                                <input type="number" class="form-control" name="glucosa" value="<?php echo $registro['glucosa']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Hora</label>
                                <input type="time" class="form-control" name="hora" value="<?php echo $registro['hora']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Corrección</label>
                                <input type="number" class="form-control" name="correccion" step="0.1" value="<?php echo $registro['correccion']; ?>" required>
                            </div>
                        <?php endif; ?>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Actualizar</button>
                            <a href="read.php" class="btn btn-secondary">Cancelar</a>
                        </div>
                    </form>
                <?php else: ?>
                    <div class="alert alert-danger">
                        No se encontró el registro o no tienes permiso para editarlo.
                    </div>
                    <a href="read.php" class="btn btn-secondary">Volver</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        // Validación básica del lado del cliente
        document.querySelector('form')?.addEventListener('submit', function(e) {
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
    </script>
</body>
</html>