export function TablaUsuarios(data){
    let compos=``;
    const handleButton= (username,tipo)=>{
        if(tipo=="modificar"){

        }
        else if(tipo=="eliminar"){
            
        }
    }
    const tablaData = (data)=>{
        let tablaHTML ='';
        for(const key in data){
            const valor=data[key];
            if(valor.length){
                for(let i=0;i<valor.length;i++){
                    let datosMostrar = valor[i]?valor[i]:valor;
                    tablaHTML += `<tr>
                        <td>${datosMostrar.username}</td>
                        <td>${datosMostrar.nombre}</td>
                        <td>${datosMostrar.apellido}</td>
                        <td>${datosMostrar.fechaNac}</td>
                    </tr>`
                }
            }else{
                compos=`<th scope="col">Modificar</th><th scope="col">Eliminar</th>`;
                tablaHTML += `<tr>
                        <td>${valor.username}</td>
                        <td>${valor.nombre}</td>
                        <td>${valor.apellido}</td>
                        <td>${valor.fechaNac}</td>
                        <td><button id="${valor.username}" value="${valor.username}" type="button" class="align-self-end btn btn-info" onClick="handleButton(${valor.username},'modificar')">Modificar Usuario</button></td>
                        <td><button id="${valor.nombre}" value="${valor.nombre}" type="button" class="align-self-end btn btn-info" onClick="handleButton(${valor.username},'eliminar')">Eliminar Usuario</button></td>
                    </tr>`
            }
        }
        return tablaHTML;
    }

    const mostrarDatos = (data)=>{
        const mostrarUsuario=$('#mostrarUsuario');
        let datos = `
            <div className="table-responsive mx-auto">
                <table className="table table-primary">
                    <thead>
                        <tr>
                            <th scope="col">Nombre de Usuario</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Apellido</th>
                            <th scope="col">Fecha de Nacimiento</th>
                            ${compos}
                        </tr>
                    </thead>
                    <tbody>
                        ${tablaData(data)}
                    </tbody>
                </table>
            </div>`;
        $(mostrarUsuario).html(datos);
    }
    return(mostrarDatos(data));
}