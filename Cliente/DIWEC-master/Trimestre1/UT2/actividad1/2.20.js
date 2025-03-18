function ej2_20() {
let opcion;
let numero;
let suma = 0;
const p=document.getElementById("20");
    p.innerHTML="1. Salir<br>";
    p.innerHTML="2. Sumatorio<br>";
    p.innerHTML="3. Factorial<br>";

    opcion = parseInt(prompt("Introduce una opción:"));

    while (opcion != 1){
        switch (opcion) {
            case 1:
                alert("Saliendo...<br>");
                break;
            case 2:
                numero = parseInt(prompt("Introduce un número:"));
                for (let i = 1; i <= numero; i++) {
                    suma += i;
                }
                p.innerHTML+="El sumatorio de los números desde 1 hasta " + numero + " es: " + suma + "<br>";
                suma = 0;
                break;
            case 3:
                numero = parseInt(prompt("Introduce un número:"));
                let factorial = 1;
                for (let i = 2; i <= numero; i++) {
                    factorial *= i;
                }
                p.innerHTML+="El factorial de " + numero + " es: " + factorial + "<br>";
                break;
        }
        opcion = parseInt(prompt("Introduce una opción:"));
    }
}