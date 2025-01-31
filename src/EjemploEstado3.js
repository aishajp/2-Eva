import {useState} from "react";
const EjemploEstado3 = () => {
    const[estado, setEstado] = useState(
        {
          titulo:"Por defecto",
          hora: new Date().toLocaleTimeString(),
          numero: 0,
          nuemeros:[]
        }
    )
    const cambiarEstado = () => {
        let numero =  Math.round(Math.random()*4);
        let numeros = estado.numeros;
        numeros.push(numero);
        setEstado({
            hora: new Date().toLocaleTimeString(),
            numeros: numeros,
            numero: numero,
            titulo: numero%2===0?"Par":"Impar"
        })
        console.log("cambiar estado: " , estado);
    };
    const colores = ["red", "yellow", "green", "blue", "orange"];
    return(
        <>
            <div style={{ backgroundColor: colores[estado.numero]}}>
                <header>
                    <h1>{estado.titulo}</h1>
                    <h2>Hora: {estado.hora}</h2>
                    <h3>Número aleatorio: {estado.numero}</h3>
                    <button onClick={cambiarEstado}>Cambiar estado</button>
                    <ul>
                        {estado.numeros.map((n) => (
                            <li key={n}>{n}</li>
                            ))}
                            </ul>
                            </header>
                            </div>
                            </>
                            )
                        }
                        export default EjemploEstado3;