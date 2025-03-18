function ej2_12(){
    const p=document.getElementById("12");
    let num = 4;//prompt("Introduce un numero: ")
    let salida = "";
    for (let i=1;i<=num;i++){
        for(let j=1;j<=num;j++){
            salida+="* ";
        }
        salida+="<br>";
    }
    p.innerHTML=salida;
}