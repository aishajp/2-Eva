<?php
    session_start();    
    require_once "conexion.php"; 
    $conexion = new mysqli($localhost, $usuario, $pw, $database);
    if($conexion->connect_error){
        die("Error de conexion");
    }

    if(isset($_POST["Entrar"])){
        if(isset($_POST["usuario"]) && isset($_POST["contra"])){
            $usuario = $_POST["usuario"]; 
            $contra = $_POST["contra"]; 

                $sql = $conexion->prepare("SELECT * FROM usuarios WHERE usuario = ? AND password=?"); 
                $sql->bind_param("ss", $usuario, $contra);
                $sql->execute(); 
                $result = $sql->get_result(); 

                if($result->num_rows>0){
                    $_SESSION["usuario"] = $usuario; 
                    header("Location: inicio.php"); 
                    exit();

                }else{
                    $_SESSION["error"] = 1;
                    header("Location: index.php"); 
                }
            
        }
    }



?>

<!-- Crearmos el formularios -->
 <!DOCTYPE html>
 <html lang="en">
 <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
 </head>
 <style>
    p{
        color: red; 
    }
 </style>
 <body>
    <h1>Iniciar Sesión</h1>
    <form action="index.php" method="POST">
        <label for="usuario">Usuario</label>
        <br>
        <input type="text" name="usuario"/>
        <br>
        <label for="contra">Contraseña</label>
        <br>
        <input type="password" name="contra"/>
        <br>
        <button type="submit" name="Entrar">Entrar</button>
    </form>
    <?php
        if(isset($_SESSION["error"])){
            echo "<p>Credenciales incorrectas</p>";
        }
    ?>
 </body>
 </html>