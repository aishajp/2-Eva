// src/components/FiltradoCanciones.jsx
import React, { useState, useEffect } from 'react';

function FiltradoCanciones({ canciones }) {
  const [filtroArtista, setFiltroArtista] = useState('');
  const [cancionesFiltradas, setCancionesFiltradas] = useState([]);
  
  useEffect(() => {
    if (filtroArtista && canciones && canciones.length > 0) {
      const filtradas = canciones.filter(cancion => 
        cancion && cancion.artist && 
        cancion.artist.toLowerCase().includes(filtroArtista.toLowerCase())
      );
      setCancionesFiltradas(filtradas);
    } else {
      setCancionesFiltradas([]);
    }
  }, [filtroArtista, canciones]);
  
  return (
    <div>
      <input
        type="text"
        placeholder="Nombre del artista"
        className="w-full p-2 border rounded mb-4"
        value={filtroArtista}
        onChange={(e) => setFiltroArtista(e.target.value)}
      />
      
      {cancionesFiltradas.length > 0 ? (
        <div className="border rounded p-4">
          <h3 className="font-medium mb-2">Canciones de {filtroArtista}</h3>
          <ul className="list-disc pl-5">
            {cancionesFiltradas.map(cancion => (
              <li key={cancion.id}>{cancion.name}</li>
            ))}
          </ul>
        </div>
      ) : filtroArtista ? (
        <p>No se encontraron canciones para este artista</p>
      ) : null}
    </div>
  );
}

export default FiltradoCanciones;