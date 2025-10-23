<?php

namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\auth;

class AuthController extends Controller
{
    public function login()
    {
        return view('Auth.login');
    }
    public function logininsert(Request $request)
    {
        if (auth::attempt($request->only('email', 'password'))) {
            return \redirect('/dashboard');
        }
        return redirect('/login')->with('error', 'Email atau Password Salah');
    }

    public function register()
    {
        return view('Auth.register');
    }
    public function registeruser(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:7',
        ]);
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'remember_token' => Str::random(60),

        ]);
        return redirect('/login');
    }

    public function logout()
    {
        Auth::logout();
        return \redirect('/login');
    }
}
