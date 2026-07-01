<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\Outfit;
use App\Models\OutfitItem;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PartnerOutfitController extends Controller
{
    private function partner()
    {
        return auth('partner')->user()->partner;
    }

    public function index(): View
    {
        $outfits = Outfit::with('products.partner')
            ->where('created_by_type', 'partner')
            ->where('partner_id', $this->partner()->id)
            ->latest()->get();

        return view('partner.outfits.index', [
            'partner' => $this->partner(),
            'outfits' => $outfits,
        ]);
    }

    public function create(): View
    {
        // Semua produk aktif dari semua mitra approved (untuk mix & match lintas mitra)
        $products = Product::with('partner')
            ->where('is_active', true)
            ->where('is_sold', false)
            ->whereHas('partner', fn($q) => $q->where('status', 'approved'))
            ->orderBy('name')->get();

        return view('partner.outfits.create', [
            'partner'    => $this->partner(),
            'products'   => $products,
            'styleTypes' => ['casual' => 'Casual', 'streetwear' => 'Streetwear', 'sporty' => 'Sporty', 'vintage' => 'Vintage'],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title'      => ['required', 'string', 'max:100'],
            'description'=> ['nullable', 'string', 'max:500'],
            'style_type' => ['nullable', 'string'],
            'products'   => ['required', 'array', 'min:2', 'max:6'],
            'products.*' => ['exists:products,id'],
        ]);

        $partner = $this->partner();

        $outfit = Outfit::create([
            'title'           => $data['title'],
            'description'     => $data['description'] ?? null,
            'style_type'      => $data['style_type'] ?? null,
            'created_by_type' => 'partner',
            'created_by_id'   => $partner->id,
            'partner_id'      => $partner->id,
            'is_active'       => true,
        ]);

        foreach ($data['products'] as $order => $productId) {
            OutfitItem::create([
                'outfit_id'  => $outfit->id,
                'product_id' => $productId,
                'sort_order' => $order,
                'note'       => $request->input("notes.{$productId}"),
            ]);
        }

        return redirect()->route('partner.outfits.index')
            ->with('success', 'Outfit berhasil dibuat dan akan tampil di Lookbook.');
    }

    public function destroy(Outfit $outfit): RedirectResponse
    {
        abort_if($outfit->partner_id !== $this->partner()->id, 403);
        $outfit->items()->delete();
        $outfit->delete();
        return back()->with('success', 'Outfit dihapus.');
    }
}
