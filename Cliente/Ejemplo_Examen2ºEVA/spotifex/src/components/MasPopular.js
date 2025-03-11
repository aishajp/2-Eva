// src/components/MasPopular.jsx
import React, { useState, useEffect } from 'react';

function MasPopular({ canciones }) {
  const [cancionMasPopular, setCancionMasPopular] = useState(null);
  
  useEffect(() => {
    if (canciones && canciones.length > 0) {
      // Verificar que los objetos canción tengan la propiedad popularity
      const cancionesConPopularidad = canciones.filter(cancion => 
        cancion && typeof cancion.popularity !== 'undefined'
      );
      
      if (cancionesConPopularidad.length > 0) {
        const masPopular = cancionesConPopularidad.reduce((max, cancion) => 
          (cancion.popularity > max.popularity) ? cancion : max
        , cancionesConPopularidad[0]);
        
        setCancionMasPopular(masPopular);
      }
    }
  }, [canciones]);
  
  if (!cancionMasPopular) {
    return <p>Cargando canción más popular...</p>;
  }
  
  return (
    <div className="border rounded p-4 bg-gray-50">
      <h3 className="font-medium mb-2">Canción más popular</h3>
      <p><strong>Título:</strong> {cancionMasPopular.name}</p>
      <p><strong>Artista:</strong> {cancionMasPopular.artist}</p>
      <p><strong>Álbum:</strong> {cancionMasPopular.album}</p>
      <p><strong>Popularidad:</strong> {cancionMasPopular.popularity}</p>
    </div>
  );
}

export default MasPopular;