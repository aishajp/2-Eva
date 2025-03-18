import './visor.css';
import {FontAwesomeIcon} from '@fortawesome/react-fontawesome';
import {faPlay,faForward,faBackward} from '@fortawesome/free-solid-svg-icons';
const Visor=(props)=>{
    let indiceActual =0;
    const img=props.img;
    const mostrarImagen=()=>{
        const path='/src/' + props.img[indiceActual];
        const visorImagenes= document.getElementById('visorImagenes');
        if (visorImagenes){visorImagenes.src=path;}
    };
    const avanzar=()=>{
        if(indiceActual<img.length-1){indiceActual++}
        else{indiceActual=0}
        mostrarImagen();
    };
    const retroceder=()=>{
        if(indiceActual>0){indiceActual--;}
        else{indiceActual=img.length-1}
        mostrarImagen();
    };
    const primera=()=>{
        indiceActual=0;
        mostrarImagen();
    }
    const ultima=()=>{
        indiceActual=img.length-1;
        mostrarImagen();
    }
    setTimeout(()=>{mostrarImagen();},0);
    return(
        <div className="Visor">
            <div>
                <img id="visorImagenes" className='img'></img>
                <br/>
            </div>
            <div className='btns'>
                <button onClick={avanzar}><FontAwesomeIcon icon={faPlay}/></button>
                <button onClick={ultima}><FontAwesomeIcon icon={faForward}/></button>
                <button onClick={retroceder}><FontAwesomeIcon icon={faPlay} className='flip-horizontal'/></button>
                <button onClick={primera}><FontAwesomeIcon icon={faBackward}/></button>
            </div>
        </div>
    )
}
export default Visor;