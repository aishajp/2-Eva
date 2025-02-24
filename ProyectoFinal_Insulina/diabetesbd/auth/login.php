<?php
session_start();

require_once '../config/database.php';
require_once '../includes/functions.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $database = new Database();
    $db = $database->getConnection();
    
    $usuario = sanitizeInput($_POST['usuario']);
    $contra = sanitizeInput($_POST['contra']);
    
    $sql = "SELECT id_usu, contra FROM usuario WHERE usuario = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$usuario]);
    
    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (password_verify($contra, $row['contra'])) {
            $_SESSION['user_id'] = $row['id_usu'];
            header("Location: ../index.php");
            exit();
        } else {
            $error = "Contraseña incorrecta";
        }
    } else {
        $error = "Usuario no encontrado";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Control Diabetes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Estilos para los mensajes de validación */
        .error-message {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.25rem;
            display: none;
        }

        /* Estilos para campos válidos e inválidos */
        .is-invalid {
            border-color: #dc3545 !important;
        }

        .is-valid {
            border-color: #198754 !important;
        }
    </style>
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
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>

                        <form id="loginForm" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" novalidate>
                            <div class="mb-3">
                                <label for="usuario" class="form-label">Usuario</label>
                                <input type="text" 
                                       class="form-control" 
                                       id="usuario" 
                                       name="usuario" 
                                       required 
                                       minlength="4" 
                                       maxlength="50"
                                       pattern="[a-zA-Z0-9_-]+"
                                       value="<?php echo isset($_POST['usuario']) ? htmlspecialchars($_POST['usuario']) : ''; ?>">
                                <div class="error-message" id="usuarioError"></div>
                            </div>
                            <div class="mb-3">
                                <label for="contra" class="form-label">Contraseña</label>
                                <input type="password" 
                                       class="form-control" 
                                       id="contra" 
                                       name="contra" 
                                       required 
                                       minlength="6">
                                <div class="error-message" id="contraError"></div>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
                            </div>
                        </form>
                        <div class="text-center mt-3">
                            <p>¿No tienes cuenta? <a href="register.php">Registrarse</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Esta función realiza la validación del formulario
        document.getElementById('loginForm').addEventListener('submit', function(event) {
            // Prevenimos el envío del formulario por defecto
            event.preventDefault();
            
            // Obtenemos los valores de los campos
            const usuario = document.getElementById('usuario');
            const contra = document.getElementById('contra');
            
            // Reiniciamos los mensajes de error
            resetErrors();
            
            // Variable para controlar si hay errores
            let hasErrors = false;

            // Validación del usuario
            if (!usuario.value) {
                showError(usuario, 'usuarioError', 'El nombre de usuario es obligatorio');
                hasErrors = true;
            } else if (usuario.value.length < 4) {
                showError(usuario, 'usuarioError', 'El usuario debe tener al menos 4 caracteres');
                hasErrors = true;
            } else if (usuario.value.length > 50) {
                showError(usuario, 'usuarioError', 'El usuario no puede tener más de 50 caracteres');
                hasErrors = true;
            } else if (!/^[a-zA-Z0-9_-]+$/.test(usuario.value)) {
                showError(usuario, 'usuarioError', 'El usuario solo puede contener letras, números, guiones y guiones bajos');
                hasErrors = true;
            } else {
                markValid(usuario);
            }

            // Validación de la contraseña
            if (!contra.value) {
                showError(contra, 'contraError', 'La contraseña es obligatoria');
                hasErrors = true;
            } else if (contra.value.length < 6) {
                showError(contra, 'contraError', 'La contraseña debe tener al menos 6 caracteres');
                hasErrors = true;
            } else {
                markValid(contra);
            }

            // Si no hay errores, enviamos el formulario
            if (!hasErrors) {
                this.submit();
            }
        });

        // Función para mostrar errores
        function showError(input, errorId, message) {
            input.classList.remove('is-valid');
            input.classList.add('is-invalid');
            const errorDiv = document.getElementById(errorId);
            errorDiv.textContent = message;
            errorDiv.style.display = 'block';
        }

        // Función para marcar campo como válido
        function markValid(input) {
            input.classList.remove('is-invalid');
            input.classList.add('is-valid');
        }

        // Función para resetear errores
        function resetErrors() {
            const errorMessages = document.getElementsByClassName('error-message');
            for (let error of errorMessages) {
                error.style.display = 'none';
            }
        }

        // Validación en tiempo real mientras el usuario escribe
        const inputs = document.querySelectorAll('input');
        inputs.forEach(input => {
            input.addEventListener('input', function() {
                if (this.value) {
                    if (this.id === 'usuario') {
                        if (this.value.length >= 4 && this.value.length <= 50 && /^[a-zA-Z0-9_-]+$/.test(this.value)) {
                            markValid(this);
                            document.getElementById('usuarioError').style.display = 'none';
                        }
                    } else if (this.id === 'contra') {
                        if (this.value.length >= 6) {
                            markValid(this);
                            document.getElementById('contraError').style.display = 'none';
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>