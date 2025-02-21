<?php
<<<<<<< HEAD
/*Conectar a la base de datos
=======
//Conectar a la base de datos
>>>>>>> e3fd474223077cd8b1957140e40f552c3ed87b56
$db_host = 'localhost:8080';
$db_user = 'root';
$db_pass = ' ';
$db_name = 'diabetesdb';

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
<<<<<<< HEAD
}*/
=======
}
>>>>>>> e3fd474223077cd8b1957140e40f552c3ed87b56

// Manjer AJAX para la validacion del mes y el año
if (isset($_GET['action']) && $_GET['action'] == 'get_month_data') {
    $month = intval($_GET['month']);
    $year = intval($_GET['year']);
    
    // Validar mes y año
    if ($month < 1 || $month > 12 || $year < 2000 || $year > 2100) {
        echo json_encode(['error' => 'Invalid date']);
        exit;
    }

    // Query to get all records for the selected month
    $query = "SELECT 
                DATE_FORMAT(fecha, '%d') as day,
                tipo_comida,
                glucosa1,
                glucosa2,
                raciones,
                insulina,
                evento,
                hypo_glucosa,
                hypo_hora,
                hyper_glucosa,
                hyper_hora,
                correccion,
                insulina_lenta
              FROM registros 
              WHERE MONTH(fecha) = ? AND YEAR(fecha) = ?
              ORDER BY fecha, tipo_comida";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $month, $year);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $data = [];
    while ($row = $result->fetch_assoc()) {
        if (!isset($data[$row['day']])) {
            $data[$row['day']] = [
                'desayuno' => null,
                'comida' => null,
                'cena' => null
            ];
        }
        $data[$row['day']][$row['tipo_comida']] = $row;
    }
    
    echo json_encode($data);
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro Mensual de Diabetes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/tabla_style.css">
</head>
<body>
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
<<<<<<< HEAD
            <a class="nav-link" href="tabla.php">Tabla de registros</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="diagrama.php">Diagrama</a>
=======
            <a class="nav-link" href="tabla.html">Tabla de registros</a>
>>>>>>> e3fd474223077cd8b1957140e40f552c3ed87b56
          </li>
          <li class="nav-item">
            <a class="nav-link" href="logout.php">Cerrar Sesión</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
    <div class="container-fluid">
        <!-- Secccion para la fecha -->
        <div class="date-selector">
            <div class="row">
                <div class="col-md-6">
                    <h4>Seleccionar Período</h4>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="monthSelect" class="form-label">Mes</label>
                            <select class="form-select" id="monthSelect">
                                <option value="1">Enero</option>
                                <option value="2">Febrero</option>
                                <option value="3">Marzo</option>
                                <option value="4">Abril</option>
                                <option value="5">Mayo</option>
                                <option value="6">Junio</option>
                                <option value="7">Julio</option>
                                <option value="8">Agosto</option>
                                <option value="9">Septiembre</option>
                                <option value="10">Octubre</option>
                                <option value="11">Noviembre</option>
                                <option value="12">Diciembre</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="yearSelect" class="form-label">Año</label>
                            <select class="form-select" id="yearSelect">
                                <!-- Se llenará con JavaScript -->
                            </select>
                        </div>
                        <div class="col-12">
                            <button type="button" class="btn btn-primary" id="btnLoadData">
                                Cargar Datos
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contenedor de la tabla -->
        <div class="table-container">
            <table class="diabetes-table">
                <thead>
                    <tr>
                        <th class="day-column"></th>
                        <th colspan="4" class="meal-header">DESAYUNO</th>
                        <th colspan="5" class="hypo-hyper-header"></th>
                        <th colspan="4" class="meal-header">COMIDA</th>
                        <th colspan="5" class="hypo-hyper-header"></th>
                        <th colspan="4" class="meal-header">CENA</th>
                        <th colspan="5" class="hypo-hyper-header"></th>
                        <th class="lenta-column">LENTA</th>
                    </tr>
                    <tr>
                        <th></th>
                        <!-- Desayuno -->
                        <th class="regular-columns">GL/1H</th>
                        <th class="regular-columns">RAC.</th>
                        <th class="regular-columns">INSU.</th>
                        <th class="regular-columns">GL/2H</th>
                        <!-- Desayuno hypo/hyper -->
                        <th class="hypo-column">GLU.</th>
                        <th class="hypo-column">HORA</th>
                        <th class="hyper-column">GLU.</th>
                        <th class="hyper-column">HORA</th>
                        <th class="hyper-column">CORR.</th>
                        <!-- Comida -->
                        <th class="regular-columns">GL/1H</th>
                        <th class="regular-columns">RAC.</th>
                        <th class="regular-columns">INSU.</th>
                        <th class="regular-columns">GL/2H</th>
                        <!-- Comida hypo/hyper -->
                        <th class="hypo-column">GLU.</th>
                        <th class="hypo-column">HORA</th>
                        <th class="hyper-column">GLU.</th>
                        <th class="hyper-column">HORA</th>
                        <th class="hyper-column">CORR.</th>
                        <!-- Cena -->
                        <th class="regular-columns">GL/1H</th>
                        <th class="regular-columns">RAC.</th>
                        <th class="regular-columns">INSU.</th>
                        <th class="regular-columns">GL/2H</th>
                        <!-- Cena hypo/hyper -->
                        <th class="hypo-column">GLU.</th>
                        <th class="hypo-column">HORA</th>
                        <th class="hyper-column">GLU.</th>
                        <th class="hyper-column">HORA</th>
                        <th class="hyper-column">CORR.</th>
                        <!-- Lenta -->
                        <th></th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <!-- JavaScript -->
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Inicializar el año
        function initializeYearSelect() {
            const yearSelect = document.getElementById('yearSelect');
            const currentYear = new Date().getFullYear();
            for (let year = currentYear; year >= currentYear - 5; year--) {
                const option = document.createElement('option');
                option.value = year;
                option.textContent = year;
                yearSelect.appendChild(option);
            }
        }

        // Inicializar mes señeccionar mes actual
        function initializeMonthSelect() {
            const monthSelect = document.getElementById('monthSelect');
            const currentMonth = new Date().getMonth() + 1;
            monthSelect.value = currentMonth;
        }

        // General tabla filas para el mes dado
        function generateTableRows(month, year) {
            const tableBody = document.getElementById('tableBody');
            tableBody.innerHTML = ''; // Clear existing rows

            const daysInMonth = new Date(year, month, 0).getDate();
            
            for (let day = 1; day <= daysInMonth; day++) {
                const row = document.createElement('tr');
                
                // Añadir columna del dia
                const dayCell = document.createElement('td');
                dayCell.className = 'day-column';
                dayCell.textContent = `DIA ${day}`;
                row.appendChild(dayCell);
                
                // Añadir columna para datos
                for (let i = 0; i < 28; i++) {
                    const cell = document.createElement('td');
                    row.appendChild(cell);
                }
                
                tableBody.appendChild(row);
            }
        }

        // Cargar datos para el mes seleccionado
        async function loadMonthData() {
            const month = document.getElementById('monthSelect').value;
            const year = document.getElementById('yearSelect').value;
            
            try {
                // Here you would normally fetch data from your backend
                const response = await fetch(`get_month_data.php?month=${month}&year=${year}`);
                const data = await response.json();
                
                // Generate the table structure
                generateTableRows(month, year);
                
                // Fill the table with the fetched data
                // This is where you would populate the cells with actual data
                
            } catch (error) {
                console.error('Error loading data:', error);
                alert('Error al cargar los datos. Por favor, intente nuevamente.');
            }
        }

        // Event Listeners
        document.addEventListener('DOMContentLoaded', () => {
            initializeYearSelect();
            initializeMonthSelect();
            generateTableRows(new Date().getMonth() + 1, new Date().getFullYear());
            
            document.getElementById('btnLoadData').addEventListener('click', loadMonthData);
            
            // Export buttons (placeholder functions)
            document.getElementById('btnExportExcel').addEventListener('click', () => {
                alert('Función de exportar a Excel será implementada aquí');
            });
            
            document.getElementById('btnExportPDF').addEventListener('click', () => {
                alert('Función de exportar a PDF será implementada aquí');
            });
        });
    </script>
</body>
</html>