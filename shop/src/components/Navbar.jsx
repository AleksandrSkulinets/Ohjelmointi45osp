import React from 'react'
import './Navbar.css'
import { Link } from 'react-router-dom'

const Navbar = () => {
  return (
    <div className="navbar">
        <div className="logo"><h1>Shop logo</h1></div>
        <div className="links">
        <Link to="/">Shop</Link>
        <Link to="/cart">Cart</Link>
        </div>
        
    </div>
  )
}

export default Navbar