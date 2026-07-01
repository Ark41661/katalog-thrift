<?php
namespace App\Http\Controllers;

use App\Models\VipSubscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class VipSubscriberController extends Controller
{
    public function subscribe(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'name'  => ['nullable', 'string', 'max:100'],
        ]);

        VipSubscriber::firstOrCreate(
            ['email' => $request->email],
            ['name' => $request->name, 'is_active' => true, 'confirmed_at' => now()]
        );

        return back()->with('vip_success', 'Kamu berhasil bergabung sebagai VIP Member! 🎉');
    }

    public function unsubscribe(string $token): RedirectResponse
    {
        $sub = VipSubscriber::where('token', $token)->firstOrFail();
        $sub->update(['is_active' => false]);
        return redirect()->route('catalog.index')->with('success', 'Kamu telah berhenti berlangganan.');
    }
}
