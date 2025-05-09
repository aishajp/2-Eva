<?php
    session_start(); 
    require_once "conexion.php"; 
    $conexion = new mysqli($localhost, $usuario, $pw, $database);
    if($conexion->connect_error){
        die("Error de conexion");
    }

    $usuario = $_SESSION["usuario"]; 
    $sql = $conexion->prepare("SELECT usuarios.usuario, notas.asignatura, notas.fecha, notas.nota
    FROM notas
    JOIN usuarios ON notas.alumno = usuarios.id 
    WHERE usuarios.usuario=?
    
    ");
    $sql ->bind_param("s", $usuario);
    $sql->execute(); 
    $result = $sql->get_result(); 

if ($result->num_rows > 0) {
    echo " <h1>$usuario , tus notas son: </h1>";
    echo "<table border='1' cellpadding='5' cellspacing='0'>";
    echo "<tr><th>Asignatura</th><th>Nota</th></tr>";
    while ($fila = $result->fetch_assoc()) {
        //$alumno = $fila["usuario"]; 
        $asignatura = $fila["asignatura"]; 
        $nota = $fila["nota"];

        echo "<tr>
        <td>$asignatura</td>
        <td>$nota</td>
      </tr>";
}

echo "</table>";
    
} else {
    echo "No se han encontrado datos."; 
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
    <br>
    <button><a href="inicio.php">Volver</a></button>
</body>
</html>