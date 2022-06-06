import React from 'react';

function Alert(props) {
  var alert = '';

  if (props.type === 'success') {
    alert = (
      <div
        className='bg-green-100 border-l-4 border-green-500 text-green-700 p-4 my-2'
        role='alert'
      >
        <p className='font-bold text-sm'>Success</p>
        <p className='text-xs'>{props.message}</p>
        <a
          href={props.url ? props.url : '#'}
          target='_blank'
          rel='noopener noreferrer'
          className='text-blue-500 hover:text-blue-700 text-xs'
        >
          {props.urlDesc ? props.urlDesc : ''}
        </a>
      </div>
    );
  } else if (props.type === 'error') {
    alert = (
      <div
        className='bg-red-100 border-l-4 border-red-500 text-red-700 p-4 my-2'
        role='alert'
      >
        <p className='font-bold text-sm'>Error</p>
        <p className='text-xs'>{props.message}</p>
        <a
          href={props.url ? props.url : '#'}
          target='_blank'
          rel='noopener noreferrer'
          className='text-blue-500 hover:text-blue-700 text-xs'
        >
          {props.urlDesc ? props.urlDesc : ''}
        </a>
      </div>
    );
  } else if (props.type === 'warning') {
    alert = (
      <div
        className='bg-orange-100 border-l-4 border-orange-500 text-orange-700 p-4 my-2'
        role='alert'
      >
        <p className='font-bold text-sm'>Warning</p>
        <p className='text-xs'>{props.message}</p>
        <a
          href={props.url ? props.url : '#'}
          target='_blank'
          rel='noopener noreferrer'
          className='text-blue-500 hover:text-blue-700 text-xs'
        >
          {props.urlDesc ? props.urlDesc : ''}
        </a>
      </div>
    );
  } else if (props.type === 'info') {
    alert = (
      <div
        className='bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 my-2'
        role='alert'
      >
        <p className='font-bold text-sm'>Info</p>
        <p className='text-xs'>{props.message}</p>
        <a
          href={props.url ? props.url : '#'}
          target='_blank'
          rel='noopener noreferrer'
          className='text-blue-500 hover:text-blue-700 text-xs'
        >
          {props.urlDesc ? props.urlDesc : ''}
        </a>
      </div>
    );
  }

  return <>{alert}</>;
}

export default Alert;
