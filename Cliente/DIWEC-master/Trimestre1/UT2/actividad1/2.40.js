function ej2_40(){
    const p=document.getElementById("40");
    let cont=1;
    let linea=0;
    do{
        linea++;
        p.innerHTML+=cont+" ";
        if(linea%4==0){
            p.innerHTML+="<br>";
        }
        cont+=3;
    }while(cont<=25);
}