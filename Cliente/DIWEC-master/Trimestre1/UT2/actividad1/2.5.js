function multiplicarNum(){
    let num=0;
    const p=document.getElementById("5");
    num= window.prompt("Introduce un numero: ");

    for(let i=2;i<=4;i++){
        p.textContent="Numero multiplicado por "+i+ " es igual a "+(num*i)+"<br>";
    }
}