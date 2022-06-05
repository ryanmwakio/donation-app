<!DOCTYPE html>
<html>
<head>

    <link rel="icon" href="{{secure_asset('/images/pesapal-logo.png')}}">
    <title>Pesapal Donation Pay</title>
    <link rel="stylesheet" href="{{secure_asset('/styles/styles.css')}}" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,200;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet"
    />

</head>
<body>
<div class="container">
    <div class="header">
        <img src="{{secure_asset('/images/pesapal-logo.png')}}" alt="Pesapal Logo" class="header-logo"/>
        <h1 class="mb-2">Pesapal Donation API</h1>
        <p>Welcome to Pesapal donation version 1.0.0</p>
    </div>

    <section class="section-wrapper">
        Base API url
        <span class="url-styling"
        >https://donation-app-254.herokuapp.com/api/v1</span
        >
    </section>

    <section class="section-wrapper">
        <h2 class="mb-2">Endpoints</h2>

        <div class="endpoint-wrapper">
            <h3>#1. Capture Donor Data</h3>

            <span class="url-styling">
            {{$app_url}}/api/v1/capture-donor-data
          </span>

            <span class="url-styling post text-success">POST</span>

            <span class="url-styling">
            <p class="pb-2">Expects name, email, phone, amount, period_of_donation</p>
            <p class="pb-2">
              name: required | email: optional | phone: optional |
              amount: required | period_of_donation: required
            </p>

            example:
                <pre>

{
    "name":"john",
    "email":"johndoe@gmail.com",
    "phone":"0712345678",
    "amount":"24000",
    "period_of_donation":"annual"
}
</pre>
          </span>

            <code class="success">
                <h6 class="code-response success">Sample success response</h6>
                <pre>
{
    "message": "details captured successfully. You can now donate using pesapal.",
    "code": 201,
    "status": "success",
    "data": {
        "donor_id": 9,
        "donor_name": "john",
        "donor_email": "johndoe@gmail.com",
        "donor_phone": "",
        "donor_amount": 24000,
        "donor_period_of_donation": "annual"
    }
}
                </pre>
                </code
            >

        </div>

        <div class="endpoint-wrapper">
            <h3>#2. Donate Via Pesapal</h3>

            <span class="url-styling">
            {{$app_url}}/api/v1/donate-via-pesapal/{id}
          </span>

            <span class="url-styling post text-primary" >PUT</span>

            <span class="url-styling">
            <p class="pb-2">Expects id (this is the donor id being received as response while capturing donor details)</p>
            <p class="pb-2">{id}: required</p>
            example: {{$app_url}}/api/v1/donate-via-pesapal/9</span
            >

            <code class="success">
                <h6 class="code-response success">Sample success response</h6>
             <pre>
    {
    "message": "details retrieved successfully.",
    "code": 200,
    "status": "success",
    "data": {
        "merchant_reference": "629b2724e37ed",
        "order_tracking_id": "e531f1a0-b6bb-499d-8de8-e00219bcd386",
        "redirect_url": "https://pay.pesapal.com/iframe/PesapalIframe3/Index/?OrderTrackingId=e531f1a0-b6bb-499d-8de8-e00219bcd386",
        "donor_id": 9,
        "donor_name": "john",
        "donor_email": "john@gmail.com",
        "donor_phone": "",
        "donor_amount": 24000,
        "donor_period_of_donation": "annual",
        "next_payment_date": "2023-06-04T14:58:27.492757Z"
    }
}
</pre>
</code>
        </div>


        <div class="endpoint-wrapper">
            <h3>#3. Register Admin</h3>

            <span class="url-styling">
            {{$app_url}}/api/v1/register
          </span>

            <span class="url-styling post text-success" >POST</span>

            <span class="url-styling">
            <p class="pb-2">Expects name,email, password</p>
            <p class="pb-2">name:required | email:required | password:required</p>
            </span
            >

            <code class="success">
                <h6 class="code-response success">Sample success response</h6>
                <pre>
   {
    "message": "User created successfully",
    "code": 201,
    "status": "success",
    "data": {
        "user": {
            "name": "john1",
            "email": "johndoe@gmail.com",
            "updated_at": "2022-06-04T18:42:57.000000Z",
            "created_at": "2022-06-04T18:42:57.000000Z",
            "id": 2
        },
        "token": "2|o9B235fyqkCCm5Mg4iKXoaH40w7UwLIKVxMdYr1q"
    }
}
</pre>
            </code>
        </div>



 <div class="endpoint-wrapper">
            <h3>#4. Login Admin</h3>

            <span class="url-styling">
            {{$app_url}}/api/v1/login
          </span>

            <span class="url-styling post text-success" >POST</span>

            <span class="url-styling">
            <p class="pb-2">Expects email, password</p>
            <p class="pb-2"> email:required | password:required</p>
            </span>

            <code class="success">
                <h6 class="code-response success">Sample success response</h6>
                <pre>
{
    "message": "Successfully logged in",
    "code": 200,
    "status": "success",
    "data": {
        "user": {
            "id": 1,
            "name": "john",
            "email": "johndoe6@gmail.com",
            "email_verified_at": null,
            "consumer_key": null,
            "consumer_secret": null,
            "created_at": "2022-06-04T18:28:35.000000Z",
            "updated_at": "2022-06-04T18:28:35.000000Z"
        },
        "token": "6|f3EoZ3IZgIL0gZ8JgzSzq9oQdWVV3iAZluK4BHwz"
    }
}
</pre>
            </code>
        </div>





 <div class="endpoint-wrapper">
            <h3>#5. Get All Donors</h3>

            <span class="url-styling">
            {{$app_url}}/api/v1/donors
          </span>

            <span class="url-styling post text-warning" >GET</span>

            <span class="url-styling">
            <p class="pb-2">Expects Authorization token on request header</p>
            <p class="pb-2"> Authorization Bearer token:required</p>
            </span>

            <code class="success">
                <h6 class="code-response success">Sample success response</h6>
                <pre>
{
    "message": "Donors retrieved successfully",
    "code": 200,
    "data": [
        {
            "id": 1,
            "name": "john",
            "email": "johndoe6@gmail.com",
            "phone": "",
            "amount": 1,
            "period_of_donation": "once",
            "last_donation_date": null,
            "next_payment_date": null,
            "merchant_reference": null,
            "redirect_url": null,
            "status": "pending",
            "order_tracking_id": null,
            "reminders_sent": 0,
            "created_at": "2022-06-04T17:39:56.000000Z",
            "updated_at": "2022-06-04T17:39:56.000000Z"
        },
        {
            "id": 2,
            "name": "john",
            "email": "johndoe6@gmail.com",
            "phone": "",
            "amount": 1,
            "period_of_donation": "annual",
            "last_donation_date": null,
            "next_payment_date": "2023-06-04 17:41:51",
            "merchant_reference": "629b995d486ba",
            "redirect_url": "https://pay.pesapal.com/iframe/PesapalIframe3/Index/?OrderTrackingId=191a13c5-8fcc-44a8-ac86-e002bc22d9ae",
            "status": "pending",
            "order_tracking_id": "191a13c5-8fcc-44a8-ac86-e002bc22d9ae",
            "reminders_sent": 1,
            "created_at": "2022-06-04T17:40:43.000000Z",
            "updated_at": "2022-06-04T17:41:51.000000Z"
        }
    ]
}
</pre>
            </code>
        </div>




 <div class="endpoint-wrapper">
            <h3>#6. Logout Admin</h3>

            <span class="url-styling">
            {{$app_url}}/api/v1/logout
          </span>

            <span class="url-styling post text-success" >POST</span>

            <span class="url-styling">
            <p class="pb-2">Expects Authorization token on request header</p>
            <p class="pb-2"> Authorization Bearer token:required</p>
            </span>

            <code class="success">
                <h6 class="code-response success">Sample success response</h6>
                <pre>
{
    "message": "Successfully logged out",
    "code": 200,
    "status": "success",
    "data": []
}
</pre>
            </code>
        </div>





