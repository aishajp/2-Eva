import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import { userService } from '../../services/api';

function UserList() {
  const [users, setUsers] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  // Cargar usuarios al montar el componente
  useEffect(() => {
    fetchUsers();
  }, []);

  // Función para obtener los usuarios
  const fetchUsers = async () => {
    try {
      setLoading(true);
      const data = await userService.getAll();
      setUsers(data);
      setError(null);
    } catch (err) {
      setError('Error al cargar los usuarios. Por favor, inténtelo de nuevo.');
      console.error(err);
    } finally {
      setLoading(false);
    }
  };

  // Función para eliminar un usuario
  const handleDelete = async (username) => {
    if (window.confirm(`¿Está seguro de que desea eliminar al usuario ${username}?`)) {
      try {
        await userService.delete(username);
        // Actualizar la lista después de eliminar
        fetchUsers();
        alert('Usuario eliminado con éxito');
      } catch (err) {
        setError('Error al eliminar el usuario. Por favor, inténtelo de nuevo.');
        console.error(err);
      }
    }
  };

  if (loading) return <div>Cargando usuarios...</div>;
  if (error) return <div className="error">{error}</div>;

  return (
    <div className="user-list">
      <h2>Lista de Usuarios</h2>
      {users.length === 0 ? (
        <p>No hay usuarios registrados.</p>
      ) : (
        <table className="user-table">
          <thead>
            <tr>
              <th>Nombre de Usuario</th>
              <th>Nombre Completo</th>
              <th>Fecha de Nacimiento</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            {users.map((user) => (
              <tr key={user.username}>
                <td>{user.username}</td>
                <td>{user.fullName}</td>
                <td>{new Date(user.birthDate).toLocaleDateString()}</td>
                <td className="actions">
                  <Link to={`/users/edit/${user.username}`} className="edit-btn">Editar</Link>
                  <Link to={`/users/stats/${user.username}`} className="stats-btn">Estadísticas</Link>
                  <button 
                    onClick={() => handleDelete(user.username)} 
                    className="delete-btn"
                  >
                    Eliminar
                  </button>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      )}
      <div className="actions-container">
        <Link to="/users/new" className="btn">Añadir Nuevo Usuario</Link>
      </div>
    </div>
  );
}

export default UserList;