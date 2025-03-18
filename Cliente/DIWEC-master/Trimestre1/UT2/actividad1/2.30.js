function ej2_30(){
    let death=0;
    let herido=0;
    let secret=numeros4("secret");
    const p=document.getElementById("30");

    for (i=0; i<8; i++){
        let adivina=numeros4("adivina"+i);
        p.innerHTML+="Tu numero es: ";
        for (num in adivina){p.innerHTML+=adivina[num]+" "}
        p.innerHTML+="<br>Intento "+(i+1)+"<br>";
        alert("Intento "+(i+1));
        for (j=0; j<4;j++) {
            for (k=0; k<4; k++) {
                herido=0;
                if (secret[j]==adivina[k]){
                    if (j==k){
                        death++;
                    } else {
                        herido++;
                    }
                }
            }
            if (death>=4){
                j=4;
                i=8;
            }
        }
        p.innerHTML+="H: "+herido+" M: "+death+"<br>";
        alert("H: "+herido+" M: "+death);
    }
}

function numeros4(text){
    let secret=[];
    for(let cont=0;cont<4;cont++){
        let numer=prompt("Introduce "+text+" entre 0 y 9 ("+cont+")");
        if (numer>=0 && numer<=9){
            secret.push(parseInt(numer));
        }else{
            while(secret<0 || secret>9){
                let numer=prompt("Introduce "+text+" entre 0 y 9 ("+cont+")");
            }
            secret.push(parseInt(numer));
        }
    }
    return secret;
}