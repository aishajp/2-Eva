import Vuelo from "./vuelo.js";
import Rentable from "./vueloRentable.js";
let vuelo=[];
let rentable=[];
const addButton=document.getElementById('addVuelo');
const modButton=document.getElementById('modVuelo');
const calcButton=document.getElementById('calcular');
const divMostrar=document.getElementById('mostrarDatos');
const cod=document.getElementById('codigo');
const nPlazas=document.getElementById('nPlazas');
const imp=document.getElementById('importe');

function crearVuelo(cod,nPlazas,imp){
    return new Vuelo(cod,nPlazas,imp);
}

function crearRentable(cod,ingreso){
    return new Rentable(cod,ingreso);
}
function calcularIngreso(){
    return nPlazas.value*imp.value;
}
function addVuelo(){
    let ingreso=calcularIngreso();
    if(!vuelo.find(v=>v.codigo==cod.value)){
        let v=crearVuelo(cod.value,nPlazas.value,imp.value);
        if(ingreso>=20000){
            let r=crearRentable(cod.value, ingreso);
            rentable.push(r);
        }
        vuelo.push(v);
    }else{
        alert('Vuelo ya existe');
        return;
    }
    detalles();
}
function modVuelo(){
    let ingreso=calcularIngreso();
    let index=vuelo.findIndex(v=>v.getCodigo()==cod.value);
    let index2=rentable.findIndex(r=>r.getCodVuelo()==cod.value);
    console.log(vuelo,rentable);
    if(vuelo.find(v=>v.getCodigo()==cod.value)){
        vuelo[index].setNumPlaza(nPlazas);
        vuelo[index].setImporte(imp);
        if(index2!=-1){
            if(ingreso<20000){
                rentable.splice(index2,1);
            }else{
                rentable[index2].setImporteRent(ingreso);
            }
        }else if(ingreso>=20000){
            let r=crearRentable(cod.value, ingreso);
            rentable.push(r);
        }
    }else{
        alert('Vuelo no encontrado');
        return;
    }
    detalles();
}

function calcular(){
    let ingreso=calcularIngreso();
    let h2=document.querySelector('h2');
    if(!h2){
        h2=document.createElement('h2');
        divMostrar.appendChild(h2);
    }

    if(ingreso<10000)
        h2.innerHTML=ingreso+' €, Poco Rentable';
    if(ingreso>=10000 && ingreso<=20000)
        h2.innerHTML=ingreso+' €, Rentable';
    if(ingreso>20000)
        h2.innerHTML=ingreso+' €, Muy Rentable';
}
function detalles(){
    let ingreso=calcularIngreso();
    const tbl=document.getElementById('tbl');
    let tbody=document.querySelector('tbody');
    if(!tbody){
        tbody=document.createElement('tbody');
        tbl.appendChild(tbody)
    }
    tbody.innerHTML=`
                    <tr>
                    <th>Codigo</th>
                    <th>Nº de Plaza</th>
                    <th>Ingreso Estimado</th>
                    </tr>    
    `;
    rentable.forEach((r)=>{
        let index=vuelo.findIndex(v=>v.getCodigo()==r.getCodVuelo());
        let lista=document.createElement('tr');
        lista.innerHTML=`
                        <td>${r.getCodVuelo()}</td>
                        <td>${vuelo[index].getNumPlaza()}</td>
                        <td>${ingreso}€</td> 
        `;
        tbody.appendChild(lista); 
    });
}

const cargarEventos=()=>{
    addButton.addEventListener('click', addVuelo);
    modButton.addEventListener('click', modVuelo);
    calcButton.addEventListener('click', calcular);
};

window.cargarEventos=cargarEventos;