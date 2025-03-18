import Cliente from './cliente.js';
export default class Pedido{
    constructor(numpedido,nombre,apellido,procesado,servido,fecha){
        this.numpedido=parseInt(numpedido>0? numpedido:this.numpedido);
        this.cliente=new Cliente(nombre?nombre:this.nombre,apellido?apellido:this.apellido);
        this.fechapedido=this.validarFecha(fecha);
        this.procesado=Boolean(procesado);
        this.servido=Boolean(servido);
    }
    validarFecha(fecha) {
        let Fnow=new Date(Date.now());
        let Frec=new Date(Date.parse(fecha));
        let fech="";
        if (Frec>=Fnow){
            fech=Fnow.getDate()+"-"+(Fnow.getMonth()+1)+"-"+Fnow.getFullYear();
            return fech;
        }else{
            fech=Frec.getDate()+"-"+(Frec.getMonth()+1)+"-"+Frec.getFullYear();
            return fech;
        }
    }
}