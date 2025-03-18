// Footer.jsx
import React from 'react';

function Footer() {
  return (
    <footer className="footer">
      <div className="container">
        <p>© {new Date().getFullYear()} - Aplicación de Control de Diabetes</p>
      </div>
    </footer>
  );
}

export default Footer;