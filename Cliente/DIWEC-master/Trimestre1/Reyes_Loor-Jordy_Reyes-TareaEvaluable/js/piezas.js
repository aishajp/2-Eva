export default class Pieza{
    constructor(numpiezas,numpedido,largo,ancho,grosor,color,ambascaras,cortada){
        this.numpiezas=parseInt(numpiezas>0?numpiezas:this.numpiezas);
        this.numpedido=parseInt(numpedido>0?numpedido:this.numpiezas);
        this.largo=parseFloat(largo>0?largo:this.largo);
        this.ancho=parseFloat(ancho>0?ancho:this.ancho);
        this.grosor=parseFloat(grosor>0?grosor:this.grosor);
        this.color=color?color:this.color;
        this.ambascaras=Boolean(ambascaras);
        this.cortada=Boolean(cortada);
    }
}