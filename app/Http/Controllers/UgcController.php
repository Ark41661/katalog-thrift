<?php
namespace App\Http\Controllers;

use App\Models\UgcPhoto;
use App\Models\User;
use App\Notifications\GenericNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UgcController extends Controller
{
    public function index(): View
    {
        $photos = UgcPhoto::with('product')
            ->where('status', 'approved')
            ->latest()
            ->get();

        return view('public.ugc.index', [
            'storeName' => config('catalog.store_name'),
            'photos'    => $photos,
        ]);
    }

    public function submit(Request $request): RedirectResponse
    {
        $request->validate([
            'submitter_name'      => ['required', 'string', 'max:100'],
            'submitter_instagram' => ['nullable', 'string', 'max:100'],
            'photo'               => ['required', 'image', 'max:10240'], // 10MB
            'caption'             => ['nullable', 'string', 'max:1000'],
            'product_id'          => ['nullable', 'exists:products,id'],
        ]);

        $path = $request->file('photo')->store('ugc', 'public');

        UgcPhoto::create([
            'user_id'             => auth()->id(),
            'product_id'          => $request->product_id,
            'submitter_name'      => $request->submitter_name,
            'submitter_instagram' => $request->submitter_instagram,
            'photo'               => $path,
            'caption'             => $request->caption,
            'status'              => 'pending',
        ]);

        // Notify admins about new UGC submission
        User::where('role', 'admin')->get()->each(function ($admin) {
            $admin->notify(new GenericNotification(
                'Ada foto komunitas baru menunggu persetujuan.',
                '📸',
                route('admin.ugc.index')
            ));
        });

        return back()->with('ugc_success', 'Foto kamu berhasil dikirim! Akan ditampilkan setelah disetujui admin.');
    }
}
