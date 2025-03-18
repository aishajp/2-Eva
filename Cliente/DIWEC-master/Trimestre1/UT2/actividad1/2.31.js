function ej2_31(radio){
    const pi = Math.PI;
    let V= (4* pi * radio**3)/3;
    let A= pi * radio**2;
    document.getElementById("31").innerHTML="El volumen es: "+V+" y el área es: "+A;
}