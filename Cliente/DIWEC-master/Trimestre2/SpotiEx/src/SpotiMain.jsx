import { useState, useEffect } from 'react'
import {FiltradoCanciones} from './components/FiltradoCanciones'
import {MasPopular}from './components/MasPopular'
import './App.css'

function SpotiMain() {
  const [canciones, setCanciones] = useState([]);
  const obtenerCanciones = async ()=>{
    const response = await fetch('/json/Spotify.json');;
    const data = await response.json();
    setCanciones(data);
  };
  useEffect(()=>{
    obtenerCanciones();
  }, [])
  return (
    <>
    <div className="row">
      <div className="col-6"><h1>Canciones de Spotify</h1></div>
      <div className="row mt-5">
        <FiltradoCanciones canciones={canciones}/>
      </div>
      <div className="col"><MasPopular canciones={canciones}/></div>
    </div>
    </>
  )
}

export default SpotiMain