function ej2_26(){
    let num1=prompt("Introduce numero 1");
    let num2=prompt("Introduce numero 2");
    let num3=prompt("Introduce numero 3");
    const p=document.getElementById("26");
    //ordenar de forma decreciente

    if (num1>num2){
        if (num1>num3){
            p.innerHTML+=`Los numeros ordenados de manera decreciente son: ${num1}, ${num2}, ${num3}`;
        }else{
            p.innerHTML+=`Los numeros ordenados de manera decreciente son: ${num3}, ${num1}, ${num2}`;
        }
    }else if(num1>num3){
        p.innerHTML+=`Los numeros ordenados de manera decreciente son: ${num2}, ${num1}, ${num3}`;
    }else{
        p.innerHTML+=`Los numeros ordenados de manera decreciente son: ${num3}, ${num2}, ${num1}`;
    }
}