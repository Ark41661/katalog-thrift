<?php
namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(): View
    {
        return view('member.profile', [
            'user'      => auth()->user(),
            'storeName' => config('catalog.store_name'),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = auth()->user();
        $data = $request->validate([
            'name'  => ['required', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:20'],
            'bio'   => ['nullable', 'string', 'max:500'],
        ]);

        $user->update($data);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
