function ej2_38(){
    const p=document.getElementById("38");
    let cont=0;
    for (let i=1;i<=25;i+=3){
        cont++;
        p.innerHTML+=i+" ";
        if(cont%4==0){
            p.innerHTML+="<br>";
        }
    }
}