function ej2_49(){
    const p=document.getElementById("49");
    let suma=0;
    let sumaim=0;

    for(let i=1; i<=200; i++){
        if(i%2==0){
            suma+=i;
        }
        if(i%2!=0){
            sumaim+=i;
        }
    }
    p.innerHTML="La suma de los numeros pares es: "+suma+"<br>La suma de los numeros impares es: "+sumaim;
}