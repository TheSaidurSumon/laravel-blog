<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(Request $request) {
        $request->validate([
            'username' => 'required|string|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    
        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
    
        Auth::login($user);
        return redirect()->intended('/');
    }
    
    public function login(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
        if (!Auth::attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials.'],
            ]);
        }
    
        return redirect()->intended('/');
    }
    
    public function logout() {
        Auth::logout();
        return redirect('/');
    }

}
