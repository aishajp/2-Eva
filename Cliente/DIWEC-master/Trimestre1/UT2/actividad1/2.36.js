function ej2_36(){
    const p=document.getElementById("36");

    let numero1 = parseInt(prompt("Ingrese el primer número:"));
    let numero2 = parseInt(prompt("Ingrese el segundo número:"));

    let cociente = numero1;
    let resto = numero2;
    p.innerHTML += `Divisor: ${numero1}, Dividendo: ${numero2}<br>`;
    while (resto!=0){
        let temp = resto;
        cociente = cociente / resto;
        resto = cociente % resto;
        p.innerHTML += `Cociente: ${cociente}, Resto: ${resto}<br>`;
    }
}