import PropTypes from "prop-types";
const Saludo = ({ nombre }) => <p>Hola, {nombre}</p>;
// Se definen las propTypes
Saludo.propTypes = {
nombre: PropTypes.string.isRequired, // La prop nombre debe ser una cadena de texto y es obligatoria
};
export default Saludo;