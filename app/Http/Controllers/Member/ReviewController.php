<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;
use App\Notifications\GenericNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, string $slug): RedirectResponse
    {
        $product = Product::where('slug', $slug)->firstOrFail();

        $data = $request->validate([
            'rating'  => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:500'],
        ]);

        $review = Review::updateOrCreate(
            ['user_id' => auth()->id(), 'product_id' => $product->id],
            $data
        );

        $user = auth()->user();
        $user->addPoints(5, 'review', 'Memberikan review untuk ' . $product->name, $product);
        $user->checkBadges($review);

        $product->partner?->recalculateTier();

        // Notify partner
        if ($product->partner?->user) {
            $product->partner->user->notify(new GenericNotification(
                "Produk {$product->name} mendapat review baru dari {$user->name}.",
                '⭐',
                route('partner.dashboard')
            ));
        }

        return back()->with('success', 'Review berhasil disimpan.');
    }

    public function destroy(string $slug): RedirectResponse
    {
        $product = Product::where('slug', $slug)->firstOrFail();

        Review::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->delete();

        $product->partner?->recalculateTier();

        return back()->with('success', 'Review dihapus.');
    }
}
