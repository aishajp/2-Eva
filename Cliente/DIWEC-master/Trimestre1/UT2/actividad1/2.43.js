function ej2_43(){
    let num=parseInt(prompt("Introduce un numero"));
    let suma=0;
    for (let i=1;i<=10;i++){
        let r = num * i;
        suma+=r;
        document.getElementById("43").innerHTML+=num+" x "+i+" = "+r+"<br>";
    }
    document.getElementById("43").innerHTML+="La suma de los resultados es: "+suma;
}