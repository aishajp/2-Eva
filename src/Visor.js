import 'Visor.css'
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import{faPlay, faForward, faBackward} from '@fortawesome/free-solid-svg-icons';

const Visor = (props)=>{
    let indiceActual = 0;
    const imagenes = props.imagenes;
    const mostrarImagen=()=>{
        const path = "/imagenes/"+props.imagenes[indiceActual];
        const visorImagenes = document.getElementById('visorImagenes');
        if(visorImagenes){
            visorImagenes.src = path;
        }
    }
    const siguiente=()=>{
        indiceActual++;
        if(indiceActual===imagenes.length){
            indiceActual = 0;
            mostrarImagen();
        }
    }
    const anterior=()=>{
        indiceActual--;
        if(indiceActual === -1){
            indiceActual = imagenes.length-1;
            mostrarImagen();
        }
    }
    const primera = () => {
        indiceActual = 0;
        mostrarImagen();}
        
        const ultima = () => {
        indiceActual = imagenes.length-1;
        mostrarImagen();}
        
        setTimeout(() => {mostrarImagen();}, 0);
        return (
            <div className="visor">
            <img id="visorImagenes" className="imagen" />
            <br />
            <div className="botones">
            <button onClick={siguiente}><FontAwesomeIcon icon={faPlay} /></button>
            <button onClick={ultima}><FontAwesomeIcon icon={faForward} /></button>
            <button onClick={anterior}><FontAwesomeIcon icon={faPlay} className="flip-horizontal" /></button>
            <button onClick={primera}><FontAwesomeIcon icon={faBackward} /></button>
            </div>
            </div>
            );
            }
            export default Visor;