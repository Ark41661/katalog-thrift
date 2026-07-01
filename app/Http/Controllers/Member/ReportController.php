<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductReport;
use App\Models\User;
use App\Notifications\GenericNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function store(Request $request, string $slug): RedirectResponse
    {
        $product = Product::where('slug', $slug)->firstOrFail();

        $request->validate([
            'reason' => ['required', 'in:harga_tidak_wajar,foto_palsu,produk_tidak_sesuai,lainnya'],
            'detail' => ['nullable', 'string', 'max:500'],
        ]);

        $report = ProductReport::updateOrCreate(
            ['user_id' => auth()->id(), 'product_id' => $product->id],
            ['reason' => $request->reason, 'detail' => $request->detail, 'status' => 'pending']
        );

        $reasonLabels = [
            'harga_tidak_wajar'     => 'Harga Tidak Wajar',
            'foto_palsu'            => 'Foto Palsu',
            'produk_tidak_sesuai'   => 'Produk Tidak Sesuai',
            'lainnya'               => 'Lainnya',
        ];

        // Notify admins
        User::where('role', 'admin')->get()->each(function ($admin) use ($product, $reasonLabels, $report) {
            $admin->notify(new GenericNotification(
                "Laporan baru: {$product->name} - {$reasonLabels[$report->reason]}.",
                '🚨',
                route('admin.reports.index')
            ));
        });

        // Notify partner
        if ($product->partner?->user) {
            $product->partner->user->notify(new GenericNotification(
                "Produk {$product->name} dilaporkan oleh pembeli. Segera periksa detailnya.",
                '⚠️',
                route('partner.dashboard')
            ));
        }

        return back()->with('success', 'Laporan berhasil dikirim. Tim kami akan meninjau dalam 1x24 jam.');
    }
}
