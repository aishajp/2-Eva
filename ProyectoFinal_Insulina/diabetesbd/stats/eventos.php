<?php
require_once '../includes/functions.php';
require_once '../includes/insulin_functions.php';
require_once '../config/database.php';

redirectIfNotLoggedIn();

$database = new Database();
$db = $database->getConnection();

// Obtener datos del último mes
$fecha_inicio = date('Y-m-d', strtotime('-30 days'));
$fecha_fin = date('Y-m-d');

try {
    // Contar eventos por tipo de comida
    $sql_hipo = "SELECT 
                tipo_comida,
                COUNT(*) as total,
                AVG(glucosa) as promedio_glucosa
                FROM hipoglucemia 
                WHERE id_usu = :id_usu 
                AND fecha BETWEEN :fecha_inicio AND :fecha_fin
                GROUP BY tipo_comida";
    
    $sql_hiper = "SELECT 
                tipo_comida,
                COUNT(*) as total,
                AVG(glucosa) as promedio_glucosa,
                AVG(correccion) as promedio_correccion
                FROM hiperglucemia 
                WHERE id_usu = :id_usu 
                AND fecha BETWEEN :fecha_inicio AND :fecha_fin
                GROUP BY tipo_comida";
    
    $stmt_hipo = $db->prepare($sql_hipo);
    $stmt_hipo->execute([
        ':id_usu' => $_SESSION['user_id'],
        ':fecha_inicio' => $fecha_inicio,
        ':fecha_fin' => $fecha_fin
    ]);
    
    $stmt_hiper = $db->prepare($sql_hiper);
    $stmt_hiper->execute([
        ':id_usu' => $_SESSION['user_id'],
        ':fecha_inicio' => $fecha_inicio,
        ':fecha_fin' => $fecha_fin
    ]);
    
    $datos_hipo = $stmt_hipo->fetchAll(PDO::FETCH_ASSOC);
    $datos_hiper = $stmt_hiper->fetchAll(PDO::FETCH_ASSOC);
    
} catch(PDOException $e) {
    $error = "Error al obtener los datos: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eventos Glucémicos - Control Diabetes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-light">
    <div class="container mt-4">
        <div class="card">
            <div class="card-header">
                <h3>Resumen de Eventos Glucémicos</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Gráfico de eventos -->
                    <div class="col-md-6">
                        <canvas id="eventosChart"></canvas>
                    </div>
                    
                    <!-- Tablas de resumen -->
                    <div class="col-md-6">
                        <div class="card mb-3">
                            <div class="card-header bg-warning text-dark">
                                <h5 class="mb-0">Hipoglucemias</h5>
                            </div>
                            <div class="card-body">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Comida</th>
                                            <th>Total</th>
                                            <th>Promedio</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($datos_hipo as $hipo): ?>
                                        <tr>
                                            <td><?php echo ucfirst($hipo['tipo_comida']); ?></td>
                                            <td><?php echo $hipo['total']; ?></td>
                                            <td><?php echo round($hipo['promedio_glucosa'], 1); ?> mg/dL</td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <div class="card">
                            <div class="card-header bg-danger text-white">
                                <h5 class="mb-0">Hiperglucemias</h5>
                            </div>
                            <div class="card-body">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Comida</th>
                                            <th>Total</th>
                                            <th>Promedio</th>
                                            <th>Corrección</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($datos_hiper as $hiper): ?>
                                        <tr>
                                            <td><?php echo ucfirst($hiper['tipo_comida']); ?></td>
                                            <td><?php echo $hiper['total']; ?></td>
                                            <td><?php echo round($hiper['promedio_glucosa'], 1); ?> mg/dL</td>
                                            <td><?php echo round($hiper['promedio_correccion'], 1); ?> U</td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Preparar datos para el gráfico
        const tiposComida = ['Desayuno', 'Comida', 'Cena'];
        const datosHipo = <?php echo json_encode($datos_hipo); ?>;
        const datosHiper = <?php echo json_encode($datos_hiper); ?>;
        
        const eventosHipo = tiposComida.map(tipo => {
            const dato = datosHipo.find(d => d.tipo_comida.toLowerCase() === tipo.toLowerCase());
            return dato ? parseInt(dato.total) : 0;
        });
        
        const eventosHiper = tiposComida.map(tipo => {
            const dato = datosHiper.find(d => d.tipo_comida.toLowerCase() === tipo.toLowerCase());
            return dato ? parseInt(dato.total) : 0;
        });

        const ctx = document.getElementById('eventosChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: tiposComida,
                datasets: [{
                    label: 'Hipoglucemias',
                    data: eventosHipo,
                    backgroundColor: 'rgba(255, 193, 7, 0.5)',
                    borderColor: 'rgb(255, 193, 7)',
                    borderWidth: 1
                },
                {
                    label: 'Hiperglucemias',
                    data: eventosHiper,
                    backgroundColor: 'rgba(220, 53, 69, 0.5)',
                    borderColor: 'rgb(220, 53, 69)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Número de eventos'
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>