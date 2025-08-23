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
     * User registration
     */
    public function signup(Request $request) {
        // validate the user data - name, email, password 
        $data = $request->validate([
            'name' => 'required|string|min:2|max:100',
            'email' => 'required|email',
            'password' => 'required|string|min:6'
        ]);

        // check if email is already exist
        $user = User::where('email', $request->email)->first();
        
        if($user) {
            throw ValidationException::withMessages(["You already have an account"]);
        }

        // encrypt the password 
        Hash::make($request->password);

        // create an entry the the database 
        $user = User::create($data);

        // generate the token - directly logged the user into the system 
        $token = $user->createToken('api-token')->plainTextToken;


        return response()->json([
            'success' => 'Congrats, you are signed up',
            'token' => $token
        ]);
    }

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

        // check: if user not exits 
        if(!$user) {
            throw ValidationException::withMessages([
                'email' => ['provided credentials are incorrect!']
            ]);
        }

        // if password is not matched 
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
            'message' => 'Logged out successfully'
        ]);
    }
}
