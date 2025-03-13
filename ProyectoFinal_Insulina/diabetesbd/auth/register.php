<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
// Mejora en seguridad de sesión
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
session_regenerate_id(true);

ob_start();

require_once '../config/database.php';
require_once '../includes/functions.php';

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $database = new Database();
    $db = $database->getConnection();
    if (!$db) {
        die("Error en la conexión a la base de datos");
    }
    
    // Validación de datos
    $nombre = sanitizeInput($_POST['nombre']);
    $apellidos = sanitizeInput($_POST['apellidos']);
    $usuario = sanitizeInput($_POST['usuario']);
    $contra = sanitizeInput($_POST['contra']);
    $fecha_nacimiento = sanitizeInput($_POST['fecha_nacimiento']);
    
    // Validar longitud y formato de usuario
    if (strlen($usuario) < 4) {
        $error = "El nombre de usuario debe tener al menos 4 caracteres";
    } 
    // Validar contraseña
    elseif (strlen($contra) < 8) {
        $error = "La contraseña debe tener al menos 8 caracteres";
    }
    // Validar que la contraseña tenga al menos un número
    elseif (!preg_match('/[0-9]/', $contra)) {
        $error = "La contraseña debe incluir al menos un número";
    }
    // Validar que la contraseña tenga al menos una letra mayúscula
    elseif (!preg_match('/[A-Z]/', $contra)) {
        $error = "La contraseña debe incluir al menos una letra mayúscula";
    }
    // Validar fecha de nacimiento (asegurarse de que sea una fecha válida)
    elseif (!validateDate($fecha_nacimiento)) {
        $error = "Por favor ingrese una fecha de nacimiento válida";
    }
    else {
        // Verificar si el usuario ya existe
        $stmt = $db->prepare("SELECT id_usu FROM usuario WHERE usuario = ?");
        $stmt->execute([$usuario]);
        
        if ($stmt->rowCount() > 0) {
            $error = "Este nombre de usuario ya está registrado";
        } else {
            $sql = "INSERT INTO usuario (nombre, apellidos, usuario, contra, fecha_nacimiento, fecha_registro) 
                    VALUES (?, ?, ?, ?, ?, NOW())";
            $stmt = $db->prepare($sql);
            
            try {
                $contraHash = password_hash($contra, PASSWORD_DEFAULT);
                $stmt->execute([
                    $nombre,
                    $apellidos,
                    $usuario,
                    $contraHash,
                    $fecha_nacimiento
                ]);

                ob_clean();
                
                $_SESSION['registro_exitoso'] = true;
                
                header("Location: login.php");
                exit();
            } catch(PDOException $e) {
                $error = "Error al registrar: " . $e->getMessage();
            }
        }
    }
}

function validateDate($date, $format = 'Y-m-d')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Control Diabetes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .card {
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background-color: #4285F4;
            color: white;
            border-radius: 15px 15px 0 0 !important;
            padding: 20px;
        }
        .btn-primary {
            background-color: #4285F4;
            border-color: #4285F4;
            padding: 10px;
        }
        .btn-primary:hover {
            background-color: #3367d6;
            border-color: #3367d6;
        }
        .form-control:focus {
            border-color: #4285F4;
            box-shadow: 0 0 0 0.25rem rgba(66, 133, 244, 0.25);
        }
        .password-toggle {
            position: absolute;
            right: 10px;
            top: 10px;
            cursor: pointer;
        }
        .password-container {
            position: relative;
        }
        .password-strength {
            height: 5px;
            margin-top: 5px;
            border-radius: 5px;
            transition: all 0.3s;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-center">Registro de Usuario</h3>
                    </div>
                    <div class="card-body p-4">
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        <?php if ($success): ?>
                            <div class="alert alert-success"><?php echo $success; ?></div>
                        <?php endif; ?>

                        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" id="registerForm">
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="apellidos" class="form-label">Apellidos</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text" class="form-control" id="apellidos" name="apellidos" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="usuario" class="form-label">Usuario</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-at"></i></span>
                                    <input type="text" class="form-control" id="usuario" name="usuario" required minlength="4">
                                </div>
                                <small class="form-text text-muted">Mínimo 4 caracteres</small>
                            </div>
                            <div class="mb-3">
                                <label for="contra" class="form-label">Contraseña</label>
                                <div class="input-group password-container">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control" id="contra" name="contra" required minlength="8">
                                    <span class="password-toggle" onclick="togglePassword()">
                                        <i class="fas fa-eye"></i>
                                    </span>
                                </div>
                                <div class="password-strength" id="passwordStrength"></div>
                                <small class="form-text text-muted">Mínimo 8 caracteres, incluyendo al menos un número y una letra mayúscula</small>
                            </div>
                            <div class="mb-4">
                                <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                    <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" required>
                                </div>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">Registrarse</button>
                            </div>
                        </form>
                        <div class="text-center mt-3">
                            <p>¿Ya tienes cuenta? <a href="login.php" class="text-decoration-none">Iniciar Sesión</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordField = document.getElementById('contra');
            const toggleIcon = document.querySelector('.password-toggle i');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        document.getElementById('contra').addEventListener('input', function() {
            const password = this.value;
            const strength = document.getElementById('passwordStrength');
            let score = 0;
            
            if (password.length >= 8) score++;
            if (password.match(/[A-Z]/)) score++;
            if (password.match(/[0-9]/)) score++;
            if (password.match(/[^a-zA-Z0-9]/)) score++;
            
            if (score === 0) {
                strength.style.width = '0%';
                strength.style.backgroundColor = '';
            } else if (score === 1) {
                strength.style.width = '25%';
                strength.style.backgroundColor = '#dc3545'; // Rojo
            } else if (score === 2) {
                strength.style.width = '50%';
                strength.style.backgroundColor = '#ffc107'; // Amarillo
            } else if (score === 3) {
                strength.style.width = '75%';
                strength.style.backgroundColor = '#0dcaf0'; // Azul claro
            } else {
                strength.style.width = '100%';
                strength.style.backgroundColor = '#198754'; // Verde
            }
        });
    </script>
</body>
</html>