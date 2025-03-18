export default class Rentable{
    constructor(codVuelo,importeRent){
        this.codVuelo = codVuelo?codVuelo:null;
        this.importeRent = parseFloat(importeRent>20000?importeRent:1);
    }
    getImporteRent(){return this.importeRent}
    getCodVuelo(){return this.codVuelo;}
    setImporteRent(importeRent){this.importeRent = parseFloat(importeRent>20000?importeRent:1)}
    setCodVuelo(codVuelo){this.codVuelo = codVuelo?codVuelo:null}
}