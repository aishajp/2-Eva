<<<<<<< HEAD
import logo from './logo.svg';
import './App.css';
import Acercade from './components/Acercade.js';
import Bucle from './components/Bucle.js';
import Saludar from './components/Saludar.js';

function App() {
  const nombre = 'Jordy';
  return (
    <div className="App">
      <header className="App-header">
        <img src={logo} className="App-logo" alt="logo" />
        <a className="App-link" href="https://reactjs.org" target="_blank" rel="noopener noreferrer">
          Learn React
        </a>
        <h1>Hola, {nombre}</h1>
        <Bucle />
        <Saludar nombre="Jordy" edad="21"/>
      </header>
      <Acercade />
    </div>
  );
}

=======
import logo from './logo.svg';
import './App.css';
import Acercade from './components/Acercade.js';
/* import Bucle from './components/Bucle.js';
import Saludar from './components/Saludar.js';
import EjemploEstado from './components/State/EjemploState.js'; */
import EjemploState3 from './components/State/EjemploState3.js';
import Visor from './components/visor.js';

function App() {
  const imgSrc=new Array("hllwn-dog.jfif","hllwn-main.jpg","hllwn-pumpking.jfif");

  const nombre = 'Jordy';
  return (
    <div className="App">
      <header className="App-header">
        <img src={logo} className="App-logo" alt="logo" />
        {/* <a className="App-link" href="https://reactjs.org" target="_blank" rel="noopener noreferrer">
          Learn React
        </a> */}
        {/* <h1>Hola, {nombre}</h1> */}
        {/* <Bucle /> */}
        {/* <Saludar nombre="Jordy" edad="21"/> */}
        {/* <EjemploEstado/> */}
        {/* <EjemploState3/> */}
      <Visor img={imgSrc}/>
      </header>
      <Acercade />
    </div>
  );
}

>>>>>>> 41fbc4fa1ae8487cf736b30aaea46a3ff47bb59b
export default App;