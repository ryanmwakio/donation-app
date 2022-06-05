<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:users,name',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token= $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'message' => 'User created successfully',
            'code'=> 201,
            'status' => 'success',
            'data'=> [
                'user' => $user,
                'token' => $token,
            ],
        ], 200);
    }

    public function logout(Request $request): JsonResponse
    {
        auth()->user()->tokens()->delete();

        return response()->json([
            'message' => 'Successfully logged out',
            'code'=> 200,
            'status' => 'success',
            'data'=> [],
        ], 200);
    }

    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);

        //check email
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json([
                'message' => 'User not found',
                'code'=> 404,
                'status' => 'error',
                'data'=> [],
            ], 404);
        }

        //check password
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Password is incorrect',
                'code'=> 401,
                'status' => 'error',
                'data'=> [],
            ], 401);
        }

        $token= $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'message' => 'Successfully logged in',
            'code'=> 200,
            'status' => 'success',
            'data'=> [
                'user' => $user,
                'token' => $token,
            ],
        ], 200);
    }

    public function getAdmin(Request $request): JsonResponse
    {
        $user = auth()->user();
        return response()->json([
            'message' => 'Logged in as '.$user->name,
            'code'=> 200,
            'status' => 'success',
            'data'=> [
                'user' => $user,
            ],
        ], 200);
    }

    public function updateAdmin(Request $request): JsonResponse
    {
        //check if consumer key and consumer secret are provided
        if (!$request->has('consumer_key') || !$request->has('consumer_secret')) {
            return response()->json([
                'message' => 'Consumer key and consumer secret are required',
                'code'=> 400,
                'status' => 'error',
                'data'=> [],
            ], 400);
        }

        // find user from token

        $user = auth()->user();
        $user->consumer_key = $request->consumer_key;
        $user->consumer_secret = $request->consumer_secret;
        $user->save();
        return response()->json([
            'message' => 'User updated successfully',
            'code'=> 200,
            'status' => 'success',
            'data'=> [
                'user' => $user,
            ],
        ], 200);
    }

    public function destroy(Request $request): JsonResponse
    {
        $user = auth()->user();
        $user->delete();
        return response()->json([
            'message' => 'User deleted successfully',
            'code'=> 200,
            'status' => 'success',
            'data'=> [],
        ], 200);
    }


}
