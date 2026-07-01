<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PartnerAuthController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        if (auth('partner')->check()) return redirect()->route('partner.dashboard');
        return view('partner.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('partner')->attempt($credentials, $request->boolean('remember'))) {
            $user = auth('partner')->user();

            if (! $user->partner) {
                Auth::guard('partner')->logout();
                return back()->withErrors(['email' => 'Akun ini bukan akun mitra.']);
            }

            if (! $user->partner->isApproved()) {
                Auth::guard('partner')->logout();
                $msg = match($user->partner->status) {
                    'pending'   => 'Pendaftaran Anda masih menunggu verifikasi admin.',
                    'rejected'  => 'Pendaftaran Anda ditolak. ' . $user->partner->rejection_reason,
                    'suspended' => 'Akun mitra Anda telah ditangguhkan.',
                    default     => 'Akun tidak dapat digunakan.',
                };
                return back()->withErrors(['email' => $msg]);
            }

            $request->session()->regenerate();
            return redirect()->route('partner.dashboard');
        }

        return back()->withErrors(['email' => 'Email atau password salah.'])->onlyInput('email');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('partner')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('partner.login');
    }
}
