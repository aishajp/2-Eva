function ej2_33(){
    let numero = parseInt(prompt("Introduce un número:"));
    const p=document.getElementById("33");

    if(numero < 0){
        alert("Número inválido");
    } else {
        let solucion1 = (numero * 2 + 1) / (3 - 7);
        let solucion2 = numero + 2;
        let solucion3 = numero * 2.5;
        p.innerHTML="Solución 1: " + solucion1 + "<br>";
        p.innerHTML="Solución 2: " + solucion2 + "<br>";
        p.innerHTML="Solución 3: " + solucion3 + "<br>";
        p.innerHTML="Fin de operación con el número: " + numero;
    }
}