function ej2_47(num){
    const p=document.getElementById("47");
    let salida="El numero es primo";
    for (let i=2; i<=(num/2);i++) {
        if (num % i == 0){
            salida="El numero no es primo";
            i=num;
        }
    }
    p.innerHTML=salida;
}