<?php 

session_start(); 

    require_once "login.php"; 

    $nombre = $_POST['nombre']; 

    $apellidos = $_POST['apellidos']; 

    $fechaNacimiento = $_POST['fecha_nacimiento']; 

    $usuario = $_POST['usuario']; 

    $contra = $_POST['contra']; 

    $con = new mysqli ($localhost, $username, $pw, $database); 

     /*Validar contraseña*/ 

     /* if ($contra !== $repContra) { 

        echo "Las contraseñas no coinciden."; 

        exit; 

    }else{ */ 

        /*Validar usuario si existe en la base de datos*/ 

        $query = "SELECT * FROM usuario WHERE usuario = '$usuario'"; 

        $result = $con->query($query); 

        if($result->num_rows > 0){ 

            echo "El usuario ya existe."; 

            exit; 

        }else{ 

            $sql = "INSERT INTO usuario (fecha_nacimiento, nombre, apellidos, usuario, contra)  

                    VALUES ('$fechaNacimiento', '$nombre', '$apellidos', '$usuario', '$contra')"; 

            if ($con->query($sql) === TRUE) { 

                echo "Usuario creado correctamente."; 

            } else { 

                echo "Error: ". $sql. "<br>". $con->error; 

            } 

        } 

        

     

    echo "Registro completado"; 

    header('Location: index.html'); //Redirecciona al index al finalizar la operación. 

    $con->close(); 

     

?> 