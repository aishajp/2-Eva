const Bucle=()=>{
    const personajes=[
        {nombre: "Seung Gi-hun",edad: 53},
        {nombre: "Hwang Jun-Ho", edad:52},
        {nombre: "Park Woojin", edad:27}
    ]
    return(
        <>
            {
                personajes.map( (p) => 
                <li key={p}><b>{p.nombre},{p.edad} años</b></li>
            )
            }
        </>
    )
}
export default Bucle;