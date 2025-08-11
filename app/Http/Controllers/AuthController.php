<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Halaman login
    public function index()
    {
        return view('layouts.admin', [
            'title'   => 'Login',
            'content' => 'auth.login', // pastikan view ini punya input name="login" & "password"
        ]);
    }

    // Proses login (username ATAU email)
    public function doLogin(Request $request)
    {
        $data = $request->validate([
            'login'    => ['required','string'],  // bisa username atau email
            'password' => ['required','string'],
            'remember' => ['nullable','boolean'],
        ]);

        $login    = $data['login'];
        $password = $data['password'];
        $remember = (bool)($data['remember'] ?? false);

        // Deteksi apakah input login adalah email
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (Auth::attempt([$field => $login, 'password' => $password], $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Redirect berdasarkan role Spatie
            if ($user->hasRole('admin')) {
                return redirect()->intended(route('admin.dashboard'));
            }
            if ($user->hasRole('dosen')) {
                return redirect()->intended(route('dosen.dashboard'));
            }
            if ($user->hasRole('mahasiswa')) {
                return redirect()->intended(route('mahasiswa.dashboard'));
            }

            // Fallback (jika tidak punya role)
            return redirect()->intended('/');
        }

        return back()
            ->withInput($request->only('login'))
            ->with('loginError', 'Gagal login. Kredensial tidak valid.');
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('success', 'Anda telah logout.');
    }
}
