<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Outfit;
use App\Models\OutfitSave;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OutfitSaveController extends Controller
{
    public function toggle(Request $request, Outfit $outfit): JsonResponse|RedirectResponse
    {
        $existing = OutfitSave::where('user_id', auth()->id())
            ->where('outfit_id', $outfit->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $saved = false;
        } else {
            OutfitSave::create(['user_id' => auth()->id(), 'outfit_id' => $outfit->id]);
            $saved = true;
        }

        if ($request->wantsJson()) {
            return response()->json(['saved' => $saved, 'count' => $outfit->saves()->count()]);
        }

        return back()->with('success', $saved ? 'Outfit disimpan.' : 'Outfit dihapus dari simpanan.');
    }

    public function index(): View
    {
        $saves = OutfitSave::where('user_id', auth()->id())
            ->with(['outfit.products.partner'])
            ->latest('created_at')
            ->get();

        return view('member.saved-outfits', [
            'saves'     => $saves,
            'storeName' => config('catalog.store_name'),
        ]);
    }
}
