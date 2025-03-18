class coche{
    constructor(name){
        this.nombre=name;
    }

    crear(){
        document.write("<p>",this.nombre,"</p>");
    }
}

var str="Uso de Variables y su muestra en el HTML";
function mensaje(){
    document.write("<h1>Hola, Mundo U<h1/>");
}
function prueba(){
    document.write("<h1>",str,"</h1>");
    str="Cambio de la variable";
    document.write("<h1>",str,"</h1>");
}