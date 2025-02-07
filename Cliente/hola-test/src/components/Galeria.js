import Ficha from "./Ficha";
function Galeria(props) {
const fotos = props.fotos;
return (
<div className="galeria">
{fotos.map((foto) => (
<Ficha key={foto.id} foto={foto} />
))}
</div>
);
}
export default Galeria;