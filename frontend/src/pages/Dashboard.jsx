import React, { useEffect, useState } from 'react';
import classes from './Home.module.css';
import axios from '../api/axios';
import Loading from './../components/Loading';
import { useNavigate } from 'react-router-dom';
import Alert from './../components/Alert';

function Dashboard() {
  const [loggedInUser, setLoggedInUser] = useState(null);
  const [loading, setLoading] = useState(false);
  const navigate = useNavigate();
  const [isOpen, setIsOpen] = useState(false);
  const [alert, setAlert] = useState({
    type: '',
    message: '',
    url: '',
    urlDesc: '',
  });
  const [allDonors, setAllDonors] = useState([]);

  const handleLogout = async (event) => {
    event.preventDefault();

    try {
      setLoading(true);
      //get token from session storage
      const token = sessionStorage.getItem('token');

      //handling sending of post request
      await axios.post(
        '/logout',
        {},
        {
          headers: {
            Authorization: 'Bearer ' + token,
          },
        }
      );
      setLoading(false);
      sessionStorage.removeItem('token');
      sessionStorage.removeItem('user');

      setAlert({
        type: 'success',
        message: 'Logout Successful',
        url: '',
        urlDesc: '',
      });
      navigate('/');
    } catch (err) {
      setLoading(false);
    }
  };

  const fetchData = async () => {
    try {
      setLoading(true);
      //get token from session storage
      const token = sessionStorage.getItem('token');

      //handling sending of post request
      const response = await axios.get('/donors', {
        headers: {
          Authorization: 'Bearer ' + token,
        },
      });
      setAllDonors(response.data.data);
      setLoading(false);
    } catch (err) {
      setLoading(false);
      console.log(err);

      setAlert({
        type: 'error',
        message: err.message,
        url: '',
        urlDesc: '',
      });
    }
  };

  useEffect(() => {
    //check if user is logged in
    if (!sessionStorage.getItem('token')) {
      navigate('/');
    }

    //get user data from local storage
    const user = JSON.parse(localStorage.getItem('user'));
    setLoggedInUser(user);

    fetchData();
  }, []);

  return (
    <div className='container'>
      <div>
        <h1 className='text-blue-500 font-bold text-xl '>Dashboard</h1>
        {isOpen && (
          <Alert
            type={alert.type}
            message={alert.message}
            url={alert.url}
            urlDesc={alert.urlDesc}
          />
        )}
        <form onSubmit={handleLogout}></form>
        <input
          type='submit'
          value='logout'
          className='text-xs text-blue-500 cursor-pointer py-1 my-1 px-4 bg-blue-100'
        />
        <hr />

        <div className='flex flex-col'>
          <h1 className='text-blue-500 font-semibold text-sm '>Donors</h1>
          {loading ? (
            <Loading />
          ) : (
            <div>
              <div className='flex flex-col'>
                <div className='overflow-x-auto sm:-mx-6 lg:-mx-8'>
                  <div className='py-2 inline-block min-w-full sm:px-6 lg:px-8'>
                    <div className='overflow-hidden'>
                      <table className='min-w-full'>
                        <thead className='bg-white border-b'>
                          <tr>
                            <th
                              scope='col'
                              className='text-sm font-medium text-gray-900 px-6 py-4 text-left'
                            >
                              #
                            </th>
                            <th
                              scope='col'
                              className='text-sm font-medium text-gray-900 px-6 py-4 text-left'
                            >
                              Name
                            </th>
                            <th
                              scope='col'
                              className='text-sm font-medium text-gray-900 px-6 py-4 text-left'
                            >
                              Email
                            </th>
                            <th
                              scope='col'
                              className='text-sm font-medium text-gray-900 px-6 py-4 text-left'
                            >
                              Merchant Reference
                            </th>
                            <th
                              scope='col'
                              className='text-sm font-medium text-gray-900 px-6 py-4 text-left'
                            >
                              Period
                            </th>
                            <th
                              scope='col'
                              className='text-sm font-medium text-gray-900 px-6 py-4 text-left'
                            >
                              Next Payment Date
                            </th>
                            <th
                              scope='col'
                              className='text-sm font-medium text-gray-900 px-6 py-4 text-left'
                            >
                              Reminders Sent
                            </th>
                            <th
                              scope='col'
                              className='text-sm font-medium text-gray-900 px-6 py-4 text-left'
                            >
                              Order Tracking ID
                            </th>
                          </tr>
                        </thead>
                        <tbody>
                          {allDonors.map((donor, index) => (
                            <tr
                              key={index}
                              className='bg-white border-b transition duration-300 ease-in-out hover:bg-gray-100'
                            >
                              <td className='px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900'>
                                {donor.id}
                              </td>
                              <td className='text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap'>
                                {donor.name}
                              </td>
                              <td className='text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap'>
                                {donor.email}
                              </td>
                              <td className='text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap'>
                                {donor.merchant_reference
                                  ? donor.merchant_reference
                                  : '-'}
                              </td>
                              <td className='text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap'>
                                {donor.period_of_donation}
                              </td>
                              <td className='text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap'>
                                {donor.next_payment_date
                                  ? donor.next_payment_date
                                  : 'N/A'}
                              </td>
                              <td className='text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap'>
                                {donor.reminders_sent}
                              </td>
                              <td className='text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap'>
                                {donor.order_tracking_id
                                  ? donor.order_tracking_id
                                  : '-'}
                              </td>
                            </tr>
                          ))}
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          )}
        </div>
      </div>
    </div>
  );
}

export default Dashboard;
