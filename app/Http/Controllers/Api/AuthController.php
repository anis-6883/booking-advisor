<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]); 

        if($validator->fails()){
            return response()->json(['result' => false, 'messages' => $validator->errors()->all()]);
        }

        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])) 
        {
            $user = Auth::user();
            $access_token = $user->createToken('authToken')->accessToken;

            return response()->json([
                'result' => true,
                'user' => $user->makeHidden(['email_verified_at', 'created_at', 'updated_at']),
                'access_token' => $access_token 
            ]);
        }
        else{
            return response()->json(['result' => false, 'messages' => 'Your provided credentials do not match our records!']);
        }
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|min:6|confirmed'
        ]); 

        if($validator->fails()){
            return response()->json(['result' => false, 'messages' => $validator->errors()->all()]);
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        $access_token = $user->createToken('authToken')->accessToken;

        return response()->json([
            'result' => true,
            'user' => $user->makeHidden(['email_verified_at', 'created_at', 'updated_at']),
            'access_token' => $access_token 
        ]);
    }
}
