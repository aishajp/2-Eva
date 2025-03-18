function ej2_13(){
    let precio = prompt("Introduce el precio: ");
    let iva = prompt("Introduce IVA: ");

    let preciototal = (precio*iva);
    const p=document.getElementById("13");
    p.innerHTML=`Precio total a pagar es: ${preciototal}`;
}