function ej2_25(){
    let apuesta = parseInt(prompt("Jugaras a doble o nada, introduce la cantidad a apostar"));
    let ran= Math.floor(Math.random()*(2));//0 cara, 1 cruz

    if(ran==0) apuesta=apuesta*2
    else apuesta=0
    document.getElementById("25").innerHTML="Tu apuesta es: " + apuesta;
}   