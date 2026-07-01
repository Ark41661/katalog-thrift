<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PartnerProfileController extends Controller
{
    public function edit(): View
    {
        return view('partner.profile', [
            'partner' => auth('partner')->user()->partner,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $partner = auth('partner')->user()->partner;

        $data = $request->validate([
            'store_name'    => ['required', 'string', 'max:100'],
            'description'   => ['nullable', 'string', 'max:1000'],
            'location'      => ['nullable', 'string', 'max:100'],
            'whatsapp'      => ['nullable', 'string', 'max:20'],
            'shopee_url'    => ['nullable', 'url'],
            'tokopedia_url' => ['nullable', 'url'],
            'instagram_url' => ['nullable', 'url'],
            'tiktok_url'    => ['nullable', 'url'],
            'logo_file'     => ['nullable', 'image', 'max:1024'],
        ]);

        if ($request->hasFile('logo_file')) {
            if ($partner->logo && ! str_starts_with($partner->logo, 'http')) {
                Storage::disk('public')->delete($partner->logo);
            }
            $data['logo'] = $request->file('logo_file')->store("logos/{$partner->id}", 'public');
        }

        unset($data['logo_file']);
        $partner->update($data);

        return back()->with('success', 'Profil toko berhasil diperbarui.');
    }
}
