<?php
    session_start(); 
    require_once "conexion.php"; 
    $conexion = new mysqli($localhost, $usuario, $pw, $database);
    if($conexion->connect_error){
        die("Error de conexion");
    }

    $usuario = $_SESSION["usuario"]; 
    $sql = $conexion->prepare("SELECT rol FROM usuarios WHERE usuario = ?");
    $sql->bind_param("s", $usuario);
    $sql->execute(); 
    $result = $sql->get_result();

    if($result->num_rows>0){
       $fila = $result->fetch_assoc(); 
       $rol = $fila["rol"]; 
        
    }else{
        echo "Usuario no encontrado"; 
        exit();
    }


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Bienvenido, <?php echo htmlspecialchars($usuario)?></h1>
    <?php
        if($rol ==="alumno"):   
    ?>
    <p>Tu perfil es de alumno/a</p>
    <form action="" method="POST">
        <button type="submit"><a href="resultado_alumno.php">Ver Mis Notas</a></button>
        <button type="submit"><a href="index.php">Cerrar Sesión</a></button>
    </form>
    <?php elseif($rol==="director"):?>
        <p>Tu perfil es de Director/a</p>
        <form action="" method="POST">
            <button type="submit"><a href="insertar_notas.php">Insertar Nota</a></button>
            <button type="submit"><a href="mostrar_notas.php">Mostrar Notas</a></button>
            <button type="submit"><a href="index.php">Cerrar Sesión</a></button>
        </form>
    <?php endif; ?>    
</body>
</html>