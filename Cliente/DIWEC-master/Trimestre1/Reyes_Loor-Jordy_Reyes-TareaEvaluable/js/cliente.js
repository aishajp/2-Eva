export default class Cliente{
    constructor(nombre,apellido){
        this.nombre=nombre?nombre:this.nombre;
        this.apellido=apellido?apellido:this.apellido;
    }
}