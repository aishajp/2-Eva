//import "./ListadoNotas.css";
import { useState, useEffect } from "react";
import { getAllUsuarios, deleteUsuByUsername, updateUsu, createUsu } from "../UsuServer";
import FormUsu from "./FormUsu";
const ListadoUsu = () => {
    const [usuarios, setUsu] = useState([]);
    const [usuActual, setUsuActual] = useState(null);
    const [formVisible, setFormVisible] = useState(false);
    const loadUsu = async () => {
try {
const UsuServer = await getAllUsuarios();
setUsu(UsuServer);
} catch (error) {
console.error("Error al cargar los usuarios:", error);
}
};
const showForm = (usu) => {
    setUsuActual(usu);
setFormVisible(true);
};
const updateUsuEvt = (usu) => showForm(usu);
const deleteUsuEvt = async (id_usu) => {
let mensajeError = `Se ha producido un error al borrar el usuario con identificador 
${id_usu}`;
try {
const exito = await deleteUsuByUsername(id_usu);
if (!exito) alert(mensajeError);
else {
alert(`El usuario con identificador ${id_usu} se ha borrado correctamente`);
loadUsu();
}
} catch (error) {
alert(mensajeError, error);
}
};
const saveUsuEvt = async (usu) => {
try {
 // Si la nota tiene id, se trata de una actualización, si no, es nueva
if (usu.id_usu) {
await updateUsu(usu);
alert(
`Se ha modificado el usuario con identificador ${usu.id_usu} correctamente`
);
} else {
const nuevoUsu = await createUsu(usu);
alert(`El usuario se ha añadido correctamente con id ${nuevoUsu.id_usu}`);
}
// Se recargan las notas y se oculta el form
loadUsu();
setFormVisible(false);
} catch (error) {
alert("Se ha producido un error al guardar el usuario: ", error);
}
};
useEffect(() => { loadUsu(); }, []);
return (
<>
<button onClick={() => showForm()}>
Añadir Usuario
</button>
<table className="tabla">
<thead>
<tr>
<th>ID</th>
<th>Nombre</th>
<th>Apellidos</th>
<th>Fecha de nacimiento</th>

</tr>
</thead>
<tbody>
{usuarios?.map((usu) => (
<tr key={usu.id_usu} className="fila">
<td className="celda-id">{usu.id_usu}</td>
<td className="celda-texto">{usu.nombre}</td>
<td className="celda-importancia">{usu.apellidos}</td>
<td className="celda-importancia">{usu.fecha_nacimiento}</td>

<td>
<button onClick={() => updateUsuEvt(usu)}>Actualizar</button>
<button onClick={() => deleteUsuEvt(usu.id_usu)}>Borrar</button>
</td>
</tr>
))}
</tbody>
</table>
{formVisible && (
<FormUsu
usu={usuActual}
onSave={saveUsuEvt}
onCancel={() => setFormVisible(false)}
/>
)}
</>
);
};
export default ListadoUsu