import React from 'react'
import {BrowserRouter as Router, Routes, Route} from 'react-router-dom';
import  Navbar  from './components/Navbar';
import Cart from './Cart';
import Shop from './Shop';

const App = () => {
  return (
    <div>
        <Router>
            <Navbar />
            <Routes>
                <Route path="/" element={<Shop />} />
                <Route path="/cart" element={<Cart />} />
            </Routes>
        </Router>
      
    </div>
  )
}

export default App