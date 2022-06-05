<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        // response json
        return response()->json([
            'message' => 'Welcome to the Donation API.',
            'code'=> 200,
            'status' => 'success',
        ], 200);
    }
}
