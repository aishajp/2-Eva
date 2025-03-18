function ej2_23(){
    let pares=0;
    let num = 12;
    let num2 = 0;
    let may=Math.max(num, num2);
    let men=Math.min(num, num2);
    for(let i=1; i<=may; i++){
        if(i % 2 === 0){
            pares++;
        }
    }
    document.getElementById("23").innerHTML="Hay " + pares + " pares";
}