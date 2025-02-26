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
$query = "SELECT nombre FROM jugador WHERE login = '$login'";
$result = $conn->query($query);
$nombre = "";
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $nombre = $row["nombre"];
}

// Inicializar el contador de cartas levantadas
if (!isset($_SESSION["cartasLevantadas"])) {
    $_SESSION["cartasLevantadas"] = 0;
}

// Generar combinación de cartas aleatoria si no existe
if (!isset($_SESSION["combinacion"])) {
    $_SESSION["combinacion"] = generarCombinacionAleatoria();
}

// Manejar la acción de levantar una carta
$cartaLevantada = null;
if (isset($_POST["levantar"])) {
    $cartaLevantada = $_POST["posicion"];
    $_SESSION["cartasLevantadas"]++;
}

// Función para generar una combinación aleatoria de cartas
function generarCombinacionAleatoria() {
    // Crear un array con pares de cartas (1,1,2,2,3,3)
    $cartas = [1, 1, 2, 2, 3, 3];
    
    // Mezclar el array
    shuffle($cartas);
    
    return $cartas;
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Juego de Cartas</title>
    <style>
        .carta {
            width: 150px;
            height: 200px;
            margin: 10px;
            display: inline-block;
        }
        .carta-boca-abajo {
            background-color: black;
        }
        .contenedor-cartas {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }
        .controles {
            margin-top: 20px;
            text-align: center;
        }
        .form-group {
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <h2>Bienvenido, <?php echo $nombre; ?>!</h2>
    
    <div class="controles">
        <p>Cartas Levantadas: <?php echo $_SESSION["cartasLevantadas"]; ?></p>
    </div>
    
    <div class="contenedor-cartas">
        <?php
        // Mostrar las 6 cartas
        for ($i = 1; $i <= 6; $i++) {
            $cartaValue = $_SESSION["combinacion"][$i-1];
            $mostrarBocaArriba = ($cartaLevantada == $i);
            
            // Determinar la imagen de la carta
            $cartaImg = "";
            if ($mostrarBocaArriba) {
                $cartaImg = "Image " . ($cartaValue + 1); // Imagen 2, 3 o 4 según el valor
            }
        ?>
        <div class="carta <?php echo $mostrarBocaArriba ? '' : 'carta-boca-abajo'; ?>">
            <?php if ($mostrarBocaArriba) { ?>
                <img src="copas_0<?php echo $cartaValue; ?>.jpg" alt="Carta <?php echo $cartaValue; ?>" width="150">
            <?php } ?>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <input type="hidden" name="posicion" value="<?php echo $i; ?>">
                <button type="submit" name="levantar">Levantar carta <?php echo $i; ?></button>
            </form>
        </div>
        <?php
        }
        ?>
    </div>
    
    <div class="controles">
        <form method="post" action="resultado.php">
            <div class="form-group">
                <label for="pareja1">Pareja - Posición 1 (1-6):</label>
                <input type="number" id="pareja1" name="pareja1" min="1" max="6" required>
            </div>
            <div class="form-group">
                <label for="pareja2">Pareja - Posición 2 (1-6):</label>
                <input type="number" id="pareja2" name="pareja2" min="1" max="6" required>
            </div>
            <button type="submit" name="comprobar">Comprobar</button>
        </form>
    </div>
</body>
</html>