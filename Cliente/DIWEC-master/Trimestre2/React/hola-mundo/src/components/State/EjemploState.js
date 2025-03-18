import {useState} from "react";
const EjemploEstado=()=>{
    const [contador,setContador] = useState(0);
    return (
        <>
            <h1>Contador: {contador}</h1>
            <button onClick={()=>setContador(contador+1)}>Incrementar</button>
            <button onClick={()=>setContador(contador-1)}>Decrementar</button>
        </>
    )
};
export default EjemploEstado;