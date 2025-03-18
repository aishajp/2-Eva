function calcularCirculo(){
    const pi = 3.141592;
    let radio=5;

    let circuferencia=(radio*2)*pi;
    let area=pi*(radio**2);
    const p=document.getElementById("7");
    p.textContent="Longitud es: "+circuferencia+" y el area es: "+area;
}