// src/components/FormNota.js
import React, { useState } from 'react';

const FormUsu = ({ onSubmit }) => {
  const [usu, setUsu] = useState('');

  const handleSubmit = (e) => {
    e.preventDefault();
    onSubmit(usu);
    setUsu('');
  };

  return (
    <form onSubmit={handleSubmit}>
      <textarea 
        value={usu} 
        onChange={(e) => setUsu(e.target.value)} 
        placeholder="Escribe un usuario..." 
      />
      <button type="submit">Guardar</button>
    </form>
  );
};

export default FormUsu;