import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { userService } from '../../services/api';
import { useValidation } from '../../context/ValidationContext';

function UserForm() {
  const navigate = useNavigate();
  const validation = useValidation(); // Acceso a las validaciones del contexto
  
  // Estado inicial del formulario
  const [formData, setFormData] = useState({
    username: '',
    password: '',
    fullName: '',
    birthDate: ''
  });
  
  // Estado para los errores de validación
  const [errors, setErrors] = useState({});
  const [submitting, setSubmitting] = useState(false);

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
    
    // Validar nombre de usuario
    if (!validation.usernameRegex.test(formData.username)) {
      newErrors.username = 'El nombre de usuario debe tener al menos 6 caracteres, empezar por una letra minúscula y contener solo letras minúsculas o números.';
    }
    
    // Validar contraseña
    if (!validation.passwordRegex.test(formData.password)) {
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
        await userService.create(formData);
        alert('Usuario creado con éxito');
        navigate('/'); // Redirigir a la lista de usuarios
      } catch (error) {
        console.error('Error al crear usuario:', error);
        
        // Comprobar si el error es porque el usuario ya existe
        if (error.response && error.response.status === 409) {
          setErrors(prev => ({
            ...prev,
            username: 'Este nombre de usuario ya existe. Por favor, elija otro.'
          }));
        } else {
          alert('Error al crear el usuario. Por favor, inténtelo de nuevo.');
        }
      } finally {
        setSubmitting(false);
      }
    }
  };

  return (
    <div className="user-form-container">
      <h2>Crear Nuevo Usuario</h2>
      <form onSubmit={handleSubmit} className="user-form">
        <div className="form-group">
          <label htmlFor="username">Nombre de Usuario*</label>
          <input
            type="text"
            id="username"
            name="username"
            value={formData.username}
            onChange={handleChange}
            className={errors.username ? 'error' : ''}
          />
          {errors.username && <div className="error-message">{errors.username}</div>}
        </div>
        
        <div className="form-group">
          <label htmlFor="password">Contraseña*</label>
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
            {submitting ? 'Guardando...' : 'Guardar Usuario'}
          </button>
        </div>
      </form>
    </div>
  );
}

export default UserForm;