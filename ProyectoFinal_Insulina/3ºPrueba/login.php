<?php
// login.php
session_start();
require_once 'config.php';

// Si ya está logueado, redirigir a tabla.php
if(isset($_SESSION['usuario_id'])) {
    header("Location: tabla.php");
    exit;
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = conectarDB();
    
    $usuario = $conn->real_escape_string($_POST['usuario']);
    $contra = $_POST['contra'];
    
    $sql = "SELECT id, usuario, contra FROM usuario WHERE usuario = ?";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            // Verifica la contraseña
            if ($contra === $user['contra']) { // Cambiar esto a password_verify cuando se implemente el hash
                $_SESSION['usuario_id'] = $user['id'];
                $_SESSION['usuario'] = $user['usuario'];
                
                header("Location: tabla.php");
                exit;
            } else {
                $error = "La contraseña no es correcta";
            }
        } else {
            $error = "No se encontró una cuenta con ese usuario";
        }
        
        $stmt->close();
    }
    
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-center">Iniciar Sesión</h3>
                    </div>
                    <div class="card-body">
                        <?php if($error): ?>
                            <div class="alert alert-danger">
                                <?php echo $error; ?>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST" action="login.php">
                            <div class="mb-3">
                                <label for="usuario" class="form-label">Usuario</label>
                                <input type="text" class="form-control" id="usuario" name="usuario" required>
                            </div>
                            <div class="mb-3">
                                <label for="contra" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" id="contra" name="contra" required>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
                            </div>
                        </form>
                        <div class="text-center mt-3">
                            <p>¿No tienes una cuenta? <a href="register.html">Regístrate</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>