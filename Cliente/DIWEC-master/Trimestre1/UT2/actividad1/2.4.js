function mostrarVida(){
    let edad=0;
    let nombre="";

    nombre=prompt("Introduce tu nombre: ");
    edad=prompt("Introduce tu edad: ");

    let vida=edad*365;
    const p=document.getElementById("4");
    p.textContent=`Nombre: ${nombre} , dias vividos: ${vida}`;
}