function ej2_28(){
    let v1 =2;
    let v2 =2;
    let v3 =2;
    const p=document.getElementById("28");

    if(v1 == 0 && v2 == 0 && v3 == 0){
        p.innerHTML+="Todas las variables son 0<br>";
    }
    if(v1 > 0 && v2 >0 && v3>0){
        p.innerHTML+="Todos los valores son positivos<br>";
    }
    if(v1 < 0 && v2 < 0 && v3 < 0){
        p.innerHTML+="Todos los valores son negativos<br>";
    }
    if(v1!=v2!=v3){
        if(v1!=v2 && v2!=v3){
            p.innerHTML+="Todos los valores son distintos<br>";
        }else{
            p.innerHTML+="Dos valores son iguales<br>";
        }
    }else{
        p.innerHTML+="Como maximo 2 de sus valores son iguales<br>";
    }
    if((v2>v1 && v2<v3)|| (v2<v1 && v2>v3)){
        p.innerHTML+="El valor de v2 esta comprendido entre v1 y v3<br>";
    }
}