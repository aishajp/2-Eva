<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

$error = '';
$success = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $database = new Database();
    $db = $database->getConnection();
    
    $nombre = sanitizeInput($_POST['nombre']);
    $apellidos = sanitizeInput($_POST['apellidos']);
    $usuario = sanitizeInput($_POST['usuario']);
    $contra = sanitizeInput($_POST['contra']);
    $fecha_nacimiento = sanitizeInput($_POST['fecha_nacimiento']);
    
    // Verificar si el usuario ya existe
    $stmt = $db->prepare("SELECT id_usu FROM usuario WHERE usuario = ?");
    $stmt->execute([$usuario]);
    
    if ($stmt->rowCount() > 0) {
        $error = "Este nombre de usuario ya está registrado";
    } else {
        // Insertar nuevo usuario
        $sql = "INSERT INTO usuario (nombre, apellidos, usuario, contra, fecha_nacimiento) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        
        try {
            $stmt->execute([
                $nombre,
                $apellidos,
                $usuario,
                hashPassword($contra),
                $fecha_nacimiento
            ]);
            $success = "Usuario registrado correctamente";
            
            // Cambiamos la forma de redirección
            echo "<script>
                    setTimeout(function() {
                        window.location.href = 'login.php';
                    }, 2000);
                  </script>";
            
        } catch(PDOException $e) {
            $error = "Error al registrar: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Control Diabetes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-center">Registro de Usuario</h3>
                    </div>
                    <div class="card-body">
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        <?php if ($success): ?>
                            <div class="alert alert-success"><?php echo $success; ?></div>
                        <?php endif; ?>

                        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" required>
                            </div>
                            <div class="mb-3">
                                <label for="apellidos" class="form-label">Apellidos</label>
                                <input type="text" class="form-control" id="apellidos" name="apellidos" required>
                            </div>
                            <div class="mb-3">
                                <label for="usuario" class="form-label">Usuario</label>
                                <input type="text" class="form-control" id="usuario" name="usuario" required>
                            </div>
                            <div class="mb-3">
                                <label for="contra" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" id="contra" name="contra" required>
                            </div>
                            <div class="mb-3">
                                <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                                <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" required>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Registrarse</button>
                            </div>
                        </form>
                        <div class="text-center mt-3">
                            <p>¿Ya tienes cuenta? <a href="login.php">Iniciar Sesión</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>