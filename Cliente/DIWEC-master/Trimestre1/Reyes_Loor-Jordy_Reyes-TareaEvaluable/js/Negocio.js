import Pedido from './pedidos.js';
import Pieza from './piezas.js';

const GetDatPiezas=localStorage.getItem('datosPiezas');
let Listpiezas=GetDatPiezas?JSON.parse(GetDatPiezas):[];

const GetDatPedidos=localStorage.getItem('datosPedidos');
let Listpedidos=GetDatPedidos?JSON.parse(GetDatPedidos):[];

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function darAltaPieza(){
    console.log(Listpiezas);
    if(Listpiezas.find((el)=>el.numpiezas==document.getElementById('numpiezaalta').value)){
        alert('El número de pieza ya existe');
        return;
    }else{
        let piz=new Pieza(
            document.getElementById('numpiezaalta').value,
            document.getElementById('numpedidoPieza').value,
            document.getElementById('largo').value,
            document.getElementById('ancho').value,
            document.getElementById('grosor').value,
            document.getElementById('color').value,
            document.getElementById('ambascaras').value,
            document.getElementById('cortada').value
        );
        Listpiezas.push(piz);
        localStorage.setItem('datosPiezas',JSON.stringify(Listpiezas));
        window.altaPieza.close();
    }
    datosPiezas();
}

function darBajaPieza(){
    console.log(Listpiezas);
    if(Listpiezas.find((el)=> el.numpiezas==document.getElementById('numpiezabaja').value)){
        Listpiezas.splice(Listpiezas.findIndex(el=> el.numpiezas==document.getElementById('numpiezabaja').value),1);
    }else{
        alert('El número de pieza no existe');
        return;
    }
    localStorage.setItem('datosPiezas',JSON.stringify(Listpiezas));
    window.bajaPieza.close();
    datosPiezas();
}

