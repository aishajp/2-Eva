function ej2_45(){
    const p=document.getElementById("45");
    let numeros=[];
    let num= parseInt(prompt("Introduce un numero para visualizar"));

    while(num!=0){
        numeros.push(parseInt(num));
        num=prompt("Introduce un numero para visualizar");
    }
    p.innerHTML=numeros.join(", ");
}