<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;  
use App\Models\Usuario;               

class AuthController extends Controller
{
    public function index()
    {
        return view('user.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'user' => ['required'],
            'password' => ['required'],
        ]);

        $usuario = Usuario::where('user', $credentials['user'])->first();

        if ($usuario && Hash::check($credentials['password'], $usuario->password)) {
            Auth::login($usuario);
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'user' => 'Login ou senha incorreta!',
        ])->onlyInput('user');
    }


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
