function ej2_50(){
    const p=document.getElementById("50");
    let num=parseInt(prompt("Introduce un numero para calcular su base en 16"));
    
    let hex = num.toString(16);
    p.innerHTML=hex;
}