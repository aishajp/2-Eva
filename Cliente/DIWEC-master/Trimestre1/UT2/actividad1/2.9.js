function llamarSaludo(){
    let primer_saludo="hola";
    let segundo_saludo=primer_saludo;
    primer_saludo="hello";

    const p=document.getElementById("9");
    p.textContent=segundo_saludo;
}