<?php
/* Conexión a la base de datos
$db_host = 'localhost:8080';
$db_user = 'root';
$db_pass = ' ';
$db_name = 'diabetesdb';

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);


if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

require_once 'auth.php';
verificarSesion();*/

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
import { Card, CardHeader, CardTitle, CardContent } from '@/components/ui/card';
import { Select } from '@/components/ui/select';
import { LineChart, Line, XAxis, YAxis, CartesianGrid, Tooltip, Legend, ResponsiveContainer } from 'recharts';

const DiabetesChart = () => {
  const [data, setData] = useState([]);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);
  const [timeRange, setTimeRange] = useState('month');
  const [selectedDate, setSelectedDate] = useState(new Date());

  const fetchData = async () => {
    try {
      setLoading(true);
      setError(null);
      const response = await fetch(`/api/diabetes-data?timeRange=${timeRange}&date=${selectedDate.toISOString()}`);
      if (!response.ok) throw new Error('Error al cargar datos');
      const result = await response.json();
      setData(result);
    } catch (err) {
      setError(err.message);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchData();
  }, [timeRange, selectedDate]);

  const getStatistics = () => {
    if (!data.length) return null;
    return {
      avgGlucose: data.reduce((acc, cur) => acc + cur.glucoseLevel, 0) / data.length,
      maxGlucose: Math.max(...data.map(d => d.glucoseLevel)),
      minGlucose: Math.min(...data.map(d => d.glucoseLevel)),
    };
  };

  const stats = getStatistics();

  return (
    <Card className="w-full max-w-4xl">
      <CardHeader>
        <CardTitle>Control de Diabetes</CardTitle>
        <div className="flex gap-4">
          <Select
            value={timeRange}
            onChange={(e) => setTimeRange(e.target.value)}
            className="w-32"
          >
            <option value="week">Semanal</option>
            <option value="month">Mensual</option>
            <option value="quarter">Trimestral</option>
          </Select>
        </div>
      </CardHeader>
      <CardContent>
        {loading && <div className="text-center p-4">Cargando datos...</div>}
        {error && <div className="text-red-500 p-4">Error: {error}</div>}
        {!loading && !error && (
          <>
            <div className="h-64">
              <ResponsiveContainer width="100%" height="100%">
                <LineChart data={data}>
                  <CartesianGrid strokeDasharray="3 3" />
                  <XAxis dataKey="date" />
                  <YAxis />
                  <Tooltip />
                  <Legend />
                  <Line 
                    type="monotone" 
                    dataKey="glucoseLevel" 
                    stroke="#8884d8" 
                    name="Nivel de Glucosa"
                  />
                  <Line 
                    type="monotone" 
                    dataKey="insulinDose" 
                    stroke="#82ca9d" 
                    name="Dosis Insulina"
                  />
                </LineChart>
              </ResponsiveContainer>
            </div>
            {stats && (
              <div className="grid grid-cols-3 gap-4 mt-4">
                <div className="p-4 bg-blue-50 rounded-lg">
                  <h4 className="text-sm font-medium">Promedio</h4>
                  <p className="text-2xl font-bold">{stats.avgGlucose.toFixed(1)}</p>
                </div>
                <div className="p-4 bg-green-50 rounded-lg">
                  <h4 className="text-sm font-medium">Mínimo</h4>
                  <p className="text-2xl font-bold">{stats.minGlucose}</p>
                </div>
                <div className="p-4 bg-red-50 rounded-lg">
                  <h4 className="text-sm font-medium">Máximo</h4>
                  <p className="text-2xl font-bold">{stats.maxGlucose}</p>
                </div>
              </div>
            )}
          </>
        )}
      </CardContent>
    </Card>
  );
};

export default DiabetesChart;
        </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>