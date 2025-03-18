function ej2_39(){
    let feliz=prompt("Cuantas veces dira feliz?");
    const p=document.getElementById("39");

    for(let i=0; i<feliz; i++){
        p.innerHTML+="Feliz! ";
    }
    p.innerHTML+=" Cumpleaños";
}