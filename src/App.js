import logo from './logo.svg';
import './App.css';
import Visor from './Visor';
function App() {
  const imagenesSrc = [
    "imagen1.jpg",
    "imagen2.jpg",
    "imagen3.jpg",
    "imagen4.jpg",
    "imagen5.jpg",
    "imagen6.jpg"
  ];
  return (
    <div className="App">
      <header className="App-header">
      <Visor imagenes={imagenesSrc} />
        <img src={logo} className="App-logo" alt="logo" />
        <p>
          Edit <code>src/App.js</code> and save to reload.
        </p>
        <a
          className="App-link"
          href="https://reactjs.org"
          target="_blank"
          rel="noopener noreferrer"
        >
          Learn React
        </a>
      </header>
    </div>
  );
}

export default App;
