import React from 'react'
import './Product.css'

const Product = (props) => {
    const {id, productName, price, productImage } = props.data;
  return (
    <div className="product">
        <h2>{productName}</h2>
        <img src={productImage} />
        <p><b>{price}</b></p>
        <button>Add to cart</button>
    </div>
  )
}

export default Product