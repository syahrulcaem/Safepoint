<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            if (!$user->hasWebRole()) {
                Auth::logout();
                throw ValidationException::withMessages([
                    'email' => 'Anda tidak memiliki akses ke sistem ini.',
                ]);
            }

            $request->session()->regenerate();

            // Redirect based on user role
            if ($user->role === 'PIMPINAN') {
                return redirect()->intended(route('pimpinan.dashboard'));
            }

            if ($user->role === 'PETUGAS') {
                return redirect()->intended(route('petugas.dashboard'));
            }

            return redirect()->intended('/');
        }

        throw ValidationException::withMessages([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
