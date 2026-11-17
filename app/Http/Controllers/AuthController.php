<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //MUESTRA EL LOGIN
    public function showLogin()
    {
        return view('auth.login');
    }

    //VALIDA LOS DATOS DE INICIO
    public function login(Request $request)
    {
        $credentials = $request->validate(['email' => ['required', 'email'],'password' => ['required'],
        ], [
            'email.required' => 'El correo es obligatorio',
            'email.email' => 'Escribe un correo valido',
            'password.required' => 'La contraseña es obligatoria',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'))->with('success', '¡Bienvenida, '.Auth::user()->name.'!');
        }

        return back()->withErrors(['email' => 'El correo o la contraseña no son correctos'])->onlyInput('email');
    }

    //LOGOUT
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Sesión cerrada correctamente.');
    }
}