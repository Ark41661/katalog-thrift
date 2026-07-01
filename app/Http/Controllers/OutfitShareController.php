<?php

namespace App\Http\Controllers;

use App\Models\Outfit;
use Illuminate\View\View;

class OutfitShareController extends Controller
{
    public function show(string $token): View
    {
        $outfit = Outfit::where('share_token', $token)
            ->with(['products.partner'])
            ->firstOrFail();

        $savedIds = auth()->check()
            ? \App\Models\OutfitSave::where('user_id', auth()->id())->pluck('outfit_id')->toArray()
            : [];

        return view('catalog.outfit-share', [
            'outfit'     => $outfit,
            'savedIds'   => $savedIds,
            'storeName'  => config('catalog.store_name'),
            'waNumber'   => config('catalog.whatsapp_number'),
            'socialLinks'=> config('catalog.social_links'),
        ]);
    }
}
