import axios from 'axios';

// URL base del servidor PHP
const API_URL = 'http://localhost/api';

// Creación del cliente axios
const apiClient = axios.create({
  baseURL: API_URL,
  headers: {
    'Content-Type': 'application/json',
  },
});

// Servicios para usuarios
export const userService = {
  // Obtener todos los usuarios
  getAll: async () => {
    try {
      const response = await apiClient.get('/users.php');
      return response.data;
    } catch (error) {
      console.error('Error al obtener usuarios:', error);
      throw error;
    }
  },

  // Obtener un usuario por su nombre de usuario
  getByUsername: async (username) => {
    try {
      const response = await apiClient.get(`/users.php?username=${username}`);
      return response.data;
    } catch (error) {
      console.error(`Error al obtener el usuario ${username}:`, error);
      throw error;
    }
  },

  // Crear un nuevo usuario
  create: async (userData) => {
    try {
      const response = await apiClient.post('/users.php', userData);
      return response.data;
    } catch (error) {
      console.error('Error al crear usuario:', error);
      throw error;
    }
  },

  // Actualizar un usuario existente
  update: async (username, userData) => {
    try {
      const response = await apiClient.put(`/users.php?username=${username}`, userData);
      return response.data;
    } catch (error) {
      console.error(`Error al actualizar el usuario ${username}:`, error);
      throw error;
    }
  },

  // Eliminar un usuario
  delete: async (username) => {
    try {
      const response = await apiClient.delete(`/users.php?username=${username}`);
      return response.data;
    } catch (error) {
      console.error(`Error al eliminar el usuario ${username}:`, error);
      throw error;
    }
  },
};

// Servicios para estadísticas
export const statsService = {
  // Obtener estadísticas mensuales para un usuario
  getMonthlyStats: async (username, month, year) => {
    try {
      const response = await apiClient.get(`/stats.php?username=${username}&month=${month}&year=${year}`);
      return response.data;
    } catch (error) {
      console.error(`Error al obtener estadísticas para ${username}:`, error);
      throw error;
    }
  },
};