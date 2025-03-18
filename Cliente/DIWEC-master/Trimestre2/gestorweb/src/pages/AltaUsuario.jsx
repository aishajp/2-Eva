import { useContext, useState } from "react";
import {useNavigate} from 'react-router-dom';
import {expresionesContext} from '../functions/RegularExpressions';

export default function AltaUsuario({closeModal}){
    const navigate = useNavigate();
    const [nombre, setNombre] = useState("");
    const [username, setUsername] = useState("");
    const [password, setPassword] = useState("");
    const [apellido, setApellido] = useState("");
    const [fechaNac, setFechaNac] = useState("");
    const [logun,setLogUn]= useState([]);
    const [logpw,setLogPw]= useState([]);
    const [logf,setLogF]= useState([]);
    const [error1, setError1] = useState(false);
    const [error2, setError2] = useState(false);
    const [error3, setError3] = useState(false);

    const{expressions}=useContext(expresionesContext);

    const handleEdad=(e)=>{
        var hoy = new Date();
        var edad= new Date(e.target.value);
        edad = hoy.getFullYear() - edad.getFullYear();
        if(edad >= 18){
            setFechaNac(e.target.value);
        }else{
            setError3(true);
            setLogF('Debe ser mayor de edad ( 18 )')
        }
    }
    const handleSubmit=(e,type)=>{
        let value=e.target.value;
        switch(type){
            case 'nombre':
                setNombre(value);
            break;
            case 'apellido':
                setApellido(value);
            break;
            case 'username':
                setUsername(value)
                let hasError1 = false
                let logMessages1 = []
                if(!expressions.username1.test(value)){
                    hasError1 = true;
                    logMessages1.push('Debe empezar por una letra')
                }if(!expressions.username2.test(value)){
                    hasError1 = true;
                    logMessages1.push('Debe tener un número');
                }if(!expressions.username3.test(value)){
                    hasError1 = true;
                    logMessages1.push('Debe tener mínimo 6 caracteres');
                }
                setError1(hasError1)
                setLogUn(logMessages1)
            break;
            case 'password':
                setPassword(value)
                let hasError2 = false
                let logMessages2 = []
                if(!expressions.password1.test(value)){
                    hasError2 = true;
                    logMessages2.push('Debe tener una minuscula');
                }if(!expressions.password2.test(value)){
                    hasError2 = true;
                    logMessages2.push('Debe tener una mayuscula');
                }if(!expressions.password3.test(value)){
                    hasError2 = true;
                    logMessages2.push('Debe tener un digito');
                }if(!expressions.password4.test(value)){
                    hasError2 = true;
                    logMessages2.push('Debe tener minimo 8 caracteres');
                }
                setError2(hasError2)
                setLogPw(logMessages2)
            break;
        }
    }
    const insertSubmit = ()=>{
        if(nombre!=="" && apellido!=="" && username!="" && password!="" && fechaNac!==""){
            const headers = {
                Accept: "application/json",
                "Content-Type": "application/json",
            }
            const data = {nombre,apellido,username,password,fechaNac}

            fetch("http://localhost:8085/altaUsuario.php", {
                method: "POST",
                headers: headers,
                body: JSON.stringify(data),
            })
           .then((response)=>{
                if(!response.ok){
                    throw new Error("Fallo en la conexion");
                }
                closeModal();
                return response.json()
           })
           .then( navigate('../'))
           .catch((data) => {
                console.error('Error:', data)
                if(data.result){
                    setLog(data.result)
                }
            });
        }else{
            setLog('Faltan Datos')
        }
    }

    return (
        <div className="row form text-center text-white p-auto">
            <div className="row-1 col-12 mt-5">
                <h2 className="fs-1">Alta de Usuario</h2>
            </div>
            <div className="row m-auto my-5 w-75">
                <div className="mb-3 col-6">
                    <label htmlFor="nombre" className="form-label fs-3 fs-3">Nombre</label>
                    <input type="text" className="form-control fs-6" name="nombre" id="nombre" value={nombre} onChange={(e)=>handleSubmit(e,'nombre')} required autoComplete="off"/>
                </div>
                <div className="mb-3 col-6">
                    <label htmlFor="apellido" className="form-label fs-3">Apellidos</label>
                    <input type="text" className="form-control fs-6" name="" id="" value={apellido} onChange={(e)=>handleSubmit(e,'apellido')} required autoComplete="off"/>
                </div>
                <div className="mb-3 col-4">
                    <label htmlFor="username" className="form-label fs-3">Nombre de Usuario</label>
                    <input type="text" className="form-control fs-6" name="username" id="username" aria-describedby="usernameHelp" value={username} onChange={(e)=>handleSubmit(e,'username')} required autoComplete="off"/>
                    <small id="usernameHelp" className="form-text text-info fs-6">
                        {
                            error1?
                            logun.map((log1,index)=>(
                                <li key={index}>{log1}</li>
                            )):
                            ''
                        }
                        </small>
                </div>
                <div className="mb-3 col-4">
                    <label htmlFor="password" className="form-label fs-3">Contraseña</label>
                    <input type="password" className="form-control fs-6" name="password" id="password" aria-describedby="passwordHelp" value={password} onChange={(e)=>handleSubmit(e,'password')} required autoComplete="off"/>
                    <small id="passwordHelp" className="form-text text-info fs-6">
                        {
                            error2?
                            logpw.map((log2,index)=>(
                                <li key={index}>{log2}</li>
                            )):
                            ''
                        }
                    </small>
                </div>
                <div className="mb-3 col-4">
                    <label htmlFor="fechaNac" className="form-label fs-3">Fecha de Nacimiento</label>
                    <input type="date" className="form-control fs-6" name="fechaNac" id="fechaNac"aria-describedby="fechaNacHelp" value={fechaNac} onChange={(e)=>handleEdad(e)} required autoComplete="off"/>
                    <small id="fechaNacHelp" className="form-text text-info fs-6">{error3?logf:''}</small>
                </div>
                <button type="button" className="btn btn-info" onClick={insertSubmit}>Insertar Usuario</button>
            </div>
        </div>
    );
}