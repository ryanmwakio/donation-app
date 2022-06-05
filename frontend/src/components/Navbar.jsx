import React, { useState } from 'react';
import { Link, NavLink } from 'react-router-dom';
import { HiOutlineMenuAlt3 } from 'react-icons/hi';
import { VscChromeClose } from 'react-icons/vsc';
import Fade from 'react-reveal/Fade';

const Navbar = () => {
  const [mobileMenu, setMobileMenu] = useState(false);

  const items = [
    { name: 'Home', to: '/' },
    { name: 'Register', to: '/register' },
    { name: 'Dashboard', to: '/dashboard' },
  ];

  return (
    <header className='z-50 py-4 bg-blue-500 navbar'>
      <div className='container lg:pb-0 text-lg text-white'>
        <div className='flex gap-x-4 justify-between items-center'>
          <Link to='/'>
            <div className='flex items-center'>
              <h1 className='text-xl text-white'>PESAPAL</h1>
            </div>
          </Link>

          {/*========== dynamic icon ==========*/}

          <button
            onClick={() => setMobileMenu(!mobileMenu)}
            className='lg:hidden p-2 border-2 border-green-500 focus:ring-4 ring-offset-1 ring-green-200 transition duration-500 rounded-lg'
          >
            {mobileMenu ? (
              <VscChromeClose className='text-xl' />
            ) : (
              <HiOutlineMenuAlt3 className='text-xl' />
            )}
          </button>

          <div className='hidden lg:flex items-center gap-x-5'>
            <nav className='flex items-center gap-x-5'>
              {items?.map((item, index) => (
                <NavLink
                  className={(info) =>
                    info.isActive
                      ? 'text-green-600 border-b border-white py-1 text-sm'
                      : 'text-sm'
                  }
                  key={index}
                  to={item?.to}
                >
                  <h1>{item?.name}</h1>
                </NavLink>
              ))}
            </nav>

            <div>
              <div className='flex gap-x-2'>
                <button className=' px-5 py-1 rounded-full border border-green-600 hover:bg-green-700 hover:text-green-700 focus:ring-1 text-white ring-white ring-offset-1 transition duration-500 text-sm'>
                  Sign In
                </button>

                {/*============ extra button ============*/}

                {/* <button className="font-semibold px-6 py-2 rounded-full bg-green-600 hover:bg-green-700 text-white focus:ring-4 ring-green-200 ring-offset-1 transition duration-500">Sign Up</button> */}
              </div>

              {/*============= user profile =============*/}

              {/* <div className="flex items-center gap-x-3">
                                <img width="50px" src="https://image.flaticon.com/icons/png/512/206/206853.png" alt="user" />
                                <h1>Jhon Doe</h1>
                                <button className="px-6 py-2 rounded-full bg-green-600 hover:bg-green-700 text-white focus:ring-4 ring-green-200 ring-offset-1 transition duration-500">Sign Out</button>
                            </div> */}
            </div>
          </div>
        </div>

        {/*============== mobile menu =============*/}

        {mobileMenu && (
          <Fade left>
            <div className='lg:hidden flex flex-col'>
              {items?.map((item, index) => (
                <NavLink
                  className={(info) =>
                    info.isActive
                      ? 'text-green-600 font-bold border-b-2 border-green-600'
                      : ''
                  }
                  key={index}
                  to={item?.to}
                >
                  <h1 className='px-2 py-2'>{item?.name}</h1>
                </NavLink>
              ))}

              <div>
                <div className='flex gap-x-2'>
                  <button className='font-semibold px-6 py-2 rounded-full border-2 border-green-600 hover:bg-green-700 hover:text-white focus:ring-4 ring-green-200 ring-offset-1 transition duration-500 mt-3 mb-5'>
                    Sign In
                  </button>

                  {/*============ extra button ============*/}

                  {/* <button className="font-semibold px-6 py-2 rounded-full bg-green-600 hover:bg-green-700 text-white focus:ring-4 ring-green-200 ring-offset-1 transition duration-500">Sign Up</button> */}
                </div>

                {/*============= user profile =============*/}

                {/* <div className="flex flex-wrap gap-y-4 items-center gap-x-3 mb-5">
    
                                        <img width="50px" src="https://image.flaticon.com/icons/png/512/206/206853.png" alt="user" />
                                        <h1>Jhon Doe</h1>
                                        <button className="px-6 py-2 rounded-full bg-green-600 hover:bg-green-700 text-white focus:ring-4 ring-green-200 ring-offset-1 transition duration-500">Sign Out</button>
    
                                    </div> */}
              </div>
            </div>
          </Fade>
        )}
      </div>
    </header>
  );
};

export default Navbar;
