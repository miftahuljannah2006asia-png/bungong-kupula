<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showLogin(Request $request)
    {
        if ($request->session()->get('admin_authenticated')) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.login', [
            'configured' => filled(config('admin.email')) && filled(config('admin.password_hash')),
        ]);
    }

    public function login(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'required' => ':attribute wajib diisi.',
            'email' => 'Format email tidak valid.',
        ], [
            'email' => 'Email',
            'password' => 'Kata sandi',
        ]);

        $email = (string) config('admin.email');
        $hash = (string) config('admin.password_hash');

        if ($email === '' || $hash === '') {
            return back()->withErrors(['email' => 'Akun admin belum diatur di file .env (lihat README).']);
        }

        $valid = hash_equals(mb_strtolower($email), mb_strtolower($data['email']))
            && password_verify($data['password'], $hash);

        if (! $valid) {
            return back()->withErrors(['email' => 'Email atau kata sandi salah.'])->onlyInput('email');
        }

        $request->session()->regenerate();
        $request->session()->put('admin_authenticated', true);

        return redirect()->route('admin.dashboard');
    }

    public function logout(Request $request): RedirectResponse
    {
        $request->session()->forget('admin_authenticated');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
