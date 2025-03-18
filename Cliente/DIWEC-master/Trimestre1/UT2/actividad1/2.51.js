function ej2_51(){
    const p=document.getElementById("51");

    let num=parseInt(prompt("Introduce un numero en base 8"));

    let numBinario = num.toString(2);
    let numDecimal=num.toString(10);

    p.innerHTML+="El número en base 2 es: "+numBinario+"<br>";
    p.innerHTML+="El número en base 10 es: "+numDecimal+"<br>";
}