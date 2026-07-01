<?php
namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PartnerNotificationController extends Controller
{
    public function index(): View
    {
        $partner = auth('partner')->user()->partner;
        $notifications = $partner->user->notifications()->latest()->paginate(20);

        return view('partner.notifications', [
            'partner'       => $partner,
            'notifications' => $notifications,
            'storeName'     => config('catalog.store_name'),
        ]);
    }

    public function markRead(string $id): RedirectResponse
    {
        $notification = auth('partner')->user()->partner->user
            ->notifications()
            ->findOrFail($id);
        $notification->markAsRead();
        return back();
    }

    public function markAllRead(): RedirectResponse
    {
        auth('partner')->user()->partner->user->unreadNotifications->markAsRead();
        return back()->with('success', 'Semua notifikasi ditandai sudah dibaca.');
    }
}
