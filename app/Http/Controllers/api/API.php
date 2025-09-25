<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class API extends Controller
{
    public function loginApi(Request $request)
    {
        $credentials = $request->validate([
            'user' => ['required'],
            'password' => ['required'],
        ]);

        $usuario = Usuario::where('user', $credentials['user'])->first();

        if ($usuario && Hash::check($credentials['password'], $usuario->password)) {
            return response()->json([
                'success' => true,
                'user' => $usuario,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Credenciais inválidas',
        ], 401);
    }
}
