<?php

require_once 'auth.php';

verificarSesion();
?>


<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Registro y seguimiento de niveles de glucosa para pacientes diabéticos. Formulario para registrar mediciones, comidas y eventos especiales.">
  <title>Registro de Glucosa | MiDiabetes</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- CSS personalizado -->
  <link rel="stylesheet" href="css/formulario_style.css">
</head>
<body>
  <!-- Skip to main content link -->
  <a href="#main-content" class="skip-link visually-hidden-focusable">Saltar al contenido principal</a>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a class="navbar-brand" href="#" aria-label="Control Glucosa - Página Principal">
        Control Glucosa
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
              aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link" href="formulario.html">Añadir registro</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="tabla.html">Tabla de registros</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="logout.php">Cerrar Sesión</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  
  <!-- Main Content -->
  <main id="main-content">
    <!-- Sección para ver registros anteriores -->
    <section class="py-4 bg-light" aria-labelledby="section-registros">
      <div class="container">
        <h3 id="section-registros" class="text-center mb-3">Ver Registros Anteriores</h3>
        <div class="row justify-content-center">
          <div class="col-md-6">
            <div class="input-group">
              <label for="filterDate" class="input-group-text">Fecha</label>
              <input type="date" class="form-control" id="filterDate" name="filterDate" aria-label="Fecha para filtrar registros">
              <button class="btn btn-outline-primary" type="button" id="btnFilter">Ver Registros</button>
            </div>
          </div>
        </div>
      </div>
    </section>
    
    <!-- Sección del formulario -->
    <section class="py-4" aria-labelledby="section-formulario">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-6 col-md-8">
            <div class="form-container shadow-sm p-4">
              <h2 id="section-formulario" class="text-center mb-4">Registro de Glucosa</h2>
              <form action="procesar_glucosa.php" method="POST" novalidate>
                <!-- Campo de fecha -->
                <div class="mb-3">
                  <label for="fecha" class="form-label">Fecha</label>
                  <input type="date" class="form-control" id="fecha" name="fecha" required 
                         aria-describedby="fechaHelp">
                  <div id="fechaHelp" class="form-text">Selecciona la fecha del registro</div>
                </div>
                
                <!-- Tipo de Comida -->
                <fieldset class="mb-3">
                  <legend class="form-label">Tipo de Comida</legend>
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
                </fieldset>
                
                <!-- Glucosa 1 -->
                <div class="mb-3">
                  <label for="glucosa1" class="form-label">Glucosa 1 (mg/dL)</label>
                  <input type="number" step="any" class="form-control" id="glucosa1" name="glucosa1" 
                         placeholder="Ingresa el valor de Glucosa 1" required
                         aria-describedby="glucosa1Help">
                  <div id="glucosa1Help" class="form-text">Ingresa el valor de glucosa antes de la comida</div>
                </div>

                <!-- Glucosa 2 -->
                <div class="mb-3">
                  <label for="glucosa2" class="form-label">Glucosa 2 (mg/dL)</label>
                  <input type="number" step="any" class="form-control" id="glucosa2" name="glucosa2" 
                         placeholder="Ingresa el valor de Glucosa 2" required
                         aria-describedby="glucosa2Help">
                  <div id="glucosa2Help" class="form-text">Ingresa el valor de glucosa después de la comida</div>
                </div>

                <!-- Raciones -->
                <div class="mb-3">
                  <label for="raciones" class="form-label">Raciones</label>
                  <input type="number" step="any" class="form-control" id="raciones" name="raciones" 
                         placeholder="Número de raciones" required aria-describedby="racionesHelp">
                  <div id="racionesHelp" class="form-text">
                    Desayuno/Merienda: máximo 3 raciones. Almuerzo/Cena: máximo 7 raciones
                  </div>
                </div>
                
                <!-- Evento Adicional -->
                <fieldset class="mb-3">
                  <legend class="form-label">Evento Adicional</legend>
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
                </fieldset>
                
                <!-- Campos para Hipoglucemia -->
                <div id="hypoFields" class="d-none">
                  <div class="mb-3">
                    <label for="hypo_glucosa" class="form-label">Glucosa en Hipoglucemia (mg/dL)</label>
                    <input type="number" step="any" class="form-control" id="hypo_glucosa" name="hypo_glucosa" 
                           placeholder="Valor de glucosa" aria-describedby="hypoGlucosaHelp">
                    <div id="hypoGlucosaHelp" class="form-text">Registra el valor de glucosa durante el episodio</div>
                  </div>
                  <div class="mb-3">
                    <label for="hypo_hora" class="form-label">Hora de Hipoglucemia</label>
                    <input type="time" class="form-control" id="hypo_hora" name="hypo_hora" 
                           aria-describedby="hypoHoraHelp">
                    <div id="hypoHoraHelp" class="form-text">Registra la hora del episodio</div>
                  </div>
                </div>
                
                <!-- Campos para Hiperglucemia -->
                <div id="hyperFields" class="d-none">
                  <div class="mb-3">
                    <label for="hyper_glucosa" class="form-label">Glucosa en Hiperglucemia (mg/dL)</label>
                    <input type="number" step="any" class="form-control" id="hyper_glucosa" name="hyper_glucosa" 
                           placeholder="Valor de glucosa" aria-describedby="hyperGlucosaHelp">
                    <div id="hyperGlucosaHelp" class="form-text">Registra el valor de glucosa durante el episodio</div>
                  </div>
                  <div class="mb-3">
                    <label for="hyper_hora" class="form-label">Hora de Hiperglucemia</label>
                    <input type="time" class="form-control" id="hyper_hora" name="hyper_hora" 
                           aria-describedby="hyperHoraHelp">
                    <div id="hyperHoraHelp" class="form-text">Registra la hora del episodio</div>
                  </div>
                  <div class="mb-3">
                    <label for="correccion" class="form-label">Corrección (Unidades)</label>
                    <input type="number" step="any" class="form-control" id="correccion" name="correccion" 
                           placeholder="Valor de corrección" aria-describedby="correccionHelp">
                    <div id="correccionHelp" class="form-text">Ingresa las unidades de insulina para corrección</div>
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
  </main>
  
  <!-- Footer -->
  <footer class="footer bg-dark text-white text-center p-3">
    <div class="container">
      <p class="mb-0">© 2025 MiDiabetes. Todos los derechos reservados.</p>
    </div>
  </footer>
  
  <!-- Bootstrap JS Bundle con Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  
  <!-- Script personalizado -->
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
        
        // Reset required states
        const hypoInputs = hypoFields.querySelectorAll('input');
        const hyperInputs = hyperFields.querySelectorAll('input');
        
        hypoInputs.forEach(input => input.required = false);
        hyperInputs.forEach(input => input.required = false);
        
        if (value === "hypo") {
          hypoFields.classList.remove("d-none");
          hyperFields.classList.add("d-none");
          hypoInputs.forEach(input => input.required = true);
        } else if (value === "hyper") {
          hyperFields.classList.remove("d-none");
          hypoFields.classList.add("d-none");
          hyperInputs.forEach(input => input.required = true);
        } else {
          hypoFields.classList.add("d-none");
          hyperFields.classList.add("d-none");
        }
      });
    });
    
    // Ajustar el máximo de raciones según el tipo de comida
    document.querySelectorAll('input[name="tipo_comida"]').forEach((elem) => {
      elem.addEventListener("change", function(event) {
        const mealType = event.target.value;
        const racionesField = document.getElementById("raciones");
        
        if (mealType === "desayuno" || mealType === "merienda") {
          racionesField.max = 3;
          racionesField.min= 0;
        } else if (mealType === "almuerzo" || mealType === "cena") {
          racionesField.max = 7;
          racionesField.min= 0;

        }
        
        // Actualizar el mensaje de ayuda
        const maxRaciones = racionesField.max;
        racionesField.nextElementSibling.textContent = 
          `Máximo ${maxRaciones} ${maxRaciones === 1 ? 'ración' : 'raciones'} para ${mealType}`;
      });
    });
    
    // Form validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(event) {
      if (!form.checkValidity()) {
        event.preventDefault();
        event.stopPropagation();
      }
      form.classList.add('was-validated');
    });
  </script>
</body>
</html>
</antArt