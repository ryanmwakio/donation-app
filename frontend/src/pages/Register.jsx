import React, { useState, useEffect } from 'react';
import Loading from './../components/Loading';
import { useNavigate } from 'react-router-dom';

import axios from '../api/axios';
import logo from '../assets/images/logo.png';
import classes from './Home.module.css';

import Alert from './../components/Alert';

function Register() {
  const navigate = useNavigate();
  const [loading, setLoading] = useState(false);
  const [isOpen, setIsOpen] = useState(false);
  const [alert, setAlert] = useState({
    type: '',
    message: '',
    url: '',
    urlDesc: '',
  });

  const handleRegister = async (event) => {
    try {
      event.preventDefault();

      setLoading(true);
      var data = JSON.stringify({
        name: event.target.name.value,
        email: event.target.email.value,
        password: event.target.password.value,
      });

      //handling sending of post request
      var response = await axios.post('/register', data);

      setLoading(false);
      setIsOpen(true);
      setAlert({
        type: 'success',
        message: 'Registration Successful',
        url: '',
        urlDesc: '',
      });

      event.target.reset();
      // store user data in local storage and token in session storage
      localStorage.setItem('user', JSON.stringify(response.data.data.user));
      sessionStorage.setItem('token', response.data.data.token);
      navigate('/dashboard');
    } catch (err) {
      setIsOpen(true);
      setAlert({
        type: 'error',
        message: err.message,
        url: '',
        urlDesc: '',
      });

      setLoading(false);
    }
  };

  useEffect(() => {
    // if (localStorage.getItem('user')) {
    //   navigate('/dashboard');
    // }
  }, []);

  return (
    <>
      <section className={classes.homeSection}>
        <div>
          <div className={classes.formSection}>
            <div>
              <img src={logo} alt='' className='mb-3' />
              <hr />
              <h1 className='title py-3'>Register</h1>
              <p className='title-text'>
                {' '}
                Register as admin to view and manage all the data.
              </p>
              {isOpen && (
                <Alert
                  type={alert.type}
                  message={alert.message}
                  url={alert.url}
                  urlDesc={alert.urlDesc}
                />
              )}

              <form onSubmit={handleRegister}>
                <div className='relative z-0 w-full mb-6 group'>
                  <input
                    type='text'
                    name='name'
                    id='floating_first_name'
                    className='block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b border-gray-300 appearance-none  dark:border-gray-300 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer'
                    placeholder=' '
                    required
                  />
                  <label className='peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6'>
                    Name
                  </label>
                </div>

                <div className='relative z-0 w-full mb-6 group'>
                  <input
                    type='email'
                    name='email'
                    className='block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b border-gray-300 appearance-none dark:border-gray-300 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer'
                    placeholder=' '
                    required
                  />
                  <label className='peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6'>
                    Email address
                  </label>
                </div>

                <div className='relative z-0 w-full mb-6 group'>
                  <input
                    type='password'
                    name='password'
                    id='floating_last_name'
                    className='block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b border-gray-300 appearance-none  dark:border-gray-300 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer'
                    placeholder=' '
                    required
                  />
                  <label className='peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6'>
                    Password
                  </label>
                </div>

                <button
                  type='submit'
                  className='text-white bg-blue-700 hover:bg-blue-800 focus:ring-1 focus:outline-none focus:ring-blue-300   text-sm w-full sm:w-auto px-9 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 font-light rounded-full shadow-lg'
                >
                  {loading ? <Loading /> : 'Register'}
                </button>
              </form>
            </div>
          </div>
        </div>
      </section>
    </>
  );
}

export default Register;
