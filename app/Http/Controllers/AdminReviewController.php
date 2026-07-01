<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Notifications\GenericNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdminReviewController extends Controller
{
    public function index(): View
    {
        $reviews = Review::with(['user', 'product.partner'])
            ->latest()
            ->paginate(30);

        return view('admin.reviews.index', [
            'reviews'   => $reviews,
            'storeName' => config('catalog.store_name'),
        ]);
    }

    public function approve(Review $review): RedirectResponse
    {
        $review->update([
            'is_approved'   => true,
            'moderated_at'  => now(),
            'moderated_by'  => auth()->id(),
        ]);
        return back()->with('success', 'Review disetujui.');
    }

    public function hide(Review $review): RedirectResponse
    {
        $review->update([
            'is_approved'   => false,
            'moderated_at'  => now(),
            'moderated_by'  => auth()->id(),
        ]);

        if ($review->user) {
            $review->user->notify(new GenericNotification(
                'Review kamu untuk ' . $review->product->name . ' disembunyikan oleh moderator.',
                '⚠️',
                route('catalog.show', $review->product->slug)
            ));
        }

        return back()->with('success', 'Review disembunyikan.');
    }

    public function destroy(Review $review): RedirectResponse
    {
        if ($review->user) {
            $review->user->notify(new GenericNotification(
                'Review kamu untuk ' . $review->product->name . ' telah dihapus oleh moderator.',
                '🗑️',
                route('catalog.show', $review->product->slug)
            ));
        }

        $review->delete();
        return back()->with('success', 'Review berhasil dihapus.');
    }
}