function modificarPieza(){
    if(Listpiezas.find((el)=> el.numpieza==document.getElementById('numpiezamod').value)){
        Listpiezas.splice(Listpedidos.findIndex(el=> el.numpedido==document.getElementById('numpiezamod').value),1);
        let ped=new Pieza(
            document.getElementById('numpiezamod').value,
            document.getElementById('numpedidoPiezamod').value,
            document.getElementById('largomod').value,
            document.getElementById('anchomod').value,
            document.getElementById('grosormod').value,
            document.getElementById('colormod').value,
            document.getElementById('ambascarasmod').value,
            document.getElementById('cortadamod').value
        );
        Listpiezas.push(ped);
        localStorage.setItem('datosPiezas',JSON.stringify(Listpiezas));
        window.modificarPedido.close();
    }else{
        alert('El número de pieza no existe');
        return;
    }
    datosPiezas();
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function darAltaPedido(){
    if(Listpedidos.find((el)=>el.numpedido==document.getElementById('numpedidoalta').value)){
        alert('El número de pedido ya existe');
        return;
    }else{
        let ped=new Pedido(
            document.getElementById('numpedidoalta').value,
            document.getElementById('nombreclientealta').value,
            document.getElementById('apellidoclientealta').value,
            document.getElementById('procesadoalta').value=='true'?true:false,
            document.getElementById('servidoalta').value=='true'?true:false,
            document.getElementById('fechaalta').value
        );
        Listpedidos.push(ped);
        localStorage.setItem('datosPedidos',JSON.stringify(Listpedidos));
        window.altaPedido.close();
    }
    datosPedidos();
}

function darBajaPedido(){
    if(Listpedidos.find((el)=> el.numpedido==document.getElementById('numpedidobaja').value)){
        Listpedidos.splice(Listpedidos.findIndex(el=> el.numpedido==document.getElementById('numpedidobaja').value),1);
    }else{
        alert('El número de pedido no existe');
        return;
    }
    localStorage.setItem('datosPedidos',JSON.stringify(Listpedidos));
    window.bajaPedido.close();
    datosPedidos();
}

function modificarPedido(){
    if(Listpedidos.find((el)=> el.numpedido==document.getElementById('numpedidomod').value)){
        Listpedidos.splice(Listpedidos.findIndex(el=> el.numpedido==document.getElementById('numpedidomod').value),1);
        let ped=new Pedido(
            document.getElementById('numpedidomod').value,
            document.getElementById('nombreclientemod').value,
            document.getElementById('apellidoclientemod').value,
            document.getElementById('procesadomod').value,
            document.getElementById('servidomod').value,
            document.getElementById('fechamod').value
        );
        Listpedidos.push(ped);
        localStorage.setItem('datosPedidos',JSON.stringify(Listpedidos));
        window.modificarPedido.close();
    }else{
        alert('El número de pedido no existe');
        return;
    }
    datosPedidos();
}
function pedForm(){
    let select=document.getElementById('numpedidomod')?document.getElementById('numpedidomod'):document.getElementById('numpedidoPieza');
    Listpedidos.forEach((el)=>{
        const opt=document.createElement('option');
        opt.value=el.numpedido;
        opt.innerHTML=el.numpedido;
        select.appendChild(opt);
    });
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function detallesPedidos(){
    let table=document.getElementById('tbl');
    let tbody=document.querySelector('tbody');
    if(!tbody){
        tbody=document.createElement('tbody');
        table.appendChild(tbody)
    }
    tbody.innerHTML=`<tr>
                    <th>Num. Pedido</th>
                    <th>Num. Pieza</th>
                    <th>Largo</th>
                    <th>Ancho</th>
                    <th>Grosor</th>
                    <th>Color</th>
                    <th>Superficie</th>
                    <th>Area</th>
                </tr>`;
    Listpiezas.forEach((el) => {
        let superficie=el.largo*el.ancho;
        let area=el.largo*el.ancho*el.grosor;
        let lista=document.createElement('tr');
        lista.innerHTML=`<td>${el.numpedido}</td>
            <td>${el.numpiezas}</td>
            <td>${el.largo}</td>
            <td>${el.ancho}</td>
            <td>${el.grosor}</td>
            <td>${el.color}</td>
            <td>${superficie}</td>
            <td>${area}</td>
            `
        tbody.appendChild(lista);
    });
}
function datosPedidos(){
    Listpedidos.sort((a,b)=>{return a.numpedido-b.numpedido;});
    let table=document.getElementById('tbl');
    let tbody=document.querySelector('tbody');
    if(!tbody){
        tbody=document.createElement('tbody');
        table.appendChild(tbody)
    }
    tbody.innerHTML=`<tr>
                    <th>Num. Pedido</th>
                    <th>Nombre Cliente</th>
                    <th>Apellido Cliente</th>
                    <th>Solicitado</th>
                    <th>Servido</th>
                    <th>Fecha del Pedido</th>
                </tr>`;
    Listpedidos.forEach((el) => {
        let lista=document.createElement('tr');
        lista.innerHTML=`<td>${el.numpedido}</td>
            <td>${el.cliente.nombre}</td>
            <td>${el.cliente.apellido}</td>
            <td>${el.procesado?'Si':'No'}</td>
            <td>${el.servido?'Si':'No'}</td>
            <td>${el.fechapedido}</td>
            `
        tbody.appendChild(lista);
    });
}
function datosPiezas(){
    Listpiezas.sort((a,b)=>{let nump=a.numpedido-b.numpedido; if(nump==0){return a.numpiezas-b.numpiezas}else{return nump}})
    let table=document.getElementById('tbl');
    let tbody=document.querySelector('tbody');
    if(!tbody){
        tbody=document.createElement('tbody');
        table.appendChild(tbody)
    }
    tbody.innerHTML=`<tr>
                    <th>Num. Pedido</th>
                    <th>Num. Pieza</th>
                    <th>Largo</th>
                    <th>Ancho</th>
                    <th>Grosor</th>
                    <th>Color</th>
                    <th>Ambas Caras</th>
                    <th>Cortada</th>
                </tr>`;
    Listpiezas.forEach((el) => {
        let lista=document.createElement('tr');
        lista.innerHTML=`<td>${el.numpedido}</td>
            <td>${el.numpiezas}</td>
            <td>${el.largo}</td>
            <td>${el.ancho}</td>
            <td>${el.grosor}</td>
            <td>${el.color}</td>
            <td>${el.ambascaras?"Si":"No"}</td>
            <td>${el.cortada?"Si":"No"}</td>
            `
        tbody.appendChild(lista);
    });
}
const cargaEventosPedidos = () => {
    datosPedidos();
    document.getElementById('darAltaPedido').addEventListener('click', darAltaPedido);
    document.getElementById('darBajaPedido').addEventListener('click', darBajaPedido);
    document.getElementById('modPedido').addEventListener('click', modificarPedido);
    document.getElementById('btnmodPed').addEventListener('click', pedForm);
}
const cargaEventosPiezas =()=>{
    datosPiezas();
    document.getElementById('darAltaPieza').addEventListener('click', darAltaPieza);
    document.getElementById('darBajaPieza').addEventListener('click', darBajaPieza);
    document.getElementById('modPieza').addEventListener('click', modificarPieza);
    document.getElementById('btnaltapiz').addEventListener('click', pedForm);
}

window.cargaEventosPedidos = cargaEventosPedidos;
window.cargaEventosPiezas = cargaEventosPiezas;
window.detallesPedidos= detallesPedidos;