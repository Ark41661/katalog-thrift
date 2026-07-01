<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WishlistController extends Controller
{
    public function index(): View
    {
        $wishlists = Wishlist::where('user_id', auth()->id())
            ->with(['product.partner'])
            ->latest('created_at')
            ->get();

        return view('member.wishlist', ['wishlists' => $wishlists]);
    }

    public function toggle(Request $request, string $slug): JsonResponse|RedirectResponse
    {
        $product = Product::where('slug', $slug)->firstOrFail();

        $existing = Wishlist::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->first();

        $user = auth()->user();
        if ($existing) {
            $existing->delete();
            $saved = false;
        } else {
            Wishlist::create(['user_id' => $user->id, 'product_id' => $product->id]);
            $saved = true;
            $user->addPoints(1, 'wishlist', 'Menyimpan ' . $product->name . ' ke wishlist', $product);
            $user->checkBadges($product);
        }

        if ($request->wantsJson()) {
            return response()->json(['saved' => $saved]);
        }

        return back()->with('success', $saved ? 'Ditambahkan ke wishlist.' : 'Dihapus dari wishlist.');
    }
}
