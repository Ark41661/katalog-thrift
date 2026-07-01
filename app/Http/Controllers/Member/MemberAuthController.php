<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Laravel\Socialite\Facades\Socialite;

class MemberAuthController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        if (auth()->check()) return redirect()->route('catalog.index');
        return view('member.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('catalog.index'));
        }

        return back()->withErrors(['email' => 'Email atau password salah.'])->onlyInput('email');
    }

    public function showRegister(): View|RedirectResponse
    {
        if (auth()->check()) return redirect()->route('catalog.index');
        return view('member.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:100'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => 'member',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('catalog.index')->with('success', 'Selamat datang, ' . $user->name . '!');
    }

    // ─── GOOGLE OAUTH ───────────────────────────────────────────────────────
    public function redirectToGoogle(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('member.login')
                ->withErrors(['email' => 'Gagal login dengan Google. Silakan coba lagi.']);
        }

        $user = User::firstOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'name'      => $googleUser->getName() ?? $googleUser->getNickname() ?? explode('@', $googleUser->getEmail())[0],
                'google_id' => $googleUser->getId(),
                'role'      => 'member',
                'avatar'    => $googleUser->getAvatar(),
            ]
        );

        if (! $user->google_id) {
            $user->update(['google_id' => $googleUser->getId()]);
        }

        Auth::login($user, true);
        session()->regenerate();

        return redirect()->route('catalog.index')->with('success', 'Selamat datang, ' . $user->name . '!');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('catalog.index');
    }

    // ─── FORGOT PASSWORD ────────────────────────────────────────────────────
    public function showForgotPassword(): View
    {
        return view('member.forgot-password', [
            'storeName' => config('catalog.store_name'),
        ]);
    }

    public function sendResetLink(Request $request): RedirectResponse
    {
        $request->validate(['email' => ['required', 'email', 'exists:users,email']]);

        if (app()->environment('local')) {
            $token = Str::random(60);
            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $request->email],
                ['email' => $request->email, 'token' => Hash::make($token), 'created_at' => now()]
            );

            $link = route('member.reset', ['token' => $token, 'email' => $request->email]);

            // Still log the email to storage/logs/laravel.log
            Password::sendResetLink($request->only('email'));

            return back()
                ->with('success', 'Link reset password telah dibuat (mode lokal).')
                ->with('reset_link', $link);
        }

        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('success', 'Link reset password telah dikirim ke email Anda.');
        }

        return back()->withErrors(['email' => __($status)]);
    }

    public function showResetForm(Request $request, string $token): View|RedirectResponse
    {
        return view('member.reset-password', [
            'token'     => $token,
            'email'     => $request->email,
            'storeName' => config('catalog.store_name'),
        ]);
    }

    public function resetPassword(Request $request): RedirectResponse
    {
        $request->validate([
            'token'    => ['required'],
            'email'    => ['required', 'email', 'exists:users,email'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('member.login')->with('success', 'Password berhasil direset. Silakan login.');
        }

        return back()->withErrors(['email' => __($status)]);
    }
}
