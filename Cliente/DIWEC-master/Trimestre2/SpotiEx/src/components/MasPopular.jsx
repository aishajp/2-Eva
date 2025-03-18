export function MasPopular({canciones}){
    if(canciones.lenght<=0){return <p>No hay canciones</p>}
    const maxPopular = canciones.reduce((max, cancion) => {
      return max.track_popularity > cancion.track_popularity ? max : cancion;
    },canciones[0]);
    return(
        <>
            <h2>Cancion mas popular</h2>
            <table>
            <th>Id</th>
            <th>Nombre</th>
            <th>Artista</th>
            <th>Album</th>
            <th>Fecha</th>
            <th>Duracion</th>
            <tbody>
                {maxPopular?<tr><td>{maxPopular.track_id}</td>
                <td>{maxPopular.track_name}</td>
                <td>{maxPopular.track_artist}</td>
                <td>{maxPopular.track_album_name}</td>
                <td>{maxPopular.track_album_release_date}</td>
                <td>{maxPopular.duration_ms/1000}</td></tr>:<tr></tr>}
            </tbody>
        </table>
        {}
        </>
    )
}