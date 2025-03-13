// src/components/SpotiMain.jsx
import React, { useState, useEffect } from 'react';
import ListadoCanciones from './ListadoCanciones';
import FiltradoCanciones from './FiltradoCanciones';
import MasPopular from './MasPopular';

function SpotiMain() {
  const [canciones, setCanciones] = useState([]);
  
  useEffect(() => {
    obtenerCanciones();
  }, []);
  
  // Función asíncrona para obtener canciones (punto 2)
  const obtenerCanciones = async () => {
    try {
      const respuesta = await fetch('/json/Spotify.json');
      const datos = await respuesta.json();
      
      // Transformar los datos para que coincidan con los nombres de propiedades que usamos
      const cancionesFormateadas = datos.map(cancion => ({
        id: cancion.track_id,
        name: cancion.track_name,
        artist: cancion.track_artist,
        album: cancion.track_album_name,
        popularity: cancion.track_popularity,
        duration_seconds: Math.round(cancion.duration_ms / 1000) // Convertir ms a segundos
      }));
      
      console.log("Datos formateados:", cancionesFormateadas);
      setCanciones(cancionesFormateadas);
    } catch (error) {
      console.error("Error al cargar los datos:", error);
    }
  };

  return (
    <div className="container mx-auto p-4">
      <h1 className="text-3xl font-bold mb-6">SpotifEx</h1>
      
      <div className="mb-8">
        <h2 className="text-xl font-semibold mb-4">Canción Más Popular</h2>
        <MasPopular canciones={canciones} />
      </div>
      
      <div className="mb-8">
        <h2 className="text-xl font-semibold mb-4">Filtrar por Artista</h2>
        <FiltradoCanciones canciones={canciones} />
      </div>
      
      <div>
        <h2 className="text-xl font-semibold mb-4">Listado de Canciones</h2>
        <ListadoCanciones canciones={canciones} />
      </div>
    </div>
  );
}

export default SpotiMain;