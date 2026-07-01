<?php
namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Follower;
use App\Models\Partner;
use App\Notifications\GenericNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FollowerController extends Controller
{
    public function toggle(Partner $partner): RedirectResponse
    {
        $user = auth()->user();
        $existing = Follower::where('user_id', $user->id)->where('partner_id', $partner->id)->first();

        if ($existing) {
            $existing->delete();
            $partner->decrement('follower_count');
            $msg = 'Berhenti mengikuti ' . $partner->store_name;
        } else {
            Follower::create(['user_id' => $user->id, 'partner_id' => $partner->id]);
            $partner->increment('follower_count');
            $user->addPoints(2, 'follow', 'Mengikuti toko ' . $partner->store_name);
            $user->checkBadges($partner);
            $msg = 'Sekarang kamu mengikuti ' . $partner->store_name;

            // Notify partner
            if ($partner->user) {
                $partner->user->notify(new GenericNotification(
                    "{$user->name} mulai mengikuti toko {$partner->store_name}.",
                    '👥',
                    route('partner.dashboard')
                ));
            }
        }

        $partner->recalculateTier();

        return back()->with('success', $msg);
    }

    public function following(): View
    {
        $user = auth()->user();
        $follows = Follower::with('partner')
            ->where('user_id', $user->id)
            ->latest('created_at')
            ->get();

        return view('member.following', [
            'follows'   => $follows,
            'storeName' => config('catalog.store_name'),
        ]);
    }
}
