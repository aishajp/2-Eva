<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: inicio.html"); // Redirige a inicio de sesión si no está autenticado
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Registro de Glucosa</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Enlace a CSS personalizado -->
  <link rel="stylesheet" href="style_formularios.css">
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a class="navbar-brand" href="#">MiDiabetes</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
              aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
         <ul class="navbar-nav ms-auto">
           <li class="nav-item">
             <a class="nav-link active" href="formularios.php">Añadir registro</a>
           </li>
           <li class="nav-item">
             <a class="nav-link" href="inicio.html">Inicio</a>
           </li>
           <li class="nav-item">
             <a class="nav-link" href="#">Datos</a>
           </li>
           <li class="nav-item">
            <a class="nav-link" href="logout.php">Cerrar Sesión</a>
          </li>
          
         </ul>
      </div>
    </div>
  </nav>
  
  <!-- Sección para ver registros anteriores -->
  <section class="py-4 bg-light">
    <div class="container">
      <h3 class="text-center mb-3">Ver Registros Anteriores</h3>
      <div class="row justify-content-center">
        <div class="col-md-6">
          <div class="input-group">
            <span class="input-group-text" id="calendar-addon">Fecha</span>
            <input type="date" class="form-control" id="filterDate" name="filterDate">
            <button class="btn btn-outline-primary" type="button" id="btnFilter">Ver Registros</button>
          </div>
        </div>
      </div>
    </div>
  </section>
  
  <!-- Sección del formulario para ingresar datos de glucosa -->
  <section class="py-4">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
          <div class="form-container shadow-sm p-4">
            <h2 class="text-center mb-4">Registro de Glucosa</h2>
            <form action="procesar_glucosa.php" method="POST">
              <!-- Campo de fecha (se autocompleta con la fecha de hoy) -->
              <div class="mb-3">
                <label for="fecha" class="form-label">Fecha</label>
                <input type="date" class="form-control" id="fecha" name="fecha" required>
              </div>
              
              <!-- Tipo de Comida en formato check-inline -->
              <div class="mb-3">
                <label class="form-label">Tipo de Comida</label>
                <div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="tipo_comida" id="comida_desayuno" value="desayuno" required>
                    <label class="form-check-label" for="comida_desayuno">Desayuno</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="tipo_comida" id="comida_almuerzo" value="almuerzo" required>
                    <label class="form-check-label" for="comida_almuerzo">Almuerzo</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="tipo_comida" id="comida_cena" value="cena" required>
                    <label class="form-check-label" for="comida_cena">Cena</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="tipo_comida" id="comida_merienda" value="merienda" required>
                    <label class="form-check-label" for="comida_merienda">Merienda</label>
                  </div>
                </div>
              </div>
              
              <!-- Glucosa 1 -->
              <div class="mb-3">
                <label for="glucosa1" class="form-label">Glucosa 1</label>
                <input type="number" step="any" class="form-control" id="glucosa1" name="glucosa1" placeholder="Ingresa el valor de Glucosa 1" required>
              </div>
              <!-- Glucosa 2 -->
              <div class="mb-3">
                <label for="glucosa2" class="form-label">Glucosa 2</label>
                <input type="number" step="any" class="form-control" id="glucosa2" name="glucosa2" placeholder="Ingresa el valor de Glucosa 2" required>
              </div>
              <!-- Raciones -->
              <div class="mb-3">
                <label for="raciones" class="form-label">Raciones</label>
                <input type="number" step="any" class="form-control" id="raciones" name="raciones" placeholder="Número de raciones" required>
                <small id="racionesHelp" class="form-text text-muted">
                  (Desayuno/Merienda: máximo 3 raciones. Almuerzo/Cena: máximo 7 raciones)
                </small>
              </div>
              
              <!-- Selección de evento adicional -->
              <div class="mb-3">
                <label class="form-label">Evento Adicional</label>
                <div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="evento" id="evento_none" value="none" checked>
                    <label class="form-check-label" for="evento_none">Ninguno</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="evento" id="evento_hypo" value="hypo">
                    <label class="form-check-label" for="evento_hypo">Hipoglucemia</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="evento" id="evento_hyper" value="hyper">
                    <label class="form-check-label" for="evento_hyper">Hiperglucemia</label>
                  </div>
                </div>
              </div>
              
              <!-- Campos para Hipoglucemia (se muestran solo si se selecciona) -->
              <div id="hypoFields" class="d-none">
                <div class="mb-3">
                  <label for="hypo_glucosa" class="form-label">Glucosa (Hipoglucemia)</label>
                  <input type="number" step="any" class="form-control" id="hypo_glucosa" name="hypo_glucosa" placeholder="Valor de glucosa">
                </div>
                <div class="mb-3">
                  <label for="hypo_hora" class="form-label">Hora (Hipoglucemia)</label>
                  <input type="time" class="form-control" id="hypo_hora" name="hypo_hora">
                </div>
              </div>
              
              <!-- Campos para Hiperglucemia (se muestran solo si se selecciona) -->
              <div id="hyperFields" class="d-none">
                <div class="mb-3">
                  <label for="hyper_glucosa" class="form-label">Glucosa (Hiperglucemia)</label>
                  <input type="number" step="any" class="form-control" id="hyper_glucosa" name="hyper_glucosa" placeholder="Valor de glucosa">
                </div>
                <div class="mb-3">
                  <label for="hyper_hora" class="form-label">Hora (Hiperglucemia)</label>
                  <input type="time" class="form-control" id="hyper_hora" name="hyper_hora">
                </div>
                <div class="mb-3">
                  <label for="correccion" class="form-label">Corrección</label>
                  <input type="number" step="any" class="form-control" id="correccion" name="correccion" placeholder="Valor de corrección">
                </div>
              </div>
              
              <!-- Botón de envío -->
              <div class="d-grid">
                <button type="submit" class="btn btn-primary">Guardar Datos</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
  
  <!-- Footer -->
  <footer class="footer bg-dark text-white text-center p-3">
    <div class="container">
      <p class="mb-0">© 2025 MiDiabetes. Todos los derechos reservados.</p>
    </div>
  </footer>
  
  <!-- Bootstrap JS Bundle con Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  
  <!-- Script para autocompletar fechas, mostrar/ocultar campos de eventos y ajustar raciones -->
  <script>
    // Establece la fecha de hoy en los campos correspondientes
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('fecha').value = today;
    document.getElementById('filterDate').value = today;
    
    // Mostrar/Ocultar campos de Hipoglucemia e Hiperglucemia
    document.querySelectorAll('input[name="evento"]').forEach((elem) => {
      elem.addEventListener("change", function(event) {
        const value = event.target.value;
        const hypoFields = document.getElementById("hypoFields");
        const hyperFields = document.getElementById("hyperFields");
        if (value === "hypo") {
          hypoFields.classList.remove("d-none");
          hyperFields.classList.add("d-none");
          document.getElementById("hypo_glucosa").required = true;
          document.getElementById("hypo_hora").required = true;
          document.getElementById("hyper_glucosa").required = false;
          document.getElementById("hyper_hora").required = false;
          document.getElementById("correccion").required = false;
        } else if (value === "hyper") {
          hyperFields.classList.remove("d-none");
          hypoFields.classList.add("d-none");
          document.getElementById("hyper_glucosa").required = true;
          document.getElementById("hyper_hora").required = true;
          document.getElementById("correccion").required = true;
          document.getElementById("hypo_glucosa").required = false;
          document.getElementById("hypo_hora").required = false;
        } else {
          hypoFields.classList.add("d-none");
          hyperFields.classList.add("d-none");
          document.getElementById("hypo_glucosa").required = false;
          document.getElementById("hypo_hora").required = false;
          document.getElementById("hyper_glucosa").required = false;
          document.getElementById("hyper_hora").required = false;
          document.getElementById("correccion").required = false;
        }
      });
    });
    
    // Ajustar el atributo max del campo "raciones" según el tipo de comida seleccionado
    document.querySelectorAll('input[name="tipo_comida"]').forEach((elem) => {
      elem.addEventListener("change", function(event) {
        const mealType = event.target.value;
        const racionesField = document.getElementById("raciones");
        if (mealType === "desayuno" || mealType === "merienda") {
          racionesField.max = 3;
        } else if (mealType === "almuerzo" || mealType === "cena") {
          racionesField.max = 7;
        }
      });
    });
  </script>
</body>
</html>
