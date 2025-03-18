function ej2_37(){
    const p=document.getElementById("37");

    let cena = parseInt(prompt("Pulse 1 para cenar Costilla, pulse 2 para cenar Pescado:"));
    let postre = confirm("¿Desea postre?");
    let propina = 0.06;

    let precioCena = 0;
    switch(cena){
        case 1:
            precioCena = 23;
            break;
        case 2:
            precioCena = 15;
            break;
    }
    if(postre){
        precioCena += 3;
    }
    precioCena = precioCena * 1.06;
    
    p.innerHTML = "Precio total de la cena " + precioCena + "€";
}