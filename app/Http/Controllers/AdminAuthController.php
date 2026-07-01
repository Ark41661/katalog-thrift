<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdminAuthController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        if (session('is_admin_authenticated')) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $username = config('admin.username');
        $password = config('admin.password');

        if (
            hash_equals((string) $username, (string) $credentials['username']) &&
            hash_equals((string) $password, (string) $credentials['password'])
        ) {
            $request->session()->regenerate();
            $request->session()->put('is_admin_authenticated', true);

            return redirect()->route('admin.dashboard');
        }

        return back()
            ->withErrors(['username' => 'Username atau password salah.'])
            ->onlyInput('username');
    }

    public function logout(Request $request): RedirectResponse
    {
        $request->session()->forget('is_admin_authenticated');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
