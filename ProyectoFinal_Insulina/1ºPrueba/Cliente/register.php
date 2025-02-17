<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['glucoseLevel'])) {
        $glucoseLevel = htmlspecialchars($_POST['glucoseLevel']);
        
        // Aquí puedes agregar lógica para guardar el nivel de glucosa en una base de datos.
        
        echo "Nivel de glucosa de $glucoseLevel mg/dL registrado con éxito.";
    } else {
        echo "Nivel de glucosa no proporcionado.";
    }
} else {
    echo "Método no permitido.";
}
?>