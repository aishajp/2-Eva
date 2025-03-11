import React, { useState, useEffect } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import { userService } from '../../services/api';
import { useValidation } from '../../context/ValidationContext';

function UserEdit() {
  const { username } = useParams();
  const navigate = useNavigate();
  const validation = useValidation();
  
  // Estado del formulario
  const [formData, setFormData] = useState({
    fullName: '',
    birthDate: '',
    password: ''
  });
  
  const [errors, setErrors] = useState({});
  const [loading, setLoading] = useState(true);
  const [submitting, setSubmitting] = useState(false);

  // Cargar datos del usuario al montar el componente
  useEffect(() => {
    const fetchUser = async () => {
      try {
        setLoading(true);
        const userData = await userService.getByUsername(username);
        setFormData({
          fullName: userData.fullName,
          birthDate: userData.birthDate,
          password: '' // No cargar la contraseña por seguridad
        });
      } catch (error) {
        console.error('Error al cargar el usuario:', error);
        alert('Error al cargar los datos del usuario');
        navigate('/');
      } finally {
        setLoading(false);
      }
    };

    fetchUser();
  }, [username, navigate]);

  // Manejar cambios en los campos del formulario
  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData(prev => ({
      ...prev,
      [name]: value
    }));
    
    // Limpiar error específico al editar
    if (errors[name]) {
      setErrors(prev => ({
        ...prev,
        [name]: null
      }));
    }
  };

  // Validar el formulario
  const validateForm = () => {
    const newErrors = {};
    
    // Solo validar la contraseña si se ha introducido alguna
    if (formData.password && !validation.passwordRegex.test(formData.password)) {
      newErrors.password = 'La contraseña debe tener al menos 8 caracteres, una letra mayúscula y un número.';
    }
    
    // Validar nombre completo
    if (!formData.fullName.trim()) {
      newErrors.fullName = 'El nombre completo es obligatorio.';
    }
    
    // Validar fecha de nacimiento
    if (!formData.birthDate) {
      newErrors.birthDate = 'La fecha de nacimiento es obligatoria.';
    } else if (!validation.isAdult(formData.birthDate)) {
      newErrors.birthDate = 'El usuario debe ser mayor de edad (18 años o más).';
    }
    
    setErrors(newErrors);
    return Object.keys(newErrors).length === 0;
  };

  // Enviar el formulario
  const handleSubmit = async (e) => {
    e.preventDefault();
    
    if (validateForm()) {
      setSubmitting(true);
      
      try {
        // Si no se introduce una nueva contraseña, no enviarla
        const dataToUpdate = { ...formData };
        if (!dataToUpdate.password) {
          delete dataToUpdate.password;
        }
        
        await userService.update(username, dataToUpdate);
        alert('Usuario actualizado con éxito');
        navigate('/');
      } catch (error) {
        console.error('Error al actualizar usuario:', error);
        alert('Error al actualizar el usuario. Por favor, inténtelo de nuevo.');
      } finally {
        setSubmitting(false);
      }
    }
  };

  if (loading) return <div>Cargando datos del usuario...</div>;

  return (
    <div className="user-form-container">
      <h2>Editar Usuario: {username}</h2>
      <form onSubmit={handleSubmit} className="user-form">
        <div className="form-group">
          <label htmlFor="fullName">Nombre Completo*</label>
          <input
            type="text"
            id="fullName"
            name="fullName"
            value={formData.fullName}
            onChange={handleChange}
            className={errors.fullName ? 'error' : ''}
          />
          {errors.fullName && <div className="error-message">{errors.fullName}</div>}
        </div>
        
        <div className="form-group">
          <label htmlFor="birthDate">Fecha de Nacimiento*</label>
          <input
            type="date"
            id="birthDate"
            name="birthDate"
            value={formData.birthDate}
            onChange={handleChange}
            className={errors.birthDate ? 'error' : ''}
          />
          {errors.birthDate && <div className="error-message">{errors.birthDate}</div>}
        </div>
        
        <div className="form-group">
          <label htmlFor="password">Nueva Contraseña (dejar en blanco para mantener la actual)</label>
          <input
            type="password"
            id="password"
            name="password"
            value={formData.password}
            onChange={handleChange}
            className={errors.password ? 'error' : ''}
          />
          {errors.password && <div className="error-message">{errors.password}</div>}
        </div>
        
        <div className="form-actions">
          <button 
            type="button" 
            onClick={() => navigate('/')}
            className="btn btn-secondary"
          >
            Cancelar
          </button>
          <button 
            type="submit" 
            className="btn" 
            disabled={submitting}
          >
            {submitting ? 'Guardando...' : 'Actualizar Usuario'}
          </button>
        </div>
      </form>
    </div>
  );
}

export default UserEdit;