const Formulario = () => {
    const manejaCambioInput = (e) =>{
        console.log('Valor del input :'+ e.target.value);
    }
    return (
        <>
            <input type="text" onChange={manejaCambioInput} />
        </>
    )
}

export default Formulario;