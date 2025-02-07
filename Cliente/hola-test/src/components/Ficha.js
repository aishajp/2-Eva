function Ficha(props) {
    const foto = props.foto;
    return (
    <div className="ficha" id={foto.id}>
    <h2>{foto.titulo}</h2>
    <p>{foto.descripcion}</p>
    <figure className="elemento-foto">
    <img src={`public/images/${foto.url}`} alt={foto.alt} />
    </figure>
    </div>
    )
    }
    export default Ficha;    