<?php
namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;
use App\Models\Wishlist;
use Illuminate\View\View;

class PartnerAnalyticsController extends Controller
{
    private function partner()
    {
        return auth('partner')->user()->partner;
    }

    public function index(): View
    {
        $partner = $this->partner();
        $partner->recalculateTier();

        $products = $partner->products()->latest()->get();
        $productIds = $products->pluck('id');

        $totalViews = $products->sum('total_views');
        $totalWaClicks = $products->sum('total_wa_clicks');
        $topProducts = $products->sortByDesc('total_views')->take(5);
        $wishlistCount = Wishlist::whereIn('product_id', $productIds)->count();

        // Review stats
        $reviewStats = Review::whereIn('product_id', $productIds)
            ->selectRaw('rating, count(*) as total')
            ->groupBy('rating')
            ->pluck('total', 'rating');

        // Daily views breakdown (last 30 days)
        $dailyData = Product::where('partner_id', $partner->id)
            ->selectRaw('DATE(created_at) as date, SUM(total_views) as views, SUM(total_wa_clicks) as wa_clicks')
            ->groupBy('date')
            ->orderBy('date')
            ->take(30)
            ->get();

        return view('partner.analytics', [
            'partner'       => $partner,
            'totalProducts' => $products->count(),
            'activeProducts'=> $products->where('is_active', true)->where('is_sold', false)->count(),
            'totalViews'    => $totalViews,
            'totalWaClicks' => $totalWaClicks,
            'topProducts'   => $topProducts,
            'wishlistCount' => $wishlistCount,
            'reviewStats'   => $reviewStats,
            'dailyData'     => $dailyData,
            'followerCount' => $partner->follower_count,
            'avgRating'     => $partner->average_rating,
            'tierBadge'     => $partner->tier_badge,
            'tierName'      => $partner->tier_name,
            'storeName'     => config('catalog.store_name'),
        ]);
    }
}
