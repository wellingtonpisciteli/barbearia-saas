<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function autenticar(Request $request)
    {
        $credenciais = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Auth::guard('web')->attempt($credenciais)) {
            return back()->withErrors([
                'email' => 'Credenciais inválidas.'
            ]);
        }


        $request->session()->regenerate();

        return redirect()->route('barbeiro.agendamentos');
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        return redirect()->route('barbeiro.login');
    }
}
