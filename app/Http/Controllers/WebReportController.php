<?php

namespace App\Http\Controllers;

use App\Models\WebReport;
use App\Models\User;
use App\Notifications\GenericNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WebReportController extends Controller
{
    public function create(): View
    {
        return view('public.report', [
            'storeName' => config('catalog.store_name'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'    => ['required', 'string', 'max:100'],
            'email'   => ['required', 'email', 'max:100'],
            'category' => ['required', 'in:bug,saran,konten_tidak_pantas,penyalahgunaan,lainnya'],
            'message' => ['required', 'string', 'max:1000'],
        ]);

        $data['user_id'] = auth()->id();
        $data['status'] = 'pending';

        WebReport::create($data);

        User::where('role', 'admin')->get()->each(function ($admin) {
            $admin->notify(new GenericNotification(
                'Laporan web baru masuk. Segera periksa.',
                '🌐',
                route('admin.web-reports.index')
            ));
        });

        return redirect()->route('web-report.create')
            ->with('success', 'Laporan berhasil dikirim. Terima kasih atas masukan Anda.');
    }
}
