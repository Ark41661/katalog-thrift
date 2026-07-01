<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class PartnerDashboardController extends Controller
{
    public function index(): View
    {
        $partner  = auth('partner')->user()->partner;
        $products = $partner->products()->latest()->get();

        return view('partner.dashboard', [
            'partner'        => $partner,
            'totalProducts'  => $products->count(),
            'activeProducts' => $products->where('is_active', true)->where('is_sold', false)->count(),
            'soldProducts'   => $products->where('is_sold', true)->count(),
            'avgRating'      => $partner->average_rating,
            'reviewCount'    => $partner->review_count,
            'recentProducts' => $products->take(5),
        ]);
    }
}
