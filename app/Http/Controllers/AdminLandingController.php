<?php
namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminLandingController extends Controller
{
    public function edit(): View
    {
        return view('admin.landing.edit', [
            'storeName' => config('catalog.store_name'),
            'landing'   => config('landing', []),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'hero_title'    => ['required', 'string', 'max:100'],
            'hero_subtitle' => ['nullable', 'string', 'max:300'],
            'hero_cta_text' => ['nullable', 'string', 'max:50'],
            'hero_image'    => ['nullable', 'url'],
            'promo_title'   => ['nullable', 'string', 'max:100'],
            'promo_text'    => ['nullable', 'string', 'max:500'],
            'promo_badge'   => ['nullable', 'string', 'max:50'],
        ]);

        // Simpan ke .env atau config file
        $envPath = base_path('.env');
        $env     = file_get_contents($envPath);

        foreach ($data as $key => $value) {
            $envKey = 'LANDING_' . strtoupper($key);
            $value  = '"' . addslashes($value ?? '') . '"';
            if (str_contains($env, $envKey . '=')) {
                $env = preg_replace('/^' . $envKey . '=.*/m', $envKey . '=' . $value, $env);
            } else {
                $env .= "\n{$envKey}={$value}";
            }
        }

        file_put_contents($envPath, $env);
        \Artisan::call('config:clear');

        return back()->with('success', 'Landing page diperbarui.');
    }
}
