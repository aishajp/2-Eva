function ej2_54(){
    const p=document.getElementById("54");

    let cadena=prompt("Introduce una cadena");

    p.innerHTML = "Longitud: "+cadena.length;
    p.innerHTML+="<br>"+cadena.italics().bold().strike().big().fontcolor("red");
}