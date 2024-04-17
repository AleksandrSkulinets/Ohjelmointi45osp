import React , {useState} from 'react'
import IconClose from './CloseIcon';
import IconHamburger from './HamburgerIcon';

const Nav = () => {
 const [nav , setNav] = useState(false);
 const handleClick = () => setNav(!nav);

  return (
    <div className='fixed top-0 h-[100px] w-full bg-black'>
        <div className='flex m-auto max-w-[1000px] h-full items-center justify-between'>
           <div className='z-10'>
            <h1 className='text-white font-bold'>Logo</h1>
           </div>
            
            {/* menu */}
         
            <ul className='md:flex md:mx-4 hidden'>
                <li className='text-white mx-2'>home</li>
                <li className='text-white mx-2'>some</li>
                <li className='text-white mx-2'>contacts</li>
                <li className='text-white mx-2' >about</li>
            </ul>
            
            {/* menu icon*/}
            <div  onClick={handleClick}  className='md:hidden z-10'  >
            {!nav ? <IconHamburger /> : <IconClose />}
            </div>
            
            {/* menu mobile*/}
           
                <ul className={!nav ? 'hidden': 'absolute top-0 left-0 w-full h-screen bg-black flex flex-col items-center justify-center'}>
                <li className='text-white py-4'>home</li>
                <li className='text-white py-4'>some</li>
                <li className='text-white py-4'>contacts</li>
                <li className='text-white py-4' >about</li>
            </ul>
            

        </div>
    </div>
  )
}

export default Nav