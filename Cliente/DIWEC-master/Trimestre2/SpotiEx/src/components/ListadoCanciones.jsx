export function ListadoCanciones({canciones}) {
return(
    <>
        <table>
            <th>Id</th>
            <th>Nombre</th>
            <th>Artista</th>
            <th>Album</th>
            <th>Fecha</th>
            <th>Duracion</th>
            <tbody>
            {canciones.map(cancion => (
            <tr key={cancion.track_id} id={cancion.track_id}>
                <td>{cancion.track_id}</td>
                <td>{cancion.track_name}</td>
                <td>{cancion.track_artist}</td>
                <td>{cancion.track_album_name}</td>
                <td>{cancion.track_album_release_date}</td>
                <td>{cancion.duration_ms/1000}</td>
            </tr>))}
            </tbody>
        </table>
    </>
)}