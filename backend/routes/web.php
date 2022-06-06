<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $app_url = env('APP_URL');
    return view('welcome', compact('app_url'));
});

Route::get('/get-csrf-token', function () {
    $token = csrf_token();
    return response()->json(['csrf_token' => $token]);
});
