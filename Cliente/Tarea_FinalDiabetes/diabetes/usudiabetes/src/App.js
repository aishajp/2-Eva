import React from 'react';
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import { ValidationProvider } from './context/ValidationContext';
import ListadoUsu from './components/ListadoUsu';
import UsuServer from './UsuServer';
import './App.css';

function App() {
  return (
    <ValidationProvider>
      <Router>
        <div className="App">
          <Routes>
            <Route path="/" element={<ListadoUsu />} />
            <Route path="/server" element={<UsuServer />} />
          </Routes>
        </div>
      </Router>
    </ValidationProvider>
  );
}

export default App;