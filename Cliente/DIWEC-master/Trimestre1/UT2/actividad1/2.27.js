function ej2_27(){
    let dia=0, mes=0,anio=0;
    dia=prompt("Introduce dia");
    mes=prompt("Introduce mes");
    anio=prompt("Introduce año");
    const p=document.getElementById("27");
    if (dia>0 && dia < 32){
        if (mes>0 && mes<13){
            if (anio>0){
                p.innerHTML+="La fecha es correcta";
                if (anio%4==0 || anio%400==0){
                    p.innerHTML+="<br>El anio es bisiesto " + `${dia}/${mes}/${anio}`;
                }else{
                    p.innerHTML+="<br>El anio no es bisiesto "  + `${dia}/${mes}/${anio}`;
                }
            }else{
                p.innerHTML+="El año introducido no es valido";
            }
        }else{
            p.innerHTML+="El mes introducido no es valido";
        }
    }else{
        p.innerHTML+="El dia introducido no es valido";
    }
}