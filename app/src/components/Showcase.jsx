import React, { useState, useEffect } from 'react';
import axios from 'axios';

function ProductList() {
  const [products, setProducts] = useState([]);
  const [cart, setCart] = useState([]);

  useEffect(() => {
    // Fetch products from backend API
    axios.get('http://localhost:3001/api/data')
      .then(response => {
        setProducts(response.data);
      })
      .catch(error => {
        console.error('Error fetching products: ', error);
      });
  }, []); 
    // add to cart example
  const addToCart = (product) => {
    setCart([...cart, product]);
  };

  return (
    <div className='flex w-full sm:w-[1300px] items-center justify-center m-auto'>
      <div className='h-screen w-full mt-32'>
        <h1 className="text-center text-3xl font-bold mb-8">Products</h1>
        <div className='flex flex-wrap justify-center'>
          {products.map(product => (
            <div className='w-96 h-96 bg-white shadow-lg rounded-lg overflow-hidden m-4' key={product.ProductID}>
              <img className="w-full h-40 object-cover" src={product.ImageURL} alt={product.ProductName} />
              <div className="p-6">
                <h2 className="text-xl font-bold mb-2">{product.ProductName}</h2>
                <p className="text-gray-700 mb-4">{product.Description}</p>
                <p className="text-gray-900 font-bold">${product.Price}</p>
                <button onClick={() => addToCart(product)} className="bg-teal-500 hover:bg-teal-300 text-white font-bold py-2 px-2 rounded-xl mt-4">Add to Cart</button>
              </div>
            </div>
          ))}
        </div>
      </div>
      <div className="fixed top-20 right-0 bg-black p-4 shadow-md">
        <h2 className="text-lg text-white font-bold mb-2">Cart</h2>
        <ul className='text-white'>
          {cart.map(item => (
            <li key={item.ProductID}>
              <p>{item.ProductName} - ${item.Price}</p>
            </li>
          ))}
        </ul>
      </div>
    </div>
  );
}

export default ProductList;
