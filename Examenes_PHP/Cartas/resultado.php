<?php
session_start();
require_once('conexion.php');

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION["login"])) {
    header("Location: entrada.php");
    exit();
}

// Obtener el nombre del usuario
$login = $_SESSION["login"];
$query = "SELECT nombre FROM jugador WHERE login = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $login);
$stmt->execute();
$result = $stmt->get_result();
$nombre = "";
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $nombre = $row["nombre"];
}

// Verificar si se ha enviado el formulario
if (!isset($_POST["pareja1"]) || !isset($_POST["pareja2"])) {
    header("Location: mostrarcartas.php");
    exit();
}

// Obtener las posiciones seleccionadas
$pareja1 = (int)$_POST["pareja1"];
$pareja2 = (int)$_POST["pareja2"];

// Obtener el número de intentos (cartas levantadas)
$intentos = $_SESSION["cartasLevantadas"];

// Obtener la combinación de cartas
$combinacion = $_SESSION["combinacion"];

// Comprobar si las posiciones seleccionadas forman una pareja
$esAcierto = false;
if (
    $pareja1 >= 1 && $pareja1 <= 6 && 
    $pareja2 >= 1 && $pareja2 <= 6 && 
    $pareja1 != $pareja2
) {
    $esAcierto = ($combinacion[$pareja1-1] == $combinacion[$pareja2-1]);
}

// Actualizar los puntos y los intentos en la base de datos
$puntos = $esAcierto ? 1 : -1;
$stmt = $conn->prepare("UPDATE jugador SET puntos = puntos + ?, extra = extra + ? WHERE login = ?");
$stmt->bind_param("iis", $puntos, $intentos, $login);
$stmt->execute();

// Reiniciar el juego
$_SESSION["cartasLevantadas"] = 0;
$_SESSION["combinacion"] = null;

// Obtener la tabla de puntuaciones actualizada
$queryPuntuaciones = "SELECT nombre, login, puntos, extra FROM jugador ORDER BY puntos DESC, extra ASC";
$resultPuntuaciones = $conn->query($queryPuntuaciones);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado</title>
    <style>
        table {
            border-collapse: collapse;
            width: 80%;
            margin: 20px auto;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .resultado {
            margin: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .acierto {
            background-color: #dff0d8;
            color: #3c763d;
        }
        .fallo {
            background-color: #f2dede;
            color: #a94442;
        }
    </style>
</head>
<body>
    <h2>Bienvenido, <?php echo $nombre; ?>!</h2>
    
    <div class="resultado <?php echo $esAcierto ? 'acierto' : 'fallo'; ?>">
        <?php if ($esAcierto) { ?>
            <h3>¡Acierto! Posiciones <?php echo $pareja1; ?> y <?php echo $pareja2; ?> después de <?php echo $intentos; ?> intentos</h3>
        <?php } else { ?>
            <h3>Fallo. Posiciones <?php echo $pareja1; ?> y <?php echo $pareja2; ?> después de <?php echo $intentos; ?> intentos</h3>
        <?php } ?>
    </div>
    
    <h3>Tabla de Puntuaciones</h3>
    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Usuario</th>
                <th>Puntos</th>
                <th>Extra</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $resultPuntuaciones->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row["nombre"]; ?></td>
                    <td><?php echo $row["login"]; ?></td>
                    <td><?php echo $row["puntos"]; ?></td>
                    <td><?php echo $row["extra"]; ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    
    <div>
        <a href="mostrarcartas.php">Jugar de nuevo</a>
    </div>
</body>
</html>