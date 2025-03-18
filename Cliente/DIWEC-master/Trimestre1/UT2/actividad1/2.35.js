function ej2_35(){
    const p=document.getElementById("35");

    let num1=parseInt(prompt("Introduce el primer número"));
    let num2=parseInt(prompt("Introduce el segundo número"));

    let result=0;

    for(let i=0; i<num2; i++){
        result+=num1;
    }
    p.innerHTML="La suma es: "+result;
}