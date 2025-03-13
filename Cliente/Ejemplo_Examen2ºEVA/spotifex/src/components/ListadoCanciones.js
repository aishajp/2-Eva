// src/components/ListadoCanciones.jsx
import React from 'react';

function ListadoCanciones({ canciones }) {
  return (
    <div className="overflow-x-auto">
      <table className="min-w-full bg-white">
        <thead className="bg-gray-100">
          <tr>
            <th className="py-2 px-4 border-b text-left">ID</th>
            <th className="py-2 px-4 border-b text-left">Nombre</th>
            <th className="py-2 px-4 border-b text-left">Artista</th>
            <th className="py-2 px-4 border-b text-left">Álbum</th>
            <th className="py-2 px-4 border-b text-left">Duración (s)</th>
          </tr>
        </thead>
        <tbody>
  {canciones && canciones.map((cancion) => (
    <tr key={cancion.id} className="hover:bg-gray-50">
      <td className="py-2 px-4 border-b">{cancion.id}</td>
      <td className="py-2 px-4 border-b">{cancion.name}</td>
      <td className="py-2 px-4 border-b">{cancion.artist || 'Desconocido'}</td>
      <td className="py-2 px-4 border-b">{cancion.album || 'Desconocido'}</td>
      <td className="py-2 px-4 border-b">{cancion.duration_seconds}</td>
    </tr>
  ))}
  </tbody>
      </table>
    </div>
  );
}

export default ListadoCanciones;