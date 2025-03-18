function ej2_14(){
    let num=prompt("Introduce un numero a adivinar");

    let adivinar=prompt("Prueba suerte");
    let cont=0;
    let salida=true;

    while (salida==true && cont<5){
        if (adivinar==num){
            alert("Acertaste");
            salida=false;
        }else{
            adivinar=prompt("Prueba suerte otra vez");
        }
    }
}