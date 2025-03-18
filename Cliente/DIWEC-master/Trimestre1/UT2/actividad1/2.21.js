function ej2_21(){
    let totalPersonas = parseInt(prompt("Ingrese el total de personas en el salón de clases:"));
    let hombres = 0;
    let mujeres = 0;
    const p=document.getElementById("21");
    for(let i = 1; i <= totalPersonas; i++){
        let sexo = prompt("Ingrese el sexo de la persona " + i + " (H para hombre, M para mujer):").toUpperCase();

        if(sexo === "H"){
            hombres++;
        }else if(sexo === "M"){
            mujeres++;
        } else {
            alert("Sexo no válido. Por favor ingrese 'H' para hombre y 'M' para mujer.");
            i--;
        }
    }

    let porcentajeHombres = (hombres / totalPersonas) * 100;
    let porcentajeMujeres = (mujeres / totalPersonas) * 100;

    p.innerHTML="Hay un "+porcentajeHombres+"% hombres, y un "+ porcentajeMujeres+"% mujeres en esta clase";
}