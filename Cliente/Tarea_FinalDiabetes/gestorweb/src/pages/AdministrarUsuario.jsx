import { useContext, useState } from "react";
import {useNavigate} from 'react-router-dom';
import {expresionesContext} from '../functions/RegularExpressions';
import { TablaUsuarios } from "./component/Tabla";

export default function AdministrarUsuario(){
    const navigate = useNavigate();
    const [username1, setUsername1] = useState("");
    const [logun,setLogUn]= useState([]);
    const [error1, setError1] = useState(false);
    const [log,setLog]= useState();

    const{expressions}=useContext(expresionesContext);

    const handleSubmit=(e)=>{
        let value=e.target.value;
            setUsername1(value)
            let hasError1 = false
            let logMessages1 = []
            if(!expressions.username13.test(value)){
                hasError1 = true;
                logMessages1.push('Debe tener mínimo 6 caracteres');
            }
            setError1(hasError1)
            setLogUn(logMessages1)
    }
    const buscarUsuario = ()=>{
        if(username1!=""){
            const headers = {
                Accept: "application/json",
                "Content-Type": "application/json",
            }
            const data = {username1}
            fetch("http://localhost:8085/verUsuario.php", {
                method: "POST",
                headers: headers,
                body: JSON.stringify(data),
            })
           .then((response)=>{
               if(!response.ok){
                   throw new Error("Fallo en la conexion");
                }
                return response.json()
            })
            .then((data)=>{
                TablaUsuarios(data)
            })
            .catch((data) => {
                console.error('Error:', data)
            });
        }else{
            setLog('No se pudo administrar este usuario')
        }
    }

    return (
        <div className="row form text-center text-white">
            <div className="row m-auto my-5 w-75">
                <div className="mb-3 row col-6 mx-auto">
                    <div className="col-6 p-0">
                        <label htmlFor="username1" className="form-label fs-5">Nombre de Usuario</label>
                        <input type="text" className="form-control fs-6" name="username1" id="username1" aria-describedby="username1Help" value={username1} onChange={(e)=>handleSubmit(e)} required autoComplete="off"/>
                        <small id="username1Help" className="form-text text-info fs-6">
                            {
                                error1?
                                logun.map((log1,index)=>(
                                    <li key={index}>{log1}</li>
                                )):
                                ''
                            }
                        </small>
                    </div>
                    <div className="align-self-end col p-0">
                        <button type="button" className="btn btn-info" onClick={buscarUsuario}>Buscar Usuario</button>
                    </div>
                </div>
            </div>
            <div id="mostrarUsuario" className="d-flex justify-content-center fs-5">        
            </div>
        </div>
    );
}