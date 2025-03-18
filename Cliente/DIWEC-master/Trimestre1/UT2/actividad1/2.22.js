function ej2_22(){
    let suma=0;
    let num=0;
    let sw=true;
    let cont=0;
     const p=document.getElementById("22");
    while(sw){
        num = prompt("Introduce un numero|Si quieres salir introduce una letra");

        if (isNaN(num)){
            sw=false;
        }else{
            suma += parseInt(num);
            cont++;
        }
    }
    p.innerHTML+="<br>La suma de los numeros introducidos es: "+suma;
    p.innerHTML+="<br>El numero de numeros introducidos es: "+cont;
    let promedio=suma/cont;
    p.innerHTML+="<br>El promedio es: "+promedio;
}