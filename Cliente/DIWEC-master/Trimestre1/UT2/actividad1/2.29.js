function ej2_29(){
    let num = prompt("Introduce un numero");
    let save=0;
    for (let i = 1; i <= num;i++){
        save+=i;
        document.getElementById("29").innerHTML+=save+" ";
    }  
}