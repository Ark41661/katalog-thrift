<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\UgcPhoto;
use App\Models\VipSubscriber;
use Illuminate\View\View;

class CommunityController extends Controller
{
    public function index(): View
    {
        $photos = UgcPhoto::with(['user', 'product.partner'])
            ->where('status', 'approved')
            ->latest()->paginate(12);

        $products = Product::with('partner')
            ->where('is_active', true)->where('is_sold', false)
            ->whereHas('partner', fn($q) => $q->where('status', 'approved'))
            ->latest()->take(6)->get();

        return view('public.community', [
            'photos'        => $photos,
            'products'      => $products,
            'storeName'     => config('catalog.store_name'),
            'whatsappNumber'=> config('catalog.whatsapp_number'),
        ]);
    }
}
