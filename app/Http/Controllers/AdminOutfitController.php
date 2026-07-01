<?php

namespace App\Http\Controllers;

use App\Models\Outfit;
use App\Models\OutfitItem;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AdminOutfitController extends Controller
{
    public function index(): View
    {
        $outfits = Outfit::with('products.partner')
            ->where('created_by_type', 'admin')
            ->latest()
            ->get();

        return view('admin.outfits.index', [
            'outfits'   => $outfits,
            'storeName' => config('catalog.store_name'),
        ]);
    }

    public function create(): View
    {
        $products = Product::with('partner')
            ->where('is_active', true)
            ->where('is_sold', false)
            ->whereHas('partner', fn($q) => $q->where('status', 'approved'))
            ->orderBy('name')
            ->get();

        return view('admin.outfits.create', [
            'products'   => $products,
            'styleTypes' => ['casual' => 'Casual', 'streetwear' => 'Streetwear', 'sporty' => 'Sporty', 'vintage' => 'Vintage'],
            'storeName'  => config('catalog.store_name'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title'        => ['required', 'string', 'max:100'],
            'description'  => ['nullable', 'string', 'max:500'],
            'style_type'   => ['nullable', 'string'],
            'is_active'    => ['nullable', 'boolean'],
            'products'     => ['required', 'array', 'min:2', 'max:6'],
            'products.*'   => ['exists:products,id'],
            'notes'        => ['nullable', 'array'],
            'notes.*'      => ['nullable', 'string', 'max:100'],
            'hotspots'     => ['nullable', 'array'],
            'hotspots.*.x' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'hotspots.*.y' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'cover_image'  => ['nullable', 'image', 'max:4096'],
            'cover_video'  => ['nullable', 'url', 'max:500'],
        ]);

        $coverImage = null;
        if ($request->hasFile('cover_image')) {
            $coverImage = $request->file('cover_image')->store('outfits', 'public');
        }

        $outfit = Outfit::create([
            'title'           => $data['title'],
            'description'     => $data['description'] ?? null,
            'style_type'      => $data['style_type'] ?? null,
            'created_by_type' => 'admin',
            'created_by_id'   => 0,
            'cover_image'     => $coverImage,
            'cover_video'     => $data['cover_video'] ?? null,
            'is_active'       => $request->boolean('is_active', true),
        ]);

        $this->syncOutfitItems($outfit, $data['products'], $request);

        return redirect()->route('admin.outfits.index')
            ->with('success', 'Outfit kurasi berhasil dibuat.');
    }

    public function edit(Outfit $outfit): View
    {
        $products = Product::with('partner')
            ->where('is_active', true)
            ->whereHas('partner', fn($q) => $q->where('status', 'approved'))
            ->orderBy('name')
            ->get();

        return view('admin.outfits.edit', [
            'outfit'     => $outfit->load('items.product'),
            'products'   => $products,
            'styleTypes' => ['casual' => 'Casual', 'streetwear' => 'Streetwear', 'sporty' => 'Sporty', 'vintage' => 'Vintage'],
            'storeName'  => config('catalog.store_name'),
        ]);
    }

    public function update(Request $request, Outfit $outfit): RedirectResponse
    {
        $data = $request->validate([
            'title'        => ['required', 'string', 'max:100'],
            'description'  => ['nullable', 'string', 'max:500'],
            'style_type'   => ['nullable', 'string'],
            'is_active'    => ['nullable', 'boolean'],
            'products'     => ['required', 'array', 'min:2', 'max:6'],
            'products.*'   => ['exists:products,id'],
            'notes'        => ['nullable', 'array'],
            'hotspots'     => ['nullable', 'array'],
            'hotspots.*.x' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'hotspots.*.y' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'cover_image'  => ['nullable', 'image', 'max:4096'],
            'cover_video'  => ['nullable', 'url', 'max:500'],
        ]);

        $coverImage = $outfit->cover_image;
        if ($request->hasFile('cover_image')) {
            if ($coverImage && !str_starts_with($coverImage, 'http')) {
                Storage::disk('public')->delete($coverImage);
            }
            $coverImage = $request->file('cover_image')->store('outfits', 'public');
        }

        $outfit->update([
            'title'       => $data['title'],
            'description' => $data['description'] ?? null,
            'style_type'  => $data['style_type'] ?? null,
            'cover_image' => $coverImage,
            'cover_video' => $data['cover_video'] ?? $outfit->cover_video,
            'is_active'   => $request->boolean('is_active', false),
        ]);

        $outfit->items()->delete();
        $this->syncOutfitItems($outfit, $data['products'], $request);

        return redirect()->route('admin.outfits.index')
            ->with('success', 'Outfit kurasi berhasil diperbarui.');
    }

    public function destroy(Outfit $outfit): RedirectResponse
    {
        if ($outfit->cover_image && !str_starts_with($outfit->cover_image, 'http')) {
            Storage::disk('public')->delete($outfit->cover_image);
        }
        $outfit->items()->delete();
        $outfit->delete();

        return back()->with('success', 'Outfit dihapus.');
    }

    public function toggleActive(Outfit $outfit): RedirectResponse
    {
        $outfit->update(['is_active' => !$outfit->is_active]);

        return back()->with('success', 'Status outfit diperbarui.');
    }

    private function syncOutfitItems(Outfit $outfit, array $productIds, Request $request): void
    {
        foreach ($productIds as $order => $productId) {
            $hotspot = $request->input("hotspots.{$productId}", []);

            OutfitItem::create([
                'outfit_id'  => $outfit->id,
                'product_id' => $productId,
                'sort_order' => $order,
                'note'       => $request->input("notes.{$productId}"),
                'hotspot_x'  => isset($hotspot['x']) ? round((float) $hotspot['x'], 2) : null,
                'hotspot_y'  => isset($hotspot['y']) ? round((float) $hotspot['y'], 2) : null,
            ]);
        }
    }
}
