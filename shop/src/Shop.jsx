import React from 'react'
import './Shop.css'
import { PRODUCTS } from './products'
import Product from './product'
const Shop = () => {
  return (
    <div>
        <div className="shop-title"><h1>Shop</h1></div>
        
    <div className="products">
        {PRODUCTS.map((product) => 
        
    <Product data={product}/> 
    )}
    </div>
    </div>
  )
}

export default Shop