<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use App\Notifications\GenericNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminPartnerController extends Controller
{
    public function index(Request $request): View
    {
        $status   = $request->get('status', 'pending');
        $partners = Partner::with('user')
            ->when($status !== 'all', fn($q) => $q->where('status', $status))
            ->latest()
            ->paginate(20);

        return view('admin.partners.index', [
            'partners'      => $partners,
            'activeStatus'  => $status,
            'pendingCount'  => Partner::where('status', 'pending')->count(),
        ]);
    }

    public function approve(Partner $partner): RedirectResponse
    {
        $partner->update([
            'status'      => 'approved',
            'approved_at' => now(),
            'rejection_reason' => null,
        ]);

        if ($partner->user) {
            $partner->user->notify(new GenericNotification(
                "Selamat! Toko {$partner->store_name} telah disetujui. Mulai unggah produk sekarang.",
                '✅',
                route('partner.dashboard')
            ));
        }

        return back()->with('success', "Mitra {$partner->store_name} berhasil disetujui.");
    }

    public function reject(Request $request, Partner $partner): RedirectResponse
    {
        $request->validate(['reason' => ['required', 'string', 'max:500']]);

        $partner->update([
            'status'           => 'rejected',
            'rejection_reason' => $request->reason,
        ]);

        if ($partner->user) {
            $partner->user->notify(new GenericNotification(
                "Pendaftaran mitra {$partner->store_name} ditolak. Alasan: {$request->reason}",
                '❌'
            ));
        }

        return back()->with('success', "Mitra {$partner->store_name} ditolak.");
    }

    public function suspend(Request $request, Partner $partner): RedirectResponse
    {
        $request->validate(['reason' => ['nullable', 'string', 'max:500']]);

        $partner->update([
            'status'           => 'suspended',
            'rejection_reason' => $request->reason,
        ]);

        if ($partner->user) {
            $reasonText = $request->reason ? " Alasan: {$request->reason}" : '';
            $partner->user->notify(new GenericNotification(
                "Toko {$partner->store_name} ditangguhkan.{$reasonText}",
                '⛔'
            ));
        }

        return back()->with('success', "Mitra {$partner->store_name} ditangguhkan.");
    }

    public function toggleVerified(Partner $partner): RedirectResponse
    {
        $partner->update(['is_verified' => ! $partner->is_verified]);
        $label = $partner->is_verified ? 'diberikan' : 'dicabut';
        return back()->with('success', "Badge terverifikasi {$label} untuk {$partner->store_name}.");
    }
}
