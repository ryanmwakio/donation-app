<?php


use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DonorController;
use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
| Developed by: @Ryan M.
|
*/

// Path: routes for api version 1
Route::prefix('v1')->group(function () {
    /**
     * Path: /api/v1/get-csrf-token
     * Method: GET
     * Description: Get to the root of the api
     * Accept : application/json
     * Content-Type : application/json
     *
     */
    Route::get('/get-csrf-token', function () {
        $token = csrf_token();
        return response()->json(['csrf_token' => $token]);
    });


    /**
     * Path: /api/v1/
     * Method: GET
     * Description: Get to the root of the api
     * Accept : application/json
     * Content-Type : application/json
     *
     */
    Route::get('/', HomeController::class.'@index')->name('api.v1.index');

    /**
     * Path: /api/v1/capture-donor-data
     * Method: POST
     * Description: Post the donor data from the form / testing client to the database
     * Accept : application/json
     * Content-Type : application/json
     *
     */
    Route::post('/capture-donor-data', DonorController::class . '@store');

    /**
     * Path: /api/v1/donate-via-pesapal/{donor_id}
     * Method: PUT
     * Description: enable a specific donor stored in database, to donate via pesapal
     * Accept : application/json
     * Content-Type : application/json
     *
     */
    Route::put('/donate-via-pesapal/{id}', DonorController::class . '@update');

    /**
     * Path: /api/v1/ipn
     * Method: GET
     * Description: callback url for pesapal payment
     * Accept : application/json
     * Content-Type : application/json
     *
     */
    Route::get('/ipn', DonorController::class . '@ipn');

    /**
     * Path: /api/v1/register
     * Method: POST
     * Description: register admin
     * Accept : application/json
     * Content-Type : application/json
     *
     */
    Route::post('/register', AuthController::class . '@register');

    /**
     * Path: /api/v1/login
     * Method: POST
     * Description: login admin
     * Accept : application/json
     * Content-Type : application/json
     *
     */
    Route::post('/login', AuthController::class . '@login');

    /**
     * *****************************************************************************************
     * Description: All the routes under this group are protected by the auth:sanctum middleware
     * *****************************************************************************************
     */
    Route::group(['middleware'=>['auth:sanctum']], function () {
        /**
         * Path: /api/v1/donors
         * Method: GET
         * Description: get all donors
         * Accept : application/json
         * Content-Type : application/json
         *
         */
        Route::get('/donors', DonorController::class . '@index');

        /**
         * Path: /api/v1/logout
         * Method: POST
         * Description: logout admin
         * Accept : application/json
         * Content-Type : application/json
         *
         */
        Route::post('/logout', AuthController::class . '@logout');

        /**
         * Path: /api/v1/admin
         * Method: GET
         * Description: get admin
         * Accept : application/json
         * Content-Type : application/json
         *
         */
        Route::get('/admin', AuthController::class . '@getAdmin');

        /**
         * Path: /api/v1/admin
         * Method: PUT
         * Description: update admin, admin can add consumer_key and consumer_secret
         * Accept : application/json
         * Content-Type : application/json
         *
         */
        Route::put('/admin', AuthController::class . '@updateAdmin');

        /**
         * Path: /api/v1/admin
         * Method: DELETE
         * Description: delete admin
         * Accept : application/json
         * Content-Type : application/json
         *
         */
        Route::delete('/admin', AuthController::class . '@destroy');
    });


});

