import logo from './logo.svg';
import './App.css';
import Encabezado from './components/Encabezado';
import Pie from './components/Pie';
import { AppContext, valoresDefecto } from './AppContext';
function App() {
return (
<div className="App">
<AppContext.Provider value={valoresDefecto}>
<Encabezado />
<div>Esto simplemente es contenido.</div>
<Pie />
</AppContext.Provider>
</div>
);
}


/*function App() {
  return (
    <div className="App">
      <header className="App-header">
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
}*/

export default App;
