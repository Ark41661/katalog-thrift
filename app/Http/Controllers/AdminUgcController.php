<?php
namespace App\Http\Controllers;

use App\Models\UgcPhoto;
use App\Notifications\GenericNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdminUgcController extends Controller
{
    public function index(): View
    {
        $photos = UgcPhoto::with(['user', 'product.partner'])
            ->latest()->paginate(24);
        return view('admin.ugc.index', [
            'photos'    => $photos,
            'storeName' => config('catalog.store_name'),
        ]);
    }

    public function approve(UgcPhoto $ugcPhoto): RedirectResponse
    {
        $ugcPhoto->update(['status' => 'approved']);

        if ($ugcPhoto->user) {
            $ugcPhoto->user->addPoints(10, 'ugc_submit', 'Foto komunitas disetujui', $ugcPhoto);
            $ugcPhoto->user->checkBadges($ugcPhoto);
            $ugcPhoto->user->notify(new GenericNotification(
                'Foto komunitas kamu telah disetujui! 🎉',
                '📸',
                route('community.index')
            ));
        }

        return back()->with('success', 'Foto disetujui dan akan tampil di halaman Komunitas.');
    }

    public function reject(UgcPhoto $ugcPhoto): RedirectResponse
    {
        $ugcPhoto->update(['status' => 'rejected']);

        if ($ugcPhoto->user) {
            $ugcPhoto->user->notify(new GenericNotification(
                'Foto komunitas kamu belum bisa ditampilkan. Coba kirim foto lain ya.',
                '📸'
            ));
        }

        return back()->with('success', 'Foto ditolak.');
    }

    public function toggleFeatured(UgcPhoto $ugcPhoto): RedirectResponse
    {
        $ugcPhoto->update(['is_featured' => !$ugcPhoto->is_featured]);
        return back()->with('success', 'Status featured diperbarui.');
    }

    public function destroy(UgcPhoto $ugcPhoto): RedirectResponse
    {
        $ugcPhoto->delete();
        return back()->with('success', 'Foto dihapus.');
    }
}
