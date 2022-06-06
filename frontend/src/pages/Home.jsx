import React, { useState } from 'react';
import classes from './Home.module.css';
import logo from '../assets/images/logo.png';
import Loading from './../components/Loading';
import axios from '../api/axios';

import Alert from './../components/Alert';

function Home() {
  const [loading, setLoading] = useState(false);
  const [isOpen, setIsOpen] = useState(false);
  const [alert, setAlert] = useState({
    type: '',
    message: '',
    url: '',
    urlDesc: '',
  });

  const handleCaptureDonorData = async (event) => {
    try {
      event.preventDefault();

      setLoading(true);
      var data = JSON.stringify({
        name: event.target.name.value,
        email: event.target.email.value,
        phone: event.target.phone.value,
        amount: event.target.amount.value,
        period_of_donation: event.target.period_of_donation.value,
      });

      //handling sending of post request
      var response1 = await axios.post('/capture-donor-data', data);
      var donor_id = response1.data.data.donor_id;

      var response2 = await axios.put(`/donate-via-pesapal/${donor_id}`);

      setLoading(false);
      var payment_url = response2.data.data.redirect_url;

      setIsOpen(true);
      setAlert({
        type: 'success',
        message: 'Data Processed Successfully',
        url: payment_url,
        urlDesc: 'Your Payment Link',
      });

      event.target.reset();
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

  return (
    <>
      <section className={classes.homeSection}>
        <div>
          <div className={classes.formSection}>
            <div>
              <img src={logo} alt='' className='mb-3' />
              <hr />
              <h1 className='title py-3'>Make Donations With Ease</h1>
              <p className='title-text'>
                {' '}
                To make donations fill in the details below and submit
              </p>
              {isOpen && (
                <Alert
                  type={alert.type}
                  message={alert.message}
                  url={alert.url}
                  urlDesc={alert.urlDesc}
                />
              )}

              <form onSubmit={handleCaptureDonorData}>
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
                    type='number'
                    pattern='[0-9]{3}-[0-9]{3}-[0-9]{4}'
                    name='phone'
                    id='floating_phone'
                    className='block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b border-gray-300 appearance-none dark:border-gray-300 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer'
                    placeholder=' '
                    required
                  />
                  <label className='peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6'>
                    Phone number (254712345678)
                  </label>
                </div>

                <div className='relative z-0 w-full mb-6 group'>
                  <input
                    type='number'
                    name='amount'
                    id='floating_last_name'
                    className='block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b border-gray-300 appearance-none  dark:border-gray-300 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer'
                    placeholder=' '
                    required
                  />
                  <label className='peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6'>
                    Amount
                  </label>
                </div>

                <div className='relative z-0 w-full mb-6 group'>
                  <select
                    name='period_of_donation'
                    id='floating_period'
                    className='block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b border-gray-300 appearance-none  dark:border-gray-300 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer'
                  >
                    <option value='once'>Once</option>
                    <option value='monthly'>Monthly</option>
                    <option value='annual'>Annual</option>
                  </select>
                  <label className='peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6'>
                    Period of donation
                  </label>
                </div>

                <button
                  type='submit'
                  className='text-white bg-blue-700 hover:bg-blue-800 focus:ring-1 focus:outline-none focus:ring-blue-300   text-sm w-full sm:w-auto px-9 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 font-light rounded-full shadow-lg'
                >
                  {loading ? <Loading /> : 'Submit'}
                </button>
              </form>
            </div>
          </div>
        </div>
      </section>
    </>
  );
}

export default Home;
