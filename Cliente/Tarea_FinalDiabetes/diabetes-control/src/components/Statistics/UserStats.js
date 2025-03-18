import React, { useState, useEffect } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import { statsService } from '../../services/api';
import { Line } from 'recharts';
import { PieChart, Pie, Cell, LineChart, XAxis, YAxis, CartesianGrid, Tooltip, Legend, ResponsiveContainer } from 'recharts';

function UserStats() {
  const { username } = useParams();
  const navigate = useNavigate();
  const [statsData, setStatsData] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [filter, setFilter] = useState({
    month: new Date().getMonth() + 1, // Mes actual (1-12)
    year: new Date().getFullYear() // Año actual
  });

  // Colores para gráficos
  const COLORS = ['#2ecc71', '#e74c3c', '#3498db'];

  // Cargar estadísticas al montar el componente o cambiar el filtro
  useEffect(() => {
    fetchStats();
  }, [filter.month, filter.year]);

  // Función para obtener las estadísticas
  const fetchStats = async () => {
    try {
      setLoading(true);
      const data = await statsService.getMonthlyStats(username, filter.month, filter.year);
      setStatsData(data);
      setError(null);
    } catch (err) {
      setError('Error al cargar las estadísticas. Por favor, inténtelo de nuevo.');
      console.error(err);
    } finally {
      setLoading(false);
    }
  };

  // Manejar cambios en los filtros
  const handleFilterChange = (e) => {
    const { name, value } = e.target;
    setFilter(prev => ({
      ...prev,
      [name]: parseInt(value)
    }));
  };

  // Preparar datos para el gráfico de línea
  const prepareLineChartData = () => {
    if (!statsData || !statsData.readings) return [];
    
    // Agrupar por fecha
    const dataByDate = {};
    statsData.readings.forEach(reading => {
      const date = reading.reading_date;
      if (!dataByDate[date]) {
        dataByDate[date] = {
          date,
          readings: []
        };
      }
      dataByDate[date].readings.push({
        time: reading.reading_time,
        level: parseFloat(reading.glucose_level)
      });
    });
    
    // Convertir a array y ordenar por fecha
    return Object.values(dataByDate)
      .map(item => ({
        date: item.date,
        promedio: item.readings.reduce((sum, r) => sum + r.level, 0) / item.readings.length,
        min: Math.min(...item.readings.map(r => r.level)),
        max: Math.max(...item.readings.map(r => r.level))
      }))
      .sort((a, b) => new Date(a.date) - new Date(b.date));
  };

  // Preparar datos para el gráfico circular
  const preparePieChartData = () => {
    if (!statsData || !statsData.statistics) return [];
    
    const stats = statsData.statistics;
    return [
      { name: 'En rango', value: stats.in_range_count },
      { name: 'Elevado', value: stats.high_count },
      { name: 'Bajo', value: stats.low_count }
    ].filter(item => item.value > 0); // Filtrar elementos con valor 0
  };

  // Opciones de meses para el filtro
  const months = [
    { value: 1, label: 'Enero' },
    { value: 2, label: 'Febrero' },
    { value: 3, label: 'Marzo' },
    { value: 4, label: 'Abril' },
    { value: 5, label: 'Mayo' },
    { value: 6, label: 'Junio' },
    { value: 7, label: 'Julio' },
    { value: 8, label: 'Agosto' },
    { value: 9, label: 'Septiembre' },
    { value: 10, label: 'Octubre' },
    { value: 11, label: 'Noviembre' },
    { value: 12, label: 'Diciembre' }
  ];

  // Generar años para el filtro (últimos 5 años)
  const currentYear = new Date().getFullYear();
  const years = Array.from({ length: 5 }, (_, i) => currentYear - i);

  if (loading) return <div>Cargando estadísticas...</div>;
  if (error) return <div className="error">{error}</div>;

  // Si no hay datos
  if (!statsData || !statsData.readings || statsData.readings.length === 0) {
    return (
      <div className="user-stats">
        <h2>Estadísticas de {username}</h2>
        <div className="filters">
          <div className="form-group">
            <label htmlFor="month">Mes:</label>
            <select
              id="month"
              name="month"
              value={filter.month}
              onChange={handleFilterChange}
            >
              {months.map(month => (
                <option key={month.value} value={month.value}>{month.label}</option>
              ))}
            </select>
          </div>
          <div className="form-group">
            <label htmlFor="year">Año:</label>
            <select
              id="year"
              name="year"
              value={filter.year}
              onChange={handleFilterChange}
            >
              {years.map(year => (
                <option key={year} value={year}>{year}</option>
              ))}
            </select>
          </div>
        </div>
        <div>No hay lecturas de glucosa para el período seleccionado.</div>
        <button className="btn" onClick={() => navigate('/')}>Volver a la lista</button>
      </div>
    );
  }

  const lineChartData = prepareLineChartData();
  const pieChartData = preparePieChartData();
  const stats = statsData.statistics;

  return (
    <div className="user-stats">
      <h2>Estadísticas de {username}</h2>
      <div className="filters">
        <div className="form-group">
          <label htmlFor="month">Mes:</label>
          <select
            id="month"
            name="month"
            value={filter.month}
            onChange={handleFilterChange}
          >
            {months.map(month => (
              <option key={month.value} value={month.value}>{month.label}</option>
            ))}
          </select>
        </div>
        <div className="form-group">
          <label htmlFor="year">Año:</label>
          <select
            id="year"
            name="year"
            value={filter.year}
            onChange={handleFilterChange}
          >
            {years.map(year => (
              <option key={year} value={year}>{year}</option>
            ))}
          </select>
        </div>
      </div>

      <div className="metrics">
        <div className="metric-card">
          <h3>Promedio</h3>
          <div className="metric-value">{stats.average} mg/dL</div>
        </div>
        <div className="metric-card">
          <h3>Lecturas</h3>
          <div className="metric-value">{stats.count}</div>
        </div>
        <div className="metric-card">
          <h3>En rango</h3>
          <div className="metric-value">{stats.in_range_percentage}%</div>
        </div>
      </div>

      <div className="chart-container">
        <h3>Tendencia de glucosa</h3>
        <ResponsiveContainer width="100%" height={300}>
          <LineChart data={lineChartData} margin={{ top: 5, right: 30, left: 20, bottom: 5 }}>
            <CartesianGrid strokeDasharray="3 3" />
            <XAxis dataKey="date" />
            <YAxis domain={['auto', 'auto']} />
            <Tooltip />
            <Legend />
            <Line type="monotone" dataKey="promedio" stroke="#8884d8" name="Promedio" strokeWidth={2} />
            <Line type="monotone" dataKey="min" stroke="#82ca9d" name="Mínimo" strokeWidth={2} />
            <Line type="monotone" dataKey="max" stroke="#ff7300" name="Máximo" strokeWidth={2} />
          </LineChart>
        </ResponsiveContainer>
      </div>

      <div className="chart-container">
        <h3>Distribución de lecturas</h3>
        <ResponsiveContainer width="100%" height={300}>
          <PieChart>
            <Pie
              data={pieChartData}
              cx="50%"
              cy="50%"
              labelLine={true}
              outerRadius={80}
              fill="#8884d8"
              dataKey="value"
              label={({ name, percent }) => `${name}: ${(percent * 100).toFixed(0)}%`}
            >
              {pieChartData.map((entry, index) => (
                <Cell key={`cell-${index}`} fill={COLORS[index % COLORS.length]} />
              ))}
            </Pie>
            <Tooltip formatter={(value) => [value, 'Lecturas']} />
            <Legend />
          </PieChart>
        </ResponsiveContainer>
      </div>

      <h3>Detalles de lecturas</h3>
      <table className="user-table">
        <thead>
          <tr>
            <th>Fecha</th>
            <th>Hora</th>
            <th>Nivel (mg/dL)</th>
            <th>Notas</th>
          </tr>
        </thead>
        <tbody>
          {statsData.readings.map((reading) => (
            <tr key={reading.reading_id}>
              <td>{reading.reading_date}</td>
              <td>{reading.reading_time}</td>
              <td>{reading.glucose_level}</td>
              <td>{reading.notes}</td>
            </tr>
          ))}
        </tbody>
      </table>

      <button className="btn" onClick={() => navigate('/')}>Volver a la lista</button>
    </div>
  );
}