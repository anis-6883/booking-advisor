<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if(!$request->isMethod('POST'))
            return view('super_admin.auth.login');
        else
        {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if(Auth::guard('super_admin')->attempt($request->only(['email', 'password'])))
            {
                $request->session()->regenerate();
                return redirect()->intended('super_admin/dashboard');
            }

            return "No User...";
        }
    }

    public function logout()
    {
        Auth::guard('super_admin')->logout();
        return redirect()->route('super_admin.login');
    }

}
