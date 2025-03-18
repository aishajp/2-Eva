function ej2_57(){
    const p=document.getElementById("57");
    let precio=parseFloat(prompt("Precio del producto"));
    let iva=parseFloat(prompt("IVA a aplicar"));

    let preciototal=precio+(precio*iva);
    p.innerHTML="Precio mas IVA es: "+preciototal;
}