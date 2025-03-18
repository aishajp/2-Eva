function ej2_17(){
    let num1= 1;
    let num2=2;
    let may=Math.max(num1,num2);
    let men=Math.min(num1,num2);
    const p=document.getElementById("17");
    while(may<50 && men<50){
        p.innerHTML+=` Mayor ${may}, Menor ${men}`;
        may-=2;
        men+=5;
        num1=may;
        num2=men;
        may=Math.max(num1,num2);
        men=Math.min(num1,num2);
    }
}