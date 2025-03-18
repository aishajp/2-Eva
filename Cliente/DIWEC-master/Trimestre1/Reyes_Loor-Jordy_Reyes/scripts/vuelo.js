export default class Vuelo{
    constructor(codigo,numPlaza,importe){
        this.codigo = codigo?codigo:null;
        this.numPlaza = parseInt(numPlaza>0?numPlaza:0);
        this.importe = parseFloat(importe>0?importe:0.0);
    }
    getCodigo(){return this.codigo}
    getNumPlaza(){return this.numPlaza}
    getImporte(){return this.importe}
    setImporte(importe){this.importe = parseFloat(importe>0?importe:0.0)}
    setCodigo(codigo){this.codigo = codigo?codigo:nul}
    setNumPlaza(numPlaza){this.numPlaza = parseInt(numPlaza>0?numPlaza:0)}
}