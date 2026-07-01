<?php

namespace App\Http\Controllers;

use App\Models\Follower;
use App\Models\Partner;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PublicPartnerController extends Controller
{
    public function index(): View
    {
        $partners = Partner::where('status', 'approved')
            ->withCount(['products as active_products_count' => fn($q) => $q->where('is_active', true)->where('is_sold', false)])
            ->latest('approved_at')
            ->get();

        return view('public.partners.index', [
            'partners'  => $partners,
            'storeName' => config('catalog.store_name'),
        ]);
    }

    public function show(string $slug): View
    {
        // Admin bisa lihat semua status, publik hanya approved
        $query = Partner::where('store_slug', $slug);
        if (!session('is_admin_authenticated')) {
            $query->where('status', 'approved');
        }
        $partner = $query->firstOrFail();

        $products = $partner->products()
            ->when($partner->status === 'approved', fn($q) => $q->where('is_active', true))
            ->latest()
            ->get();

        $reviews = \App\Models\Review::whereIn('product_id', $partner->products()->pluck('id'))
            ->with('user', 'product')
            ->latest()
            ->take(10)
            ->get();

        $isFollowing = false;
        if (Auth::guard('member')->check()) {
            $isFollowing = Follower::where('user_id', Auth::guard('member')->id())
                ->where('partner_id', $partner->id)
                ->exists();
        }

        return view('public.partners.show', [
            'partner'       => $partner,
            'products'      => $products,
            'reviews'       => $reviews,
            'storeName'     => config('catalog.store_name'),
            'productTypes'  => config('catalog.product_types', []),
            'isFollowing'   => $isFollowing,
            'followerCount' => $partner->follower_count ?? $partner->followers()->count(),
        ]);
    }

    public function registerForm(): View
    {
        return view('public.partners.register', [
            'storeName' => config('catalog.store_name'),
        ]);
    }

    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'store_name'  => ['required', 'string', 'max:100'],
            'owner_name'  => ['required', 'string', 'max:100'],
            'email'       => ['required', 'email', 'unique:users,email'],
            'password'    => ['required', 'min:8', 'confirmed'],
            'whatsapp'    => ['required', 'string', 'max:20'],
            'description' => ['nullable', 'string', 'max:1000'],
            'location'    => ['nullable', 'string', 'max:100'],
            'shopee_url'  => ['nullable', 'url'],
            'tokopedia_url' => ['nullable', 'url'],
            'instagram_url' => ['nullable', 'url'],
        ]);

        $slug = $this->uniqueStoreSlug($data['store_name']);

        $user = User::create([
            'name'     => $data['owner_name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => 'partner',
        ]);

        $partner = Partner::create([
            'user_id'       => $user->id,
            'store_name'    => $data['store_name'],
            'store_slug'    => $slug,
            'description'   => $data['description'] ?? null,
            'location'      => $data['location'] ?? null,
            'whatsapp'      => $data['whatsapp'],
            'shopee_url'    => $data['shopee_url'] ?? null,
            'tokopedia_url' => $data['tokopedia_url'] ?? null,
            'instagram_url' => $data['instagram_url'] ?? null,
            'status'        => 'pending',
        ]);

        $user->update(['partner_id' => $partner->id]);

        return redirect()->route('partner.register.success');
    }

    public function registerSuccess(): View
    {
        return view('public.partners.register-success', [
            'storeName' => config('catalog.store_name'),
        ]);
    }

    private function uniqueStoreSlug(string $name): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i    = 1;
        while (Partner::where('store_slug', $slug)->exists()) {
            $slug = "{$base}-{$i}";
            $i++;
        }
        return $slug;
    }
}
