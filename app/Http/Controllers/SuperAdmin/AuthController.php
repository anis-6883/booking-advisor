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
            return view('super_admins.auth.login');
        else
        {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if(Auth::guard('super_admin')->attempt($request->only(['email', 'password'])))
            {
                $request->session()->regenerate();
                session()->flash('success', 'Welcome Back!👋');
                return redirect()->intended('super-admin/dashboard');
            }

            session()->flash('error', 'These credentials do not match our records!');
            return redirect()->back()->withInput();
        }
    }

    public function logout()
    {
        Auth::guard('super_admin')->logout();
        session()->flash('success', 'See you again!👋');
        return redirect()->route('super_admins.login');
    }

}
