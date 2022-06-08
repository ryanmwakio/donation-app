# DONATION APP

---

## _The easier way to make donations_

[![Build Status](https://travis-ci.org/joemccann/dillinger.svg?branch=master)](https://travis-ci.org/joemccann/dillinger)

Pesapal Donations is a web based interface as well as a restful api where donators can make payments easily through the pesapal payment platform,

- Backend made with Laravel
- Frontend made with React
- Running on a postgres database

1. [API](https://donation-app-254.herokuapp.com/)
2. [Frontend](https://donation-app-254.herokuapp.com/)

## Features

- Capture donor data through the plartform
- Make payment through pesapal
- Receive email notifications
- Receive links for payments in emails as well as next donation date if (monthly or annually)
- Admin panel to monitor all payments and details
- Task Scheduling for notification when donation date has reached

Donation app is created to easier payments of donations as all payments are tightly integrated to the Pesapal payments platform (the convenient way to make payments) with all the payments channels at the tip of your fingertips.
Developed by [Ryan Mwakio](https://ryanmwakio.netlify.app) to make the simple simpler.

> The overriding development goal for donation
> app was to make payments by donors easier through a well designed,
> modeled and thought of process

## Tech

Donation app uses a number of technologies to achieve the intended goals

- ReactJS - React is a free and open-source front-end JavaScript library for building user interfaces based on UI components.
- Laravel - Laravel is a free, open-source PHP web framework, created by Taylor Otwell and intended for the development of web applications following the model–view–controller architectural pattern
- Postgres - PostgreSQL, also known as Postgres, is a free and open-source relational database management system emphasizing extensibility and SQL compliance. It was originally named POSTGRES, referring to its origins as a successor to the Ingres database
- Postman - Postman is an API platform for building and using APIs. Postman simplifies each step of the API lifecycle and streamlines collaboration
- Pesapal - pay all your bills in bulk at a go in one transaction.
- Note that I opted for the inbuilt task scheduler instead of cron jobs for scheduling as the scheduler is tightly coupled with Laravel itself (e.g intergrating the models to get the stored schedules such that I managed to automate the email sending: this was one instance I used, but it can do more depending on your needs)
- Eloquent - an ORM developed for ease of use

## Installation

Donation App requires [PHP](https://www.php.net/) 7+, [Laravel](https://laravel.com/) 8 or 9, composer, [Node.js](https://nodejs.org/) v10+ to run.

Install the dependencies and devDependencies and start the server.

```sh
cd backend
php artisan serve
or php artisan serve --port=8001
php artisan:migrate
_________________________________________________________
cd frontend
npm i
npm start

```

For production environments...

```sh
npm install --production
NODE_ENV=production node app
```

## Plugins

Donation App is currently using the following plugins.
Instructions on how to use them in your own application are linked below.

| Plugin   | README                                                       |
| -------- | ------------------------------------------------------------ |
| Sendgrid | [https://sendgrid.com/](https://sendgrid.com/)               |
| Heroku   | [https://dashboard.heroku.com](https://dashboard.heroku.com) |

## Development

Want to contribute? Great!

Donation App uses Laravel and React for fast developing.
Make a change in your file and instantaneously see your updates!

Clone the project and run the installations necessary (php, database(note that eloquent being a proper ORM gives you a leeway to shift between relational database just by changing the driver and credentials in environment variables and if necessary in the config folder))

## License

MIT

**Free Software**
