function ej2_44(){
    const p=document.getElementById("44");
    let cont=1;
    let suma=0;
    do{
        suma+=cont;
        p.innerHTML+=suma+" ";
        cont++;
    }while(cont<=10)
}