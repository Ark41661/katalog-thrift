<?php
namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function index(Request $request): View
    {
        $query = $request->get('q');
        $products = collect();

        if ($query) {
            $products = Product::with('partner')
                ->where('is_active', true)
                ->whereHas('partner', fn($q) => $q->where('status', 'approved'))
                ->search($query)
                ->latest()
                ->get();
        }

        return view('catalog.search', [
            'query'        => $query,
            'products'     => $products,
            'storeName'    => config('catalog.store_name'),
            'whatsappNumber' => config('catalog.whatsapp_number'),
            'socialLinks'  => config('catalog.social_links'),
        ]);
    }

    public function ajax(Request $request)
    {
        $query = $request->get('q');
        if (!$query || strlen($query) < 2) {
            return response()->json([]);
        }

        $products = Product::with('partner')
            ->where('is_active', true)
            ->whereHas('partner', fn($q) => $q->where('status', 'approved'))
            ->search($query)
            ->take(8)
            ->get(['id', 'slug', 'name', 'brand', 'price', 'image', 'image_path']);

        return response()->json($products->map(fn($p) => [
            'slug'  => $p->slug,
            'name'  => $p->name,
            'brand' => $p->brand,
            'price' => 'Rp ' . number_format($p->price, 0, ',', '.'),
            'image' => $p->image_url,
        ]));
    }
}
