function ej2_15(){
    let num = 7;
    const p=document.getElementById("15");
    p.innerHTML=" 'Switch con condiciones' ";
    switch (true){
        case num<5:
            p.innerHTML="Suspenso";
            break;
        case num<=6:
            p.innerHTML="Aprobado";
            break;
        case num<=8:
            p.innerHTML="Notable";
            break;
        case num<=10:
            p.innerHTML="Sobresaliente";
            break;
    }
}