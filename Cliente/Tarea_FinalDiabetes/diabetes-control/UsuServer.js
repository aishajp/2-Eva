const url = "http://localhost:8080/2%c2%baEva/Cliente/Tarea_FinalDiabetes/diabetes-control/usuarios.php";
// Obtención de todos los usuarios
export const getAllUsuarios = async () => {
    const mensajeError = "Error al obtener todos los usuarios";
   try {
   const respuesta = await fetch(url);
   if (!respuesta.ok) throw new Error (mensajeError);
   return await respuesta.json();
   } 
    catch (error) 
   {
   console.error(mensajeError, error);
   return [];
   }
   };
   // Obtención de un usuario por ID
export const getNotaById = async (idNota) => {
let mensajeError = `Error al obtener la nota con id ${idNota}`;
try {
const respuesta = await fetch(`${url}?id=${idNota}`);
if (!respuesta.ok) throw new Error(mensajeError);
return await respuesta.json();
} catch (error) {
console.error(mensajeError, error);
return null;
}
};
// Añadir nuevo usuario
export const createUsu = async (nuevoUsu) => {
    const initObject = {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    mode: "cors",
    body: JSON.stringify(nuevoUsu),
    };
    const mensajeError = "Error al añadir el usuario";
    try {
    const respuesta = await fetch(url, initObject);
    if (!respuesta.ok) throw new Error(mensajeError);
    return await respuesta.json();
    } catch (error) {
    console.error(mensajeError, error);
    return null;
    }
    };
    // Actualizar un usuario existente
export const updateUsu = async (actUsu) => {
    const initObject = {
    method: "PUT",
    headers: { "Content-Type": "application/json" },
    mode: "cors",
    body: JSON.stringify(actUsu),
    };
    const mensajeError = "Error al actualizar el usuario";
    try {
    const respuesta = await fetch(url, initObject);
    if (!respuesta.ok) throw new Error(mensajeError);
    return await respuesta.json();
    } catch (error) {
    console.error(mensajeError, error);
    return null;
    }
    };
    //Eliminar usuarios
    export const deleteUsuById = async (idUsu) => {
        const initObject = {
        method: "DELETE",
        headers: { "Content-Type": "application/json" },
        mode: "cors",
        body: JSON.stringify({id: idUsu}),
        };
        const mensajeError = "Error al eliminar el usuario";
        try 
         {
        const respuesta = await fetch(url, initObject);
        if (!respuesta.ok) throw new Error(mensajeError);
        return true;
        }
         catch (error)
         {
        console.error(mensajeError, error);
        return false;
        }
        };
        
