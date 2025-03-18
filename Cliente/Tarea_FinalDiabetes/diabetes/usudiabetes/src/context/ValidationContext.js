// src/context/ValidationContext.js
import React, { createContext, useState } from 'react';

export const ValidationContext = createContext();

export const ValidationProvider = ({ children }) => {
  const [errors, setErrors] = useState({});

  const validateForm = (form) => {
    // Implementa tu lógica de validación aquí
    // ...
    return true;
  };

  return (
    <ValidationContext.Provider value={{ errors, validateForm }}>
      {children}
    </ValidationContext.Provider>
  );
};