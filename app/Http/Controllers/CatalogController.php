<?php

namespace App\Http\Controllers;

use App\Models\Follower;
use App\Models\Partner;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CatalogController extends Controller
{
    public function landing(): View
    {
        $newArrivals = Product::with('partner')
            ->where('is_active', true)->where('is_new_arrival', true)
            ->whereHas('partner', fn($q) => $q->where('status', 'approved'))
            ->latest()->take(3)->get();

        return view('public.landing', [
            'storeName'      => config('catalog.store_name'),
            'storeTagline'   => config('catalog.store_tagline'),
            'socialLinks'    => config('catalog.social_links'),
            'coverImage'     => config('catalog.cover_image'),
            'newArrivals'    => $newArrivals,
        ]);
    }

    public function index(Request $request): View
    {
        $query = Product::with('partner')
            ->where('is_active', true)
            ->whereHas('partner', fn($q) => $q->where('status', 'approved'));

        if ($request->filled('category'))    $query->where('product_type', $request->category);
        if ($request->filled('brand'))       $query->where('brand', $request->brand);
        if ($request->filled('partner'))     $query->where('partner_id', $request->partner);
        if ($request->filled('size'))        $query->where('size', $request->size);
        if ($request->filled('min_price'))   $query->where('price', '>=', (int) $request->min_price);
        if ($request->filled('max_price'))   $query->where('price', '<=', (int) $request->max_price);
        if ($request->filled('availability')) {
            $request->availability === 'sold'
                ? $query->where('is_sold', true)
                : $query->where('is_sold', false);
        }
        if ($request->filled('new_arrival')) $query->where('is_new_arrival', true);

        $products    = $query->latest()->get();
        $newArrivals = Product::with('partner')
            ->where('is_active', true)->where('is_new_arrival', true)
            ->whereHas('partner', fn($q) => $q->where('status', 'approved'))
            ->latest()->take(4)->get();

        $allSizes    = Product::whereHas('partner', fn($q) => $q->where('status', 'approved'))
            ->where('is_active', true)->distinct()->orderBy('size')->pluck('size');
        $allPartners = Partner::where('status', 'approved')->orderBy('store_name')->get();

        $productTypes   = config('catalog.product_types', []);
        $categoryCounts = Product::whereHas('partner', fn($q) => $q->where('status', 'approved'))
            ->where('is_active', true)
            ->selectRaw('product_type, count(*) as total')
            ->groupBy('product_type')
            ->pluck('total', 'product_type');

        return view('catalog.index', [
            'storeName'      => config('catalog.store_name'),
            'storeTagline'   => config('catalog.store_tagline'),
            'whatsappNumber' => config('catalog.whatsapp_number'),
            'catalogSeason'  => config('catalog.catalog_season'),
            'catalogTitle'   => config('catalog.catalog_title'),
            'socialLinks'    => config('catalog.social_links'),
            'coverImage'     => config('catalog.cover_image'),
            'products'       => $products,
            'newArrivals'    => $newArrivals,
            'allSizes'       => $allSizes,
            'allPartners'    => $allPartners,
            'productTypes'   => $productTypes,
            'categoryCounts' => $categoryCounts,
            'filters'        => $request->only(['category', 'brand', 'partner', 'size', 'min_price', 'max_price', 'availability', 'new_arrival']),
        ]);
    }

    public function show(string $slug): View
    {
        $product = Product::with(['partner', 'reviews.user', 'variants', 'questions.user'])
            ->where('slug', $slug)->firstOrFail();

        $product->increment('total_views');

        $productTypes = config('catalog.product_types', []);
        $currentType  = $product->product_type ?? 'hoodie';
        $pairingTypes = $productTypes[$currentType]['pairing'] ?? [];

        if (!empty($pairingTypes)) {
            $pairings = Product::with('partner')
                ->where('is_active', true)->where('is_sold', false)
                ->where('slug', '!=', $slug)
                ->whereIn('product_type', $pairingTypes)
                ->whereHas('partner', fn($q) => $q->where('status', 'approved'))
                ->inRandomOrder()->take(4)->get();
            $pairingLabel = 'Cocok Dipadukan: ' . collect($pairingTypes)->map(fn($t) => $productTypes[$t]['label'] ?? $t)->implode(' / ');
        } else {
            $pairings = Product::with('partner')
                ->where('is_active', true)->where('is_sold', false)
                ->where('slug', '!=', $slug)
                ->whereIn('product_type', ['hoodie', 'jacket', 'tshirt'])
                ->whereHas('partner', fn($q) => $q->where('status', 'approved'))
                ->inRandomOrder()->take(4)->get();
            $pairingLabel = 'Outfit yang Cocok';
        }

        $relatedProducts = Product::with('partner')
            ->where('slug', '!=', $slug)->where('is_active', true)->where('is_sold', false)
            ->whereHas('partner', fn($q) => $q->where('status', 'approved'))
            ->inRandomOrder()->take(3)->get();

        $userReview = auth()->check()
            ? $product->reviews->firstWhere('user_id', auth()->id())
            : null;

        $wishlistIds = auth()->check()
            ? auth()->user()->wishlists()->pluck('product_id')->toArray()
            : [];

        $isFollowingPartner = false;
        if (Auth::guard('member')->check() && $product->partner) {
            $isFollowingPartner = Follower::where('user_id', Auth::guard('member')->id())
                ->where('partner_id', $product->partner_id)
                ->exists();
        }

        return view('catalog.show', [
            'storeName'           => config('catalog.store_name'),
            'whatsappNumber'      => config('catalog.whatsapp_number'),
            'socialLinks'         => config('catalog.social_links'),
            'product'             => $product,
            'relatedProducts'     => $relatedProducts,
            'pairings'            => $pairings,
            'pairingLabel'        => $pairingLabel,
            'productTypes'        => $productTypes,
            'userReview'          => $userReview,
            'wishlistIds'         => $wishlistIds,
            'isFollowingPartner'  => $isFollowingPartner,
        ]);
    }

    public function trackWaClick(Request $request, string $slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();
        $product->increment('total_wa_clicks');

        if ($product->partner) {
            $product->partner->increment('total_wa_clicks');
            $product->partner->recalculateTier();
        }

        $storeName = $product->partner?->store_name ?? config('catalog.store_name');
        $size = $request->query('size', $product->size);
        $price = (int) $request->query('price', $product->price);
        $message = "Halo {$storeName}, saya ingin pesan {$product->name} ({$size}) harga Rp " . number_format($price, 0, ',', '.') . ". Mohon info stoknya ya.";
        $whatsappNumber = $product->partner?->whatsapp ?? config('catalog.whatsapp_number');

        return redirect()->away("https://wa.me/{$whatsappNumber}?text=" . urlencode($message));
    }

    public function lookbook(): View
    {
        // Curated outfits dari admin + mitra (Lookbook)
        $curatedOutfits = \App\Models\Outfit::with(['items.product.partner', 'partner'])
            ->where('is_active', true)
            ->latest()
            ->get();

        $savedOutfitIds = auth()->check()
            ? \App\Models\OutfitSave::where('user_id', auth()->id())->pluck('outfit_id')->toArray()
            : [];

        return view('catalog.lookbook', [
            'storeName'      => config('catalog.store_name'),
            'catalogSeason'  => config('catalog.catalog_season'),
            'socialLinks'    => config('catalog.social_links'),
            'whatsappNumber' => config('catalog.whatsapp_number'),
            'curatedOutfits' => $curatedOutfits,
            'savedOutfitIds' => $savedOutfitIds,
        ]);
    }

    public function about(): View
    {
        $totalProducts = Product::whereHas('partner', fn($q) => $q->where('status', 'approved'))->where('is_active', true)->count();
        $totalPartners = Partner::where('status', 'approved')->count();

        $featuredOutfit = \App\Models\Outfit::with(['items.product'])
            ->where('is_active', true)
            ->whereNotNull('cover_image')
            ->latest()
            ->first();

        return view('catalog.about', [
            'storeName'      => config('catalog.store_name'),
            'storeTagline'   => config('catalog.store_tagline'),
            'storeSince'     => config('catalog.store_since'),
            'storeLocation'  => config('catalog.store_location'),
            'whatsappNumber' => config('catalog.whatsapp_number'),
            'socialLinks'    => config('catalog.social_links'),
            'brandStory'     => config('catalog.brand_story', []),
            'heroImage'      => env('LANDING_HERO_IMAGE', config('catalog.cover_image')),
            'totalProducts'  => $totalProducts,
            'totalPartners'  => $totalPartners,
            'featuredOutfit' => $featuredOutfit,
            'activeNav'      => 'tentang',
        ]);
    }
}
