const inicializar = () => 
    document.getElementById('enviar')
.addEventListener('click', validar, false);
const validaElemento = (nombre) => {
    let elemento = document.getElementById(nombre);
    if (!elemento.checkValidity()){
    error(elemento);
    return false;
    }
    return true;
    };
    const error = (elemento) => {
        document.getElementById('mensajeError').innerHTML = elemento.validationMessage;
        elemento.className ="error";
        elemento.focus();
        };
        const borrarError = () => {
        let formulario = document.forms[0];
        formulario.elements.forEach((e) => e.className="");
        };
        const validar = (e) => {
            if (validaElemento("nombre") && validaElemento("edad") && confirm("Pulsa enviar si quieres mandar el formulario"))
            return true;
            else{
            e.preventDefault();
            return false;
            }
            };
            
    
    