function ej2_52(){
    let num=prompt("Introduce un numero");
    let base=prompt("Introduce una base");
    
    num = parseInt(num);
    base = parseInt(base);

    if(isNaN(num) || num < 1){
        alert("Numeros introducidos no validos");
    }else if(isNaN(base) || base < 2 || base > 9){
        alert("Numero debe ser positivo");
    }else{
        let c = num;
        let r;
        let res=' ';
        while (c>=base){
            r = c % base;
            c = parseInt(c / base);
            res = String(r) + res;
        }
        document.write('El numero ' +num +' en base '+ base+ ': '+ res);
    }
}