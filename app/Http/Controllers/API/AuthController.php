<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);
    
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()], 422);
        }
    
        // Attempt to log the user in
        if (!auth()->attempt($request->only('email', 'password'))) {
            return response()->json(['status' => false, 'message' => 'Invalid credentials'], 401);
        }
    
        // Get the authenticated user
        $user = auth()->user();
    
        $token = $user->createToken('Personal Access Token')->plainTextToken;

        // Return the token along with the user details
        return response()->json([
            'status' => true,
            'message' => 'Login success',
            'data' => [
                'user' => $user,
                'token' => $token // Use $token here
            ]
        ]);        
    }

    public function logout(Request $request)
    {
        // Revoke the token that was used to authenticate the current request...
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => true,
            'message' => 'Logout success'
        ]);
    }
}
