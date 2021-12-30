<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class allUserController extends Controller
{
    public function index(){
        return User::all();
    }

    public function register(Request $request){
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed',
        ]);

        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password'])
        ]);

        $token = $user->createToken('myapptoken')->plainTextToken;
        $response = [
            'user' => $user,
            'token' => $token
        ];

        return Response($response, 201);
    }

    public function login(Request $request){
        $fields = $request->validate([
            'email' => 'required|String',
            'password' => 'required|string',
        ]);

        // Check email
        $user = User::where('email',$fields['email'])->first();

        // check password
        if(!$user || !Hash::check($fields['password'], $user->password)){
            return Response([
                'message' => 'Invalid Email and Password'
            ], 401);
        }


        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user' => $user,
            'message' => 'Success',
            'token' => $token
        ];

        return Response($response, 201);
    }


}
