<?php

namespace App\Http\Controllers;


use Exception;
use GuzzleHttp\Client;


use App\Models\Donor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;


class DonorController extends Controller
{

    /**
     * Get all donors.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $donors = Donor::all();

        // check if there are donors
        if ($donors->isEmpty()) {
            return response()->json([
                'code' => ResponseAlias::HTTP_NOT_FOUND,
                'message' => 'No donors found',
                'data' => [],
            ], 404);
        }

        // return a json response
        return response()->json([

            'message' => 'Donors retrieved successfully',
            'code' => 200,
            'data' => $donors
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        // validate request values from input
        $this->validate($request, [
            'name' => 'required',
            'amount' => 'required|numeric',
            'period_of_donation' => 'required|string',
        ]);

        // check if both email and phone are empty
        if (empty($request->email) && empty($request->phone)) {
            return response()->json([
                'message' => 'Either email or phone should be provided.',
                'code' => 422,
                'status' => 'error',
            ], 422);
        }

        // check that either the email or phone is not empty
        if (empty($request->email)) {
            // check that the phone is valid
            if (!preg_match('/^[0-9]{10}$/', $request->phone)) {
                return response()->json([
                    'message' => 'Phone number given is invalid.',
                    'code' => 422,
                    'status' => 'error',
                ], 422);
            }
            $email = '';
            $phone = $request->phone;
        } else {
            // check that the email is valid
            if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
                return response()->json([
                    'message' => 'Email is invalid.',
                    'code' => 422,
                    'status' => 'error',
                ], 422);
            }
            $email = $request->email;
            $phone = '';
        }

        // Retrieve the remaining donor data from the request
        $name = $request->input('name');
        $amount = $request->input('amount');
        $period_of_donation = $request->input('period_of_donation');

        // Create a new donor
        $donor = new Donor();
        $donor->name = $name;
        $donor->email = $email;
        $donor->phone = $phone;
        $donor->amount = (int)$amount;
        $donor->period_of_donation = $period_of_donation;
        $donor->last_donation_date = null;
        $donor->next_payment_date = null;
        $donor->merchant_reference = null;
        $donor->redirect_url = null;
        $donor->status = 'pending';
        $donor->order_tracking_id = null;
        $donor->reminders_sent = 0;
        $donor->save();

        // return a success response
        if ($donor->id) {

            // return a success response
            return response()->json([
                'message' => 'details captured successfully. You can now donate using pesapal.',
                'code' => 201,
                'status' => 'success',
                "data" => [
                    "donor_id" => $donor->id,
                    "donor_name" => $donor->name,
                    "donor_email" => $donor->email,
                    "donor_phone" => $donor->phone,
                    "donor_amount" => $donor->amount,
                    "donor_period_of_donation" => $donor->period_of_donation,
                    "next_payment_date" => $donor->next_payment_date,
                ]
            ], 201);
        } else {
            // return an error response
            return response()->json([
                'message' => 'details could not be captured hence you cannot donate using pesapal.',
                'code' => 422,
                'status' => 'error',
            ], 422);
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        //get client secret and client id from form input if it exists
        $consumer_key = $request->input('pesapal_consumer_key');
        $consumer_secret = $request->input('pesapal_consumer_secret');

        try {
            // get id from url parameter and not from input
            $donor_id = $request->route('id');

            //display the donor details
            $donor = Donor::find($donor_id);

            if ($donor) {
                // process pesapal payment
                // 1) Authentication
                $client = new Client();
                $headers = [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ];

                $token_response = $client->post('https://pay.pesapal.com/v3/api/Auth/RequestToken', [
                    'headers' => $headers,
                    'json' => [
                        'consumer_key' => $consumer_key?:env('PESAPAL_CONSUMER_KEY'),
                        'consumer_secret' => $consumer_secret?:env('PESAPAL_CONSUMER_SECRET'),
                    ]
                ]);
                $pesapal_token = json_decode($token_response->getBody()->getContents())->token;

                $header_with_token = [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . $pesapal_token
                ];

                // 2)Get ipn list or Register ipn if none is available
                $ipn_list_response = $client->get('https://pay.pesapal.com/v3/api/URLSetup/GetIpnList', [
                    'headers' => $header_with_token,
                ]);
                $ipn_list = json_decode($ipn_list_response->getBody()->getContents());
                //get first item
                $ipn_id = $ipn_list[0]->ipn_id;
                //if ipn list length is 0 then register an ipn
                if (count($ipn_list) == 0) {
                    $ipn_response = $client->post('https://pay.pesapal.com/v3/api/URLSetup/RegisterIPN', [
                        'headers' => $header_with_token,
                        'json' => [
                            'url' => env('PESAPAL_IPN_URL'),
                            'ipn_notification_type'=>'GET'
                        ]
                    ]);
                    $ipn_id = json_decode($ipn_response->getBody()->getContents())->ipn_id;
                }

                // 3) submit order request to pesapal
                $unique_id = uniqid();
                $order_request_response = $client->post('https://pay.pesapal.com/v3/api/Transactions/SubmitOrderRequest', [
                    'headers' => $header_with_token,
                    'json' => [
                        "id"=> $unique_id,
                        "currency"=> "KES",
                        "amount"=> $donor->amount,
                        "description"=> "Donation payment for " . $donor->name,
                        "callback_url"=> env('PESAPAL_CALLBACK_URL'),
                        "notification_id"=> $ipn_id,
                        "billing_address"=> [
                            "email_address"=> $donor->email,
                            "phone_number"=> $donor->phone,
                            "country_code"=> "KE",
                            "first_name"=> $donor->name,
                            "middle_name"=> "",
                            "last_name"=> "",
                            "line_1"=> "",
                            "line_2"=> "",
                            "city"=> "",
                            "state"=> "",
                            "postal_code"=> null,
                             "zip_code"=> null
                        ],
                    ]
                ]);
                $order_request = json_decode($order_request_response->getBody()->getContents());
                $order_tracking_id = $order_request->order_tracking_id;
                $merchant_reference=$order_request->merchant_reference;
                $redirect_url = $order_request->redirect_url;

             if($donor->period_of_donation == 'monthly' || $donor->period_of_donation == 'annual') {
                 $email = new \SendGrid\Mail\Mail();
                 $email->setFrom("ryanmwakio6@gmail.com", "Pesapal Ryan");
                 $email->setSubject(ucfirst($donor->period_of_donation) . " donation payment for " . $donor->name . " set up successfully");
                 $email->addTo($donor->email, $donor->name);
                 $email->addContent("text/plain", "Thank you for choosing to donate,You can now make payment using pesapal.");
                 $email->addContent(
                     "text/html", "<h1>Thank you for donating.</h1><br><style>h1{color:red;}</style><p>You can now make payment using pesapal <a href=".$redirect_url." style='color: #0072b3'>pesapal payment link</a></p><hr><img src='https://res.cloudinary.com/dwxeqcqt0/image/upload/v1654351754/imageedit_1_2429230768_tg9nee.png'/>"
                 );
                 $sendgrid = new \SendGrid(env('SENDGRID_API_KEY'));
                 try {
                     $response = $sendgrid->send($email);
                     $email_response_code = $response->statusCode() . "\n";
//                    print_r($response->headers());
//                    print $response->body() . "\n";
                 } catch (Exception $e) {
                     echo 'Caught exception: ' . $e->getMessage() . "\n";
                 }

                 // update the donor details in db next_payment_date
                 $donor->period_of_donation == 'monthly' ? $donor->next_payment_date = Carbon::now()->addMonth() : $donor->next_payment_date = Carbon::now()->addYear();
                    $donor->save();
                 $donor->reminders_sent=$donor->reminders_sent+1;
             }else{
                 // that is one time donation
                 $donor->next_payment_date = null;
                 $donor->reminders_sent=0;
                    $donor->save();
             }
             $donor->order_tracking_id = $order_tracking_id;
             $donor->merchant_reference = $merchant_reference;
             $donor->redirect_url = $redirect_url;
             $donor->status='pending';

             $donor->save();


                // return a success response
                return response()->json([
                    'message' => 'details retrieved successfully.',
                    'code' => 200,
                    'status' => 'success',
                    "data" => [
                        "merchant_reference"=>$merchant_reference,
                        "order_tracking_id" => $order_tracking_id,
                        "redirect_url" => $redirect_url,
                        "donor_id" => $donor->id,
                        "donor_name" => $donor->name,
                        "donor_email" => $donor->email,
                        "donor_phone" => $donor->phone,
                        "donor_amount" => $donor->amount,
                        "donor_period_of_donation" => $donor->period_of_donation,
                        "next_payment_date" => $donor->next_payment_date,
                        "reminders_sent" => $donor->reminders_sent,
                    ]
                ], 200);

            } else {
                // return an error response
                return response()->json([
                    'message' => 'user by this id does not exist.',
                    'code' => 422,
                    'status' => 'error',
                ], 422);
            }

        } catch (NotFoundExceptionInterface|ContainerExceptionInterface $e) {
            return response()->json([
                'message' => 'An error occurred while donating. please try again.',
                'code' => 422,
                'status' => 'error',
            ], 422);
        }


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    public function ipn(){
        return response()->json([
            'message' => 'ipn',
            'code' => 200,
            'status' => 'success',
        ], 200);
    }

    public function processPayment(){
        return response()->json([
            'message' => 'callback',
            'code' => 200,
            'status' => 'success',
        ], 200);
    }
}