<div class="endpoint-wrapper">
            <h3>#7. Update Admin</h3>

            <span class="url-styling">
            {{$app_url}}/api/v1/logout
          </span>

            <span class="url-styling post text-primary" >PUT</span>

            <span class="url-styling">
            <p class="pb-2">Expects Authorization token, consumer_key, consumer_secret</p>
            <p class="pb-2"> Authorization token, consumer_key:string:required | consumer_secret:string:required</p>
            </span>

            <code class="success">
                <h6 class="code-response success">Sample success response</h6>
                <pre>
{
    "message": "User updated successfully",
    "code": 200,
    "status": "success",
    "data": {
        "user": {
            "id": 1,
            "name": "john",
            "email": "johndoe6@gmail.com",
            "email_verified_at": null,
            "consumer_key": "hbbhbdgcdgvg6t6",
            "consumer_secret": "jnjncd565646",
            "created_at": "2022-06-04T18:28:35.000000Z",
            "updated_at": "2022-06-04T21:18:10.000000Z"
        }
    }
}
</pre>
            </code>
        </div>






<div class="endpoint-wrapper">
            <h3>#8. Delete Admin</h3>

            <span class="url-styling">
            {{$app_url}}/api/v1/admin
          </span>

            <span class="url-styling post text-danger" >DELETE</span>

            <span class="url-styling">
            <p class="pb-2">Expects Authorization token</p>
            <p class="pb-2"> Authorization token:required</p>
            </span>

            <code class="success">
                <h6 class="code-response success">Sample success response</h6>
                <pre>
{
    "message": "User deleted successfully",
    "code": 200,
    "status": "success",
    "data": []
}
</pre>
            </code>
        </div>





    </section>
</div>
</body>
</html>
