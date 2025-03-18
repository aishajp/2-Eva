function ej2_34(){
    let costoCena = parseInt(prompt("Introduce el costo de la cena"));
    let propinaPequeña = costoCena * 0.15;
    let propinaGrande = costoCena * 0.25;
    const p=document.getElementById("34");
    p.innerHTML="La propina más pequeña vale: " + propinaPequeña;
    p.innerHTML="<br>La propina más grande vale: " + propinaGrande;
}