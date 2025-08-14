<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
      * login 
     */
    public function login(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // find the user by email
        $user = User::where('email', $request->email)->first();

        // check: if user exits 
        if(!$user) {
            throw ValidationException::withMessages([
                'email' => ['provided credentials are incorrect!']
            ]);
        }

        // if password is matched 
        if(!Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['provided credentials are incorrect!']
            ]);
        }


        // generate a $token
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'token' => $token
        ]);
    }

    /**
     * logout
     */
    public function logout(Request $request) {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logged Out'
        ]);
    }
}
