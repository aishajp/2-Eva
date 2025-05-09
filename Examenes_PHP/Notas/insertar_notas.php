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

        if($_SERVER["REQUEST_METHOD"]=="POST"){
            if(isset($_POST["id"])&&isset($_POST["asignatura"])&&isset($_POST["nota"])){
                $id = $_POST["id"]; 
                $asignatura = $_POST["asignatura"]; 
                $nota = $_POST["nota"]; 

                /* Buscamos el alummno */
                $sql = $conexion->prepare("SELECT notas.alumno, notas.asignatura, usuarios.id
                FROM `notas` JOIN `usuarios` ON notas.alumno = usuarios.id;"); 
                 $sql->execute(); 
                 $result = $sql->get_result(); 
                 if($result ->num_rows>0){
                    $insertar = $conexion ->prepare("INSERT INTO `notas` (alumno, asignatura, nota) VALUES (?, ?,?)");
                    $insertar->bind_param("isd", $id, $asignatura, $nota);
                    if($insertar->execute()){
                        echo " Se ha insertado correctamente"; 
                    }else{
                        echo " No se ha enviado nada";
                    } 
                 }else{
                    echo " No se encuentra el alumno correspondiente";
                 }
            }
        }

   // $insertar = $conexion->prepare();     

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Insertar Nota</h1>
    <form action="" method="post">
        <label for="id">ID del Alumno</label><br>
        <input type="number" name="id"><br>
        <label for="asignatura">Asignatura</label><br>
        <input type="text" name="asignatura"><br>
        <label for="nota">Nota (0-10)</label><br>
        <input type="number" name="nota" min="0" max="10"><br><br>
        <button type="submit" name="enviar">Insertar Nota</button>
    </form>
</body>
</html>