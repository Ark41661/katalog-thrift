<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use App\Models\Product;
use App\Models\ProductReport;
use App\Models\Review;
use App\Models\UgcPhoto;
use App\Models\User;
use App\Models\ActivityLog;
use App\Models\WebReport;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'storeName'       => config('catalog.store_name'),
            'totalPartners'   => Partner::where('status', 'approved')->count(),
            'pendingPartners' => Partner::where('status', 'pending')->count(),
            'totalProducts'   => Product::count(),
            'totalMembers'    => User::where('role', 'member')->count(),
            'totalReviews'    => Review::count(),
            'pendingReports'  => ProductReport::where('status', 'pending')->count(),
            'pendingUgc'        => UgcPhoto::where('status', 'pending')->count(),
            'pendingWebReports' => WebReport::where('status', 'pending')->count(),
            'recentPartners'    => Partner::with('user')->where('status', 'pending')->latest()->take(5)->get(),
        ]);
    }

    public function analytics(): View
    {
        $topPartners = Partner::where('status', 'approved')
            ->orderBy('total_views', 'desc')
            ->take(10)
            ->get(['store_name', 'store_slug', 'total_views', 'tier', 'follower_count']);

        $topProducts = Product::with('partner')
            ->where('is_active', true)
            ->orderBy('total_views', 'desc')
            ->take(10)
            ->get();

        $tierDistribution = Partner::where('status', 'approved')
            ->selectRaw('tier, count(*) as total')
            ->groupBy('tier')
            ->pluck('total', 'tier');

        $memberTierDistribution = User::where('role', 'member')
            ->selectRaw('tier, count(*) as total')
            ->groupBy('tier')
            ->pluck('total', 'tier');

        return view('admin.analytics', [
            'storeName'              => config('catalog.store_name'),
            'topPartners'            => $topPartners,
            'topProducts'            => $topProducts,
            'tierDistribution'       => $tierDistribution,
            'memberTierDistribution' => $memberTierDistribution,
            'totalMembers'           => User::where('role', 'member')->count(),
            'totalPartners'          => Partner::where('status', 'approved')->count(),
            'totalProducts'          => Product::where('is_active', true)->count(),
            'totalViews'             => Product::sum('total_views'),
        ]);
    }
}
