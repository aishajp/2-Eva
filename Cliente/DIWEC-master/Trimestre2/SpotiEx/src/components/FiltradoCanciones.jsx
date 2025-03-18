import { useState } from 'react'
import $ from 'jquery'
import { ListadoCanciones } from './ListadoCanciones'

export function FiltradoCanciones({canciones}){
    const [artista, setArtista] = useState('');
    const search = (e)=>{
        setArtista(e.target.value);
        canciones.map(
            (s)=>{
                if(s.track_artist.toLowerCase().includes(artista.toLowerCase())){
                    $('#'+s.track_id).removeClass('d-none');
                }else{
                    $('#'+s.track_id).addClass('d-none');
                }
            }
        )
    }
    return (
        <>
            <form>
                <label htmlFor="search">Buscar por Artista</label>
                <input type="search" name="search" id="search" onChange={search}/>
            </form>
            <ListadoCanciones canciones={canciones}/>
        </>
    )
}