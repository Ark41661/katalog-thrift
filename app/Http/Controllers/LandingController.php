<?php
namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Partner;
use App\Models\Product;
use App\Models\UgcPhoto;
use Illuminate\View\View;

class LandingController extends Controller
{
    public function index(): View
    {
        $featuredProducts = Product::with('partner')
            ->where('is_active', true)->where('is_sold', false)
            ->whereHas('partner', fn($q) => $q->where('status', 'approved'))
            ->where('is_new_arrival', true)
            ->latest()->take(6)->get();

        $latestArticles = Article::where('is_published', true)
            ->latest('published_at')->take(3)->get();

        $featuredUgc = UgcPhoto::where('status', 'approved')
            ->where('is_featured', true)->latest()->take(6)->get();

        $totalPartners  = Partner::where('status', 'approved')->count();
        $totalProducts  = Product::whereHas('partner', fn($q) => $q->where('status', 'approved'))->where('is_active', true)->count();

        return view('public.landing', [
            'storeName'        => config('catalog.store_name'),
            'storeTagline'     => config('catalog.store_tagline'),
            'socialLinks'      => config('catalog.social_links'),
            'whatsappNumber'   => config('catalog.whatsapp_number'),
            'coverImage'       => config('catalog.cover_image'),
            'featuredProducts' => $featuredProducts,
            'latestArticles'   => $latestArticles,
            'featuredUgc'      => $featuredUgc,
            'totalPartners'    => $totalPartners,
            'totalProducts'    => $totalProducts,
            'heroTitle'        => env('LANDING_HERO_TITLE', config('catalog.catalog_title')),
            'heroSubtitle'     => env('LANDING_HERO_SUBTITLE', config('catalog.brand_story.tagline', config('catalog.store_tagline'))),
            'heroCtaText'      => env('LANDING_HERO_CTA_TEXT', 'Lihat Katalog'),
            'heroImage'        => env('LANDING_HERO_IMAGE', config('catalog.cover_image')),
            'activeNav'        => 'home',
        ]);
    }
}
