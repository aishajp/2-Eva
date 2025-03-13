import React from 'react';
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import { ValidationProvider } from './context/ValidationContext';
import Header from './components/Header';
import Footer from './components/Footer';
import UserList from './components/User/UserList';
import UserForm from './components/User/UserForm';
import UserEdit from './components/User/UserEdit';
import UserStats from './components/Statistics/UserStats';
import './App.css';

function App() {
  return (
    <ValidationProvider>
      <Router>
        <div className="App">
          <Header />
          <main className="container">
            <Routes>
              <Route path="/" element={<UserList />} />
              <Route path="/users/new" element={<UserForm />} />
              <Route path="/users/edit/:username" element={<UserEdit />} />
              <Route path="/users/stats/:username" element={<UserStats />} />
            </Routes>
          </main>
          <Footer />
        </div>
      </Router>
    </ValidationProvider>
  );
}

export default App;