<?php
// Conexión a la base de datos
$db_host = 'localhost:8080';
$db_user = 'root';
$db_pass = ' ';
$db_name = 'diabetesdb';

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Manejo de solicitud AJAX para datos mensuales
if (isset($_GET['action']) && $_GET['action'] == 'get_month_data') {
    $month = intval($_GET['month']);
    $year = intval($_GET['year']);
    
    // Validación de mes y año
    if ($month < 1 || $month > 12 || $year < 2000 || $year > 2100) {
        echo json_encode(['error' => 'Fecha no válida']);
        exit;
    }

    // Consulta para obtener todos los registros del mes seleccionado
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
    <title>Visualización de Datos de Diabetes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://unpkg.com/react@17/umd/react.development.js"></script>
    <script src="https://unpkg.com/react-dom@17/umd/react-dom.development.js"></script>
    <script src="https://unpkg.com/babel-standalone@6/babel.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/recharts/2.1.9/Recharts.js"></script>
</head>
<body>
    <div id="root"></div>

    <script type="text/babel">
        import React, { useState, useEffect } from 'react';
        import { LineChart, Line, XAxis, YAxis, CartesianGrid, Tooltip, Legend, ResponsiveContainer } from 'recharts';

        const DiabetesCharts = () => {
            // Estados para almacenar datos y selección de fecha
            const [processedData, setProcessedData] = useState([]);
            const [currentMonth, setCurrentMonth] = useState(new Date().getMonth() + 1);
            const [currentYear, setCurrentYear] = useState(new Date().getFullYear());
            
            // Función para procesar los datos en el formato necesario para las gráficas
            const processData = (rawData) => {
                return Object.entries(rawData).map(([day, meals]) => ({
                    day: parseInt(day),
                    // Niveles de glucosa en el desayuno
                    breakfastGlucose1: meals.desayuno?.glucosa1 || null,
                    breakfastGlucose2: meals.desayuno?.glucosa2 || null,
                    // Niveles de glucosa en la comida
                    lunchGlucose1: meals.comida?.glucosa1 || null,
                    lunchGlucose2: meals.comida?.glucosa2 || null,
                    // Niveles de glucosa en la cena
                    dinnerGlucose1: meals.cena?.glucosa1 || null,
                    dinnerGlucose2: meals.cena?.glucosa2 || null,
                    // Datos de insulina
                    breakfastInsulin: meals.desayuno?.insulina || null,
                    lunchInsulin: meals.comida?.insulina || null,
                    dinnerInsulin: meals.cena?.insulina || null,
                    longActingInsulin: meals.desayuno?.insulina_lenta || null,
                })).sort((a, b) => a.day - b.day);
            };

            // Función para obtener datos del mes seleccionado
            const fetchMonthData = async (month, year) => {
                try {
                    const response = await fetch(`?action=get_month_data&month=${month}&year=${year}`);
                    const data = await response.json();
                    const processed = processData(data);
                    setProcessedData(processed);
                } catch (error) {
                    console.error('Error al obtener datos:', error);
                }
            };

            // Efecto para cargar datos cuando cambia el mes o año
            useEffect(() => {
                fetchMonthData(currentMonth, currentYear);
            }, [currentMonth, currentYear]);

            // Manejadores de eventos para cambios de mes y año
            const handleMonthChange = (event) => {
                setCurrentMonth(parseInt(event.target.value));
            };

            const handleYearChange = (event) => {
                setCurrentYear(parseInt(event.target.value));
            };

            return (
                <div className="container mt-4">
                    <div className="card">
                        <div className="card-header">
                            <h2>Control de Diabetes - Visualización de Datos</h2>
                        </div>
                        <div className="card-body">
                            {/* Selectores de mes y año */}
                            <div className="mb-4">
                                <select 
                                    className="form-select d-inline-block w-auto me-2"
                                    value={currentMonth}
                                    onChange={handleMonthChange}
                                >
                                    {[...Array(12)].map((_, i) => (
                                        <option key={i + 1} value={i + 1}>
                                            {new Date(2024, i, 1).toLocaleString('es', { month: 'long' })}
                                        </option>
                                    ))}
                                </select>
                                
                                <select 
                                    className="form-select d-inline-block w-auto"
                                    value={currentYear}
                                    onChange={handleYearChange}
                                >
                                    {[...Array(5)].map((_, i) => {
                                        const year = new Date().getFullYear() - i;
                                        return (
                                            <option key={year} value={year}>
                                                {year}
                                            </option>
                                        );
                                    })}
                                </select>
                            </div>

                            {/* Pestañas de navegación */}
                            <ul className="nav nav-tabs" role="tablist">
                                <li className="nav-item">
                                    <a className="nav-link active" data-bs-toggle="tab" href="#glucose">
                                        Niveles de Glucosa
                                    </a>
                                </li>
                                <li className="nav-item">
                                    <a className="nav-link" data-bs-toggle="tab" href="#insulin">
                                        Insulina
                                    </a>
                                </li>
                            </ul>

                            {/* Contenido de las pestañas */}
                            <div className="tab-content mt-3">
                                {/* Gráfica de niveles de glucosa */}
                                <div id="glucose" className="tab-pane active">
                                    <div style={{ height: "400px" }}>
                                        <ResponsiveContainer width="100%" height="100%">
                                            <LineChart data={processedData}>
                                                <CartesianGrid strokeDasharray="3 3" />
                                                <XAxis dataKey="day" />
                                                <YAxis />
                                                <Tooltip />
                                                <Legend />
                                                <Line type="monotone" dataKey="breakfastGlucose1" stroke="#8884d8" name="Desayuno Pre" />
                                                <Line type="monotone" dataKey="breakfastGlucose2" stroke="#82ca9d" name="Desayuno Post" />
                                                <Line type="monotone" dataKey="lunchGlucose1" stroke="#ffc658" name="Comida Pre" />
                                                <Line type="monotone" dataKey="lunchGlucose2" stroke="#ff7300" name="Comida Post" />
                                            </LineChart>
                                        </ResponsiveContainer>
                                    </div>
                                </div>
                                
                                {/* Gráfica de insulina */}
                                <div id="insulin" className="tab-pane">
                                    <div style={{ height: "400px" }}>
                                        <ResponsiveContainer width="100%" height="100%">
                                            <LineChart data={processedData}>
                                                <CartesianGrid strokeDasharray="3 3" />
                                                <XAxis dataKey="day" />
                                                <YAxis />
                                                <Tooltip />
                                                <Legend />
                                                <Line type="monotone" dataKey="breakfastInsulin" stroke="#8884d8" name="Insulina Desayuno" />
                                                <Line type="monotone" dataKey="lunchInsulin" stroke="#82ca9d" name="Insulina Comida" />
                                                <Line type="monotone" dataKey="dinnerInsulin" stroke="#ffc658" name="Insulina Cena" />
                                                <Line type="monotone" dataKey="longActingInsulin" stroke="#ff7300" name="Insulina Lenta" strokeDasharray="5 5" />
                                            </LineChart>
                                        </ResponsiveContainer>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            );
        };

        ReactDOM.render(<DiabetesCharts />, document.getElementById('root'));
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>