function ej2_41(){
    const p=document.getElementById("41");
    let suma=0;

    for (let i=1; i<=30; i++){
        if ((i*11)<300){
            p.innerHTML+=i*11+" ";
            suma+=i*11;
        }
    }
    p.innerHTML+="<br>La suma es: "+suma;
}