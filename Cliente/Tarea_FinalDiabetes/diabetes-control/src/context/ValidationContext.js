import React, { createContext, useContext } from 'react';

// Crear el contexto
const ValidationContext = createContext(null);

// Expresiones regulares para validaciones
const validations = {
  // Username: mínimo 6 caracteres, solo letras minúsculas o números, debe comenzar por letra
  usernameRegex: /^[a-z][a-z0-9]{5,}$/,
  
  // Contraseña: mínimo 8 caracteres, al menos una mayúscula y un número
  passwordRegex: /^(?=.*[A-Z])(?=.*\d).{8,}$/,
  
  // Función para validar si es mayor de edad
  isAdult: (birthDate) => {
    const today = new Date();
    const birth = new Date(birthDate);
    const age = today.getFullYear() - birth.getFullYear();
    const monthDiff = today.getMonth() - birth.getMonth();
    
    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birth.getDate())) {
      return age - 1 >= 18;
    }
    
    return age >= 18;
  }
};

// Provider component
export const ValidationProvider = ({ children }) => {
  return (
    <ValidationContext.Provider value={validations}>
      {children}
    </ValidationContext.Provider>
  );
};

// Hook personalizado para usar el contexto
export const useValidation = () => {
  const context = useContext(ValidationContext);
  if (!context) {
    throw new Error('useValidation debe usarse dentro de un ValidationProvider');
  }
  return context;
};