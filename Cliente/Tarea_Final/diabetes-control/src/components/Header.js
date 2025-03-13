// Header.jsx
import React from 'react';
import { Link } from 'react-router-dom';

function Header() {
  return (
    <header className="header">
      <div className="container">
        <h1>Control de Diabetes</h1>
        <nav>
          <ul>
            <li><Link to="/">Lista de Usuarios</Link></li>
            <li><Link to="/users/new">Nuevo Usuario</Link></li>
          </ul>
        </nav>
      </div>
    </header>
  );
}

export default Header;