function ej2_42(){
    const p=document.getElementById("42");
    let num=parseInt(prompt("Introduce un numero para calcular su factorial"));
    let factorial =1;
    for(let i=1;i<=num;i++){
        factorial*=i;
        p.innerHTML+="El factorial de "+num+" es: "+factorial+"<br>";
    }
}